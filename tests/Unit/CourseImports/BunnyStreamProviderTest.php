<?php

namespace Tests\Unit\CourseImports;

use App\Domain\CourseImports\Providers\BunnyStreamProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BunnyStreamProviderTest extends TestCase
{
    public function test_it_builds_the_embed_url_expected_by_the_player(): void
    {
        $provider = new BunnyStreamProvider();

        $source = $provider->source(
            ['library_id' => 723013],
            ['provider_id' => '41854dd4-df5a-4267-9e8d-e7b0b6933f6f']
        );

        $this->assertSame(
            'https://iframe.mediadelivery.net/embed/723013/41854dd4-df5a-4267-9e8d-e7b0b6933f6f',
            $source
        );
    }

    public function test_it_rejects_an_invalid_video_id(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new BunnyStreamProvider())->source(['library_id' => 723013], ['provider_id' => 'bad-id']);
    }
}
