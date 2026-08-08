import { existsSync, readFileSync, readdirSync, renameSync, writeFileSync } from 'node:fs';
import { basename, join, resolve } from 'node:path';
import process from 'node:process';

function loadEnv(path) {
    if (!existsSync(path)) return {};
    return Object.fromEntries(readFileSync(path, 'utf8').split(/\r?\n/).flatMap((line) => {
        const match = line.match(/^([A-Z0-9_]+)=(.*)$/);
        return match ? [[match[1], match[2].replace(/^(['"])(.*)\1$/, '$2')]] : [];
    }));
}

const apply = process.argv.includes('--apply');
const env = { ...loadEnv(resolve('.env')), ...process.env };
const apiKey = env.BUNNY_STREAM_API_KEY;
if (apply && !apiKey) throw new Error('BUNNY_STREAM_API_KEY não configurada.');

const libraryId = 723013;
const stateRoot = resolve('storage/app/course-imports/upload-state');
const sourceRoot = resolve('storage/app/course-imports/sources');
let orphanCount = 0;

for (const stateFile of readdirSync(stateRoot).filter((file) => file.endsWith('.json')).sort()) {
    const sourceFile = join(sourceRoot, basename(stateFile));
    if (!existsSync(sourceFile)) continue;
    const statePath = join(stateRoot, stateFile);
    const state = JSON.parse(readFileSync(statePath, 'utf8'));
    const source = JSON.parse(readFileSync(sourceFile, 'utf8'));
    const activePaths = new Set(source.lessons.map((lesson) => lesson.source_path));
    const orphans = Object.entries(state.files).filter(([sourcePath, item]) => (
        !activePaths.has(sourcePath) && item.status === 'uploaded' && item.video_id
    ));
    orphanCount += orphans.length;

    for (const [sourcePath, item] of orphans) {
        process.stdout.write(`${apply ? 'REMOVE' : 'ORPHAN'} ${state.course} ${sourcePath} (${item.video_id})\n`);
        if (!apply) continue;
        const response = await fetch(`https://video.bunnycdn.com/library/${libraryId}/videos/${item.video_id}`, {
            method: 'DELETE',
            headers: { Accept: 'application/json', AccessKey: apiKey },
        });
        if (!response.ok && response.status !== 404) {
            throw new Error(`Falha ao remover ${item.video_id}: HTTP ${response.status}`);
        }
        delete state.files[sourcePath];
    }

    if (apply && orphans.length) {
        const temporaryPath = `${statePath}.tmp`;
        writeFileSync(temporaryPath, `${JSON.stringify(state, null, 2)}\n`, 'utf8');
        renameSync(temporaryPath, statePath);
    }
}

process.stdout.write(`${orphanCount} vídeo(s) órfão(s) ${apply ? 'removido(s)' : 'encontrado(s)'}.\n`);
