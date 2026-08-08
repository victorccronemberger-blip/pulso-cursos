import { existsSync, mkdirSync, readFileSync, readdirSync, renameSync, writeFileSync } from 'node:fs';
import { basename, dirname, join, resolve } from 'node:path';
import process from 'node:process';

function readJson(path) {
    return JSON.parse(readFileSync(path, 'utf8'));
}

function writeJsonAtomic(path, value) {
    mkdirSync(dirname(path), { recursive: true });
    const temporaryPath = `${path}.tmp`;
    writeFileSync(temporaryPath, `${JSON.stringify(value, null, 2)}\n`, 'utf8');
    renameSync(temporaryPath, path);
}

function sourceKey(path) {
    const stem = basename(path).replace(/\.[^.]+$/, '').replace(/^\d+_/, '');
    return stem.split('_', 1)[0].toUpperCase().replace(/[^A-Z0-9]/g, '');
}

function descriptions(courseRoot) {
    const textDirectory = join(courseRoot, 'Textos');
    if (!existsSync(textDirectory)) return new Map();
    const values = new Map();
    for (const name of readdirSync(textDirectory).filter((file) => file.toLowerCase().endsWith('.txt'))) {
        const key = sourceKey(name);
        if (!values.has(key)) values.set(key, readFileSync(join(textDirectory, name), 'utf8').trim());
    }
    return values;
}

const sourceRoot = resolve('storage/app/course-imports/sources');
const stateRoot = resolve('storage/app/course-imports/upload-state');
const pendingRoot = resolve('storage/app/course-imports/final-pending');
const catalog = readJson(resolve('resources/course-imports/source-catalog-2026.json'));

let incomplete = false;
for (const course of catalog.courses) {
    const key = course.key.toLowerCase();
    const sourcePath = join(sourceRoot, `${key}.json`);
    const statePath = join(stateRoot, `${key}.json`);
    if (!existsSync(sourcePath) || !existsSync(statePath)) {
        process.stdout.write(`${course.key}: aguardando upload\n`);
        incomplete = true;
        continue;
    }

    const source = readJson(sourcePath);
    const state = readJson(statePath);
    const courseRoot = join(source.source.root, source.source.key);
    const lessonDescriptions = descriptions(courseRoot);
    const missing = source.lessons.filter((lesson) => state.files[lesson.source_path]?.status !== 'uploaded');
    if (missing.length) {
        process.stdout.write(`${course.key}: ${source.lessons.length - missing.length}/${source.lessons.length} enviados\n`);
        incomplete = true;
        continue;
    }

    const manifest = {
        version: 1,
        course: { slug: source.course.slug },
        provider: {
            driver: source.provider.driver,
            library_id: source.provider.library_id,
        },
        curriculum: source.curriculum,
        content: {
            quiz_attempts: source.content.quiz_attempts,
            final_section: source.content.final_section,
            section_overrides: source.content.section_overrides,
        },
        sections: source.sections,
        lessons: source.lessons.map((lesson) => ({
            source_file: lesson.source_file,
            provider_id: state.files[lesson.source_path].video_id,
            title: lesson.title,
            description: lessonDescriptions.get(sourceKey(lesson.source_file)) || '',
            section: lesson.section,
            sort: lesson.sort,
            duration: lesson.duration,
            is_free: lesson.is_free,
        })),
    };

    const destination = source.course.slug
        ? resolve(`resources/course-imports/${key}-2026.json`)
        : join(pendingRoot, `${key}-2026.json`);
    writeJsonAtomic(destination, manifest);
    process.stdout.write(`${course.key}: manifesto final -> ${destination}\n`);
}

if (incomplete) process.exitCode = 2;
