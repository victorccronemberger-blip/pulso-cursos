<?php

namespace App\Domain\CourseImports;

use InvalidArgumentException;
use JsonException;

class CourseVideoManifest
{
    public function __construct(private readonly array $data)
    {
        $this->validate();
    }

    /** @throws JsonException */
    public static function fromFile(string $path): self
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new InvalidArgumentException("Course manifest is not readable: {$path}");
        }

        return new self(json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR));
    }

    public function courseSlug(): string
    {
        return $this->data['course']['slug'];
    }

    public function provider(): array
    {
        return $this->data['provider'];
    }

    public function sections(): array
    {
        return $this->data['sections'];
    }

    public function lessons(): array
    {
        return $this->data['lessons'];
    }

    private function validate(): void
    {
        if (($this->data['version'] ?? null) !== 1) {
            throw new InvalidArgumentException('Course manifest version must be 1.');
        }

        if (empty($this->data['course']['slug']) || empty($this->data['provider']['driver'])) {
            throw new InvalidArgumentException('Manifest requires course.slug and provider.driver.');
        }

        $sections = $this->data['sections'] ?? [];
        $lessons = $this->data['lessons'] ?? [];
        if (! is_array($sections) || ! is_array($lessons) || $sections === [] || $lessons === []) {
            throw new InvalidArgumentException('Manifest requires non-empty sections and lessons.');
        }

        $sectionKeys = [];
        foreach ($sections as $section) {
            $key = trim((string) ($section['key'] ?? ''));
            if ($key === '' || empty($section['title']) || ! is_numeric($section['sort'] ?? null)) {
                throw new InvalidArgumentException('Every section requires key, title and numeric sort.');
            }
            if (isset($sectionKeys[$key])) {
                throw new InvalidArgumentException("Duplicate section key: {$key}");
            }
            $sectionKeys[$key] = true;
        }

        $providerIds = [];
        foreach ($lessons as $lesson) {
            $providerId = trim((string) ($lesson['provider_id'] ?? ''));
            $sectionKey = trim((string) ($lesson['section'] ?? ''));
            if ($providerId === '' || empty($lesson['title']) || ! isset($sectionKeys[$sectionKey])) {
                throw new InvalidArgumentException('Every lesson requires a unique provider_id, title and valid section.');
            }
            if (! preg_match('/^\d{2,}:\d{2}:\d{2}$/', (string) ($lesson['duration'] ?? ''))) {
                throw new InvalidArgumentException("Invalid lesson duration for provider_id {$providerId}.");
            }
            if (isset($providerIds[$providerId])) {
                throw new InvalidArgumentException("Duplicate provider_id: {$providerId}");
            }
            $providerIds[$providerId] = true;
        }
    }
}
