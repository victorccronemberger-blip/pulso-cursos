<?php

namespace App\Domain\CourseImports\Providers;

use App\Domain\CourseImports\Contracts\VideoProvider;
use InvalidArgumentException;

class BunnyStreamProvider implements VideoProvider
{
    public function name(): string
    {
        return 'bunny_stream';
    }

    public function lessonType(): string
    {
        return 'bunny_stream';
    }

    public function source(array $provider, array $lesson): string
    {
        $libraryId = filter_var($provider['library_id'] ?? null, FILTER_VALIDATE_INT);
        $videoId = trim((string) ($lesson['provider_id'] ?? ''));

        if (! $libraryId || ! preg_match('/^[a-f0-9-]{36}$/i', $videoId)) {
            throw new InvalidArgumentException('Bunny Stream library_id or provider_id is invalid.');
        }

        return "https://iframe.mediadelivery.net/embed/{$libraryId}/{$videoId}";
    }
}
