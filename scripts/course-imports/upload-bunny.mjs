import { createHash } from 'node:crypto';
import { createReadStream, existsSync, mkdirSync, readFileSync, renameSync, statSync, writeFileSync } from 'node:fs';
import { basename, dirname, isAbsolute, join, resolve } from 'node:path';
import process from 'node:process';
import * as tus from 'tus-js-client';

const TUS_ENDPOINT = 'https://video.bunnycdn.com/tusupload';
const DEFAULT_SOURCE_ROOT = 'storage/app/course-imports/sources';
const DEFAULT_STATE_ROOT = 'storage/app/course-imports/upload-state';
const CHUNK_SIZE = 64 * 1024 * 1024;

function parseArguments(argv) {
    const options = { courses: [], sourceRoot: DEFAULT_SOURCE_ROOT, stateRoot: DEFAULT_STATE_ROOT };
    for (let index = 0; index < argv.length; index += 1) {
        const argument = argv[index];
        if (argument === '--course') {
            options.courses.push(argv[++index]);
        } else if (argument === '--source-root') {
            options.sourceRoot = argv[++index];
        } else if (argument === '--state-root') {
            options.stateRoot = argv[++index];
        } else if (argument === '--dry-run') {
            options.dryRun = true;
        } else if (argument === '--help') {
            options.help = true;
        } else {
            throw new Error(`Argumento desconhecido: ${argument}`);
        }
    }
    return options;
}

function loadEnv(path) {
    if (!existsSync(path)) return {};
    const values = {};
    for (const line of readFileSync(path, 'utf8').split(/\r?\n/)) {
        const match = line.match(/^([A-Z0-9_]+)=(.*)$/);
        if (!match) continue;
        values[match[1]] = match[2].replace(/^(['"])(.*)\1$/, '$2');
    }
    return values;
}

function readJson(path) {
    return JSON.parse(readFileSync(path, 'utf8'));
}

function writeJsonAtomic(path, value) {
    mkdirSync(dirname(path), { recursive: true });
    const temporaryPath = `${path}.tmp`;
    writeFileSync(temporaryPath, `${JSON.stringify(value, null, 2)}\n`, 'utf8');
    renameSync(temporaryPath, path);
}

async function bunnyRequest(path, apiKey, options = {}) {
    const response = await fetch(`https://video.bunnycdn.com${path}`, {
        ...options,
        headers: {
            Accept: 'application/json',
            AccessKey: apiKey,
            ...(options.body ? { 'Content-Type': 'application/json' } : {}),
            ...options.headers,
        },
    });
    const body = await response.text();
    if (!response.ok) {
        throw new Error(`Bunny API ${response.status}: ${body.slice(0, 500)}`);
    }
    return body ? JSON.parse(body) : null;
}

async function createVideo({ apiKey, libraryId, collectionId, title }) {
    return bunnyRequest(`/library/${libraryId}/videos`, apiKey, {
        method: 'POST',
        body: JSON.stringify({ title, collectionId }),
    });
}

function uploadFile({ apiKey, libraryId, collectionId, videoId, title, filePath, uploadUrl, onUploadUrl }) {
    const size = statSync(filePath).size;
    const expiration = Math.floor(Date.now() / 1000) + (7 * 24 * 60 * 60);
    const signature = createHash('sha256')
        .update(`${libraryId}${apiKey}${expiration}${videoId}`)
        .digest('hex');

    return new Promise((resolveUpload, rejectUpload) => {
        let lastPercent = -1;
        const upload = new tus.Upload(createReadStream(filePath), {
            endpoint: TUS_ENDPOINT,
            uploadUrl: uploadUrl || null,
            uploadSize: size,
            chunkSize: CHUNK_SIZE,
            retryDelays: [0, 3000, 5000, 10000, 20000, 60000, 60000],
            removeFingerprintOnSuccess: true,
            storeFingerprintForResuming: false,
            headers: {
                AuthorizationSignature: signature,
                AuthorizationExpire: String(expiration),
                LibraryId: String(libraryId),
                VideoId: videoId,
            },
            metadata: {
                filetype: 'video/mp4',
                title,
                collection: collectionId,
            },
            onUploadUrlAvailable() {
                onUploadUrl(upload.url);
            },
            onProgress(bytesUploaded, bytesTotal) {
                const percent = Math.floor((bytesUploaded / bytesTotal) * 100);
                if (percent >= lastPercent + 5 || percent === 100) {
                    lastPercent = percent;
                    process.stdout.write(`[${new Date().toISOString()}] ${basename(filePath)} ${percent}%\n`);
                }
            },
            onError(error) {
                rejectUpload(error);
            },
            onSuccess() {
                resolveUpload(upload.url);
            },
        });
        upload.start();
    });
}

function resolveSourcePath(manifest, lesson) {
    if (isAbsolute(lesson.source_path)) return lesson.source_path;
    return resolve(manifest.source.root, lesson.source_path);
}

async function uploadCourse(manifestPath, stateRoot, apiKey, dryRun) {
    const manifest = readJson(manifestPath);
    const courseKey = manifest.source.key;
    const statePath = join(stateRoot, `${courseKey.toLowerCase()}.json`);
    const state = existsSync(statePath)
        ? readJson(statePath)
        : { version: 1, course: courseKey, library_id: manifest.provider.library_id, files: {} };

    process.stdout.write(`\n=== ${courseKey}: ${manifest.lessons.length} vídeos ===\n`);
    for (const lesson of manifest.lessons) {
        const filePath = resolveSourcePath(manifest, lesson);
        if (!existsSync(filePath)) throw new Error(`Arquivo ausente: ${filePath}`);

        const entry = state.files[lesson.source_path] || {};
        if (entry.status === 'uploaded' && entry.video_id) {
            process.stdout.write(`SKIP ${lesson.source_file} (${entry.video_id})\n`);
            continue;
        }
        if (dryRun) {
            process.stdout.write(`DRY  ${lesson.source_file}\n`);
            continue;
        }

        if (!entry.video_id) {
            const video = await createVideo({
                apiKey,
                libraryId: manifest.provider.library_id,
                collectionId: manifest.provider.collection_id,
                title: lesson.title,
            });
            entry.video_id = video.guid;
            entry.status = 'created';
            entry.created_at = new Date().toISOString();
            state.files[lesson.source_path] = entry;
            writeJsonAtomic(statePath, state);
        }

        process.stdout.write(`UPLOAD ${lesson.source_file} -> ${entry.video_id}\n`);
        try {
            const finalUrl = await uploadFile({
                apiKey,
                libraryId: manifest.provider.library_id,
                collectionId: manifest.provider.collection_id,
                videoId: entry.video_id,
                title: lesson.title,
                filePath,
                uploadUrl: entry.upload_url,
                onUploadUrl(url) {
                    entry.upload_url = url;
                    entry.status = 'uploading';
                    entry.updated_at = new Date().toISOString();
                    writeJsonAtomic(statePath, state);
                },
            });
            entry.upload_url = finalUrl;
            entry.status = 'uploaded';
            entry.uploaded_at = new Date().toISOString();
            entry.size_bytes = statSync(filePath).size;
            writeJsonAtomic(statePath, state);
        } catch (error) {
            entry.status = 'failed';
            entry.error = String(error?.message || error).slice(0, 1000);
            entry.updated_at = new Date().toISOString();
            writeJsonAtomic(statePath, state);
            throw error;
        }
    }
}

async function main() {
    const options = parseArguments(process.argv.slice(2));
    if (options.help) {
        process.stdout.write('Uso: node scripts/course-imports/upload-bunny.mjs [--course ANCORD] [--dry-run]\n');
        return;
    }

    const env = { ...loadEnv(resolve('.env')), ...process.env };
    const apiKey = env.BUNNY_STREAM_API_KEY;
    if (!options.dryRun && !apiKey) throw new Error('BUNNY_STREAM_API_KEY não configurada no .env.');

    const sourceRoot = resolve(options.sourceRoot);
    const stateRoot = resolve(options.stateRoot);
    mkdirSync(stateRoot, { recursive: true });

    const requested = options.courses.map((course) => course.toLowerCase());
    const catalog = readJson(resolve('resources/course-imports/source-catalog-2026.json'));
    const courseKeys = requested.length ? requested : catalog.courses.map((course) => course.key.toLowerCase());
    for (const courseKey of courseKeys) {
        const manifestPath = join(sourceRoot, `${courseKey}.json`);
        if (!existsSync(manifestPath)) throw new Error(`Manifest-fonte ausente: ${manifestPath}`);
        await uploadCourse(manifestPath, stateRoot, apiKey, Boolean(options.dryRun));
    }
}

main().catch((error) => {
    process.stderr.write(`${error?.stack || error}\n`);
    process.exitCode = 1;
});
