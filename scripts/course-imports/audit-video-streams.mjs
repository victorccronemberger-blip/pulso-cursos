import { execFile } from 'node:child_process';
import { existsSync, readFileSync, readdirSync } from 'node:fs';
import { join, resolve } from 'node:path';
import { promisify } from 'node:util';

const execFileAsync = promisify(execFile);
const sourceRoot = resolve('storage/app/course-imports/sources');
const ffprobe = process.argv.find((argument) => argument.startsWith('--ffprobe='))?.slice(10) || 'ffprobe';

async function mapConcurrent(items, concurrency, callback) {
    const results = new Array(items.length);
    let cursor = 0;
    await Promise.all(Array.from({ length: Math.min(concurrency, items.length) }, async () => {
        while (cursor < items.length) {
            const index = cursor++;
            results[index] = await callback(items[index]);
        }
    }));
    return results;
}

if (!existsSync(sourceRoot)) throw new Error('Gere os manifestos de origem antes da auditoria.');

const manifests = readdirSync(sourceRoot)
    .filter((file) => file.endsWith('.json'))
    .map((file) => JSON.parse(readFileSync(join(sourceRoot, file), 'utf8')));
const videos = manifests.flatMap((manifest) => manifest.lessons.map((lesson) => ({
    course: manifest.source.key,
    sourcePath: lesson.source_path,
    absolutePath: resolve(manifest.source.root, lesson.source_path),
})));

const results = await mapConcurrent(videos, 6, async (video) => {
    try {
        const { stdout, stderr } = await execFileAsync(ffprobe, [
            '-v', 'warning', '-show_entries', 'stream=codec_type,duration', '-of', 'json', video.absolutePath,
        ], { maxBuffer: 1024 * 1024 * 4 });
        const streams = JSON.parse(stdout).streams || [];
        const videoDurations = streams.filter((stream) => stream.codec_type === 'video').map((stream) => Number(stream.duration));
        const audioDurations = streams.filter((stream) => stream.codec_type === 'audio').map((stream) => Number(stream.duration));
        const durationDifference = videoDurations.length && audioDurations.length
            ? Math.abs(Math.max(...videoDurations) - Math.max(...audioDurations))
            : null;
        const warnings = stderr.trim();
        return {
            ...video,
            ok: videoDurations.length > 0 && audioDurations.length > 0 && durationDifference <= 1 && !warnings,
            videoStreams: videoDurations.length,
            audioStreams: audioDurations.length,
            durationDifference,
            warnings,
        };
    } catch (error) {
        return { ...video, ok: false, error: error.message };
    }
});

const failures = results.filter((result) => !result.ok);
const byCourse = Object.groupBy(results, (result) => result.course);
for (const [course, items] of Object.entries(byCourse)) {
    process.stdout.write(`${course.padEnd(10)} vídeos=${String(items.length).padStart(3)} problemas=${String(items.filter((item) => !item.ok).length).padStart(2)}\n`);
}
for (const failure of failures) {
    const details = failure.error
        || `vídeo=${failure.videoStreams} áudio=${failure.audioStreams} diferença=${failure.durationDifference?.toFixed(3) ?? 'n/a'}s${failure.warnings ? ` aviso=${failure.warnings.replace(/\s+/g, ' ')}` : ''}`;
    process.stdout.write(`  FALHA ${failure.sourcePath}: ${details}\n`);
}

if (failures.length) process.exitCode = 1;
