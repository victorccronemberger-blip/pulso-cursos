<?php

namespace App\Domain\CourseImports\Catalog;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class BunnyStreamCatalogClient
{
    public function fetch(int $libraryId, string $readOnlyKey): array
    {
        if ($libraryId < 1 || trim($readOnlyKey) === '') {
            throw new RuntimeException('Bunny Stream library and read-only key are required.');
        }

        $page = 1;
        $videos = [];
        do {
            $payload = $this->request($readOnlyKey)
                ->get("https://video.bunnycdn.com/library/{$libraryId}/videos", [
                    'page' => $page,
                    'itemsPerPage' => 100,
                    'orderBy' => 'date',
                ])
                ->throw()
                ->json();

            foreach ($payload['items'] ?? [] as $video) {
                $seconds = (int) ($video['length'] ?? 0);
                $videos[] = [
                    'source_file' => (string) ($video['title'] ?? ''),
                    'provider_id' => (string) ($video['guid'] ?? ''),
                    'duration' => sprintf('%02d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60),
                    'status' => (int) ($video['status'] ?? 0),
                ];
            }

            $page++;
            $totalItems = (int) ($payload['totalItems'] ?? count($videos));
        } while (count($videos) < $totalItems);

        return $videos;
    }

    private function request(string $readOnlyKey): PendingRequest
    {
        return Http::acceptJson()
            ->withHeaders(['AccessKey' => $readOnlyKey])
            ->connectTimeout(10)
            ->timeout(30)
            ->retry(2, 300);
    }
}
