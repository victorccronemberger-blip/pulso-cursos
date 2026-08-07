<?php

namespace App\Console\Commands;

use App\Domain\CourseImports\Catalog\BunnyStreamCatalogClient;
use Illuminate\Console\Command;
use Throwable;

class ExportBunnyStreamCatalog extends Command
{
    protected $signature = 'courses:bunny-catalog
        {library : Bunny Stream library ID}
        {--key-env=BUNNY_STREAM_READ_KEY : Environment variable containing the read-only key}
        {--output= : JSON output path relative to the project root}';

    protected $description = 'Export a normalized, read-only Bunny Stream video catalog.';

    public function handle(BunnyStreamCatalogClient $client): int
    {
        $keyName = (string) $this->option('key-env');
        $key = (string) env($keyName, '');

        try {
            $videos = $client->fetch((int) $this->argument('library'), $key);
            $json = json_encode(['videos' => $videos], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());
            return self::FAILURE;
        }

        if ($output = $this->option('output')) {
            $path = base_path((string) $output);
            if (! is_dir(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            file_put_contents($path, $json . PHP_EOL);
            $this->info("Catalog exported to {$path}");
        } else {
            $this->line($json);
        }

        return self::SUCCESS;
    }
}
