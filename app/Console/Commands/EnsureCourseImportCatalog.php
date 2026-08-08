<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use JsonException;
use RuntimeException;

class EnsureCourseImportCatalog extends Command
{
    protected $signature = 'courses:ensure-import-catalog
        {catalog=resources/course-imports/source-catalog-2026.json : Catálogo JSON de origem}
        {--dry-run : Apenas audita cursos existentes e ausentes}
        {--create-missing : Cria somente cursos ausentes que tenham metadados catalog}';

    protected $description = 'Audita e, com autorização explícita, cria cursos ausentes do catálogo de importação.';

    /** @throws JsonException */
    public function handle(): int
    {
        $path = base_path((string) $this->argument('catalog'));
        if (! is_file($path)) {
            throw new RuntimeException("Catálogo não encontrado: {$path}");
        }

        $catalog = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        $defaults = is_array($catalog['course_defaults'] ?? null) ? $catalog['course_defaults'] : [];
        $rows = [];
        $missingWithoutMetadata = false;

        foreach ($catalog['courses'] ?? [] as $course) {
            $slug = trim((string) ($course['course_slug'] ?? ''));
            if ($slug === '') {
                $rows[] = [$course['key'] ?? '?', '-', 'sem slug', '-'];
                $missingWithoutMetadata = true;

                continue;
            }

            $metadata = is_array($course['catalog'] ?? null) ? $course['catalog'] : [];
            $categoryId = isset($metadata['category_id']) ? (int) $metadata['category_id'] : null;
            $category = is_array($metadata['category'] ?? null) ? $metadata['category'] : [];
            if ($category !== []) {
                $categoryId = DB::table('categories')->where('slug', $category['slug'])->value('id');
                if (! $categoryId && $this->option('create-missing') && ! $this->option('dry-run')) {
                    $categoryId = DB::table('categories')->insertGetId([
                        'parent_id' => 0,
                        'title' => $category['title'],
                        'slug' => $category['slug'],
                        'sort' => (int) $category['sort'],
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $existing = DB::table('courses')->where('slug', $slug)->first();
            if ($existing) {
                $state = 'existente';
                if ($categoryId && (int) $existing->category_id !== (int) $categoryId && $this->option('create-missing') && ! $this->option('dry-run')) {
                    DB::table('courses')->where('id', $existing->id)->update(['category_id' => $categoryId, 'updated_at' => now()]);
                    $state = 'existente (categoria atualizada)';
                }
                $rows[] = [$course['key'], $slug, $state, $existing->id];

                continue;
            }

            if ($metadata === []) {
                $rows[] = [$course['key'], $slug, 'ausente sem metadados', '-'];
                $missingWithoutMetadata = true;

                continue;
            }

            if ($this->option('dry-run') || ! $this->option('create-missing')) {
                $rows[] = [$course['key'], $slug, 'ausente (pronto para criar)', '-'];

                continue;
            }

            if (! $categoryId) {
                throw new RuntimeException("Categoria não resolvida para {$slug}.");
            }

            $id = DB::transaction(function () use ($slug, $metadata, $defaults, $categoryId): int {
                $now = now();

                return DB::table('courses')->insertGetId([
                    'title' => $metadata['title'],
                    'slug' => $slug,
                    'short_description' => $metadata['short_description'],
                    'user_id' => (int) $defaults['user_id'],
                    'category_id' => $categoryId,
                    'course_type' => $defaults['course_type'],
                    'status' => $defaults['status'],
                    'level' => $metadata['level'],
                    'language' => $defaults['language'],
                    'is_paid' => (int) $defaults['is_paid'],
                    'price' => $metadata['price'],
                    'discounted_price' => $metadata['discounted_price'],
                    'discount_flag' => (int) $defaults['discount_flag'],
                    'thumbnail' => $metadata['thumbnail'],
                    'instructor_ids' => $defaults['instructor_ids'],
                    'average_rating' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });
            $rows[] = [$course['key'], $slug, 'criado', $id];
        }

        $this->table(['Origem', 'Slug', 'Estado', 'ID'], $rows);

        return $missingWithoutMetadata ? self::FAILURE : self::SUCCESS;
    }
}
