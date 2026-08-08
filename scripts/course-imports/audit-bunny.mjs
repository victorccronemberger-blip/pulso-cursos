import { existsSync, readFileSync, readdirSync } from 'node:fs';
import { join, resolve } from 'node:path';
import process from 'node:process';

function loadEnv(path) {
    if (!existsSync(path)) return {};
    return Object.fromEntries(readFileSync(path, 'utf8').split(/\r?\n/).flatMap((line) => {
        const match = line.match(/^([A-Z0-9_]+)=(.*)$/);
        return match ? [[match[1], match[2].replace(/^(['"])(.*)\1$/, '$2')]] : [];
    }));
}

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

const env = { ...loadEnv(resolve('.env')), ...process.env };
const apiKey = env.BUNNY_STREAM_API_KEY;
if (!apiKey) throw new Error('BUNNY_STREAM_API_KEY não configurada.');

const libraryId = 723013;
const stateRoot = resolve('storage/app/course-imports/upload-state');
for (const name of readdirSync(stateRoot).filter((file) => file.endsWith('.json')).sort()) {
    const state = JSON.parse(readFileSync(join(stateRoot, name), 'utf8'));
    const uploaded = Object.values(state.files).filter((file) => file.status === 'uploaded' && file.video_id);
    const videos = await mapConcurrent(uploaded, 8, async (file) => {
        const response = await fetch(`https://video.bunnycdn.com/library/${libraryId}/videos/${file.video_id}`, {
            headers: { Accept: 'application/json', AccessKey: apiKey },
        });
        if (!response.ok) return { status: -1, encodeProgress: 0, hasOriginal: false };
        return response.json();
    });

    const counts = {};
    for (const video of videos) counts[video.status] = (counts[video.status] || 0) + 1;
    const finished = counts[3] || 0;
    const failed = (counts[5] || 0) + (counts[8] || 0) + (counts[-1] || 0);
    const originals = videos.filter((video) => video.hasOriginal).length;
    const averageProgress = videos.length
        ? Math.round(videos.reduce((sum, video) => sum + (video.encodeProgress || 0), 0) / videos.length)
        : 0;
    process.stdout.write(
        `${state.course.padEnd(10)} enviados=${String(uploaded.length).padStart(3)} `
        + `finalizados=${String(finished).padStart(3)} processando=${String(uploaded.length - finished - failed).padStart(3)} `
        + `falhas=${String(failed).padStart(2)} originais=${String(originals).padStart(3)} progresso_médio=${averageProgress}%\n`,
    );
    for (const video of videos.filter((item) => [5, 8, -1].includes(item.status))) {
        const message = Array.isArray(video.transcodingMessages)
            ? video.transcodingMessages.map((item) => item.message || item.value).filter(Boolean).join('; ')
            : '';
        process.stdout.write(`  FALHA ${video.guid || '?'} ${video.title || '?'}${message ? `: ${message}` : ''}\n`);
    }
}
