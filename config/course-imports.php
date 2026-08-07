<?php

use App\Domain\CourseImports\Providers\BunnyStreamProvider;

return [
    'video_providers' => [
        'bunny_stream' => BunnyStreamProvider::class,
    ],
];
