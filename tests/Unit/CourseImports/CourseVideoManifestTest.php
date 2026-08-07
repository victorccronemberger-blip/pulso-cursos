<?php

namespace Tests\Unit\CourseImports;

use App\Domain\CourseImports\CourseVideoManifest;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CourseVideoManifestTest extends TestCase
{
    public function test_it_accepts_a_valid_versioned_manifest(): void
    {
        $manifest = new CourseVideoManifest($this->validManifest());

        $this->assertSame('course-slug', $manifest->courseSlug());
        $this->assertCount(1, $manifest->sections());
        $this->assertCount(1, $manifest->lessons());
        $this->assertSame(1, $manifest->curriculumSortStep());
        $this->assertSame([], $manifest->content());
    }

    public function test_it_exposes_optional_curriculum_and_content_configuration(): void
    {
        $data = $this->validManifest();
        $data['curriculum'] = ['sort_step' => 100];
        $data['content'] = ['final_section' => 'module-1'];

        $manifest = new CourseVideoManifest($data);

        $this->assertSame(100, $manifest->curriculumSortStep());
        $this->assertSame('module-1', $manifest->content()['final_section']);
    }

    public function test_it_rejects_duplicate_provider_ids(): void
    {
        $data = $this->validManifest();
        $data['lessons'][] = $data['lessons'][0];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Duplicate provider_id');

        new CourseVideoManifest($data);
    }

    public function test_it_rejects_unknown_section_keys(): void
    {
        $data = $this->validManifest();
        $data['lessons'][0]['section'] = 'unknown';

        $this->expectException(InvalidArgumentException::class);
        new CourseVideoManifest($data);
    }

    private function validManifest(): array
    {
        return [
            'version' => 1,
            'course' => ['slug' => 'course-slug'],
            'provider' => ['driver' => 'bunny_stream', 'library_id' => 123],
            'sections' => [['key' => 'module-1', 'title' => 'Module 1', 'sort' => 1]],
            'lessons' => [[
                'provider_id' => '41854dd4-df5a-4267-9e8d-e7b0b6933f6f',
                'title' => 'Introduction',
                'section' => 'module-1',
                'sort' => 1,
                'duration' => '00:42:53',
            ]],
        ];
    }
}
