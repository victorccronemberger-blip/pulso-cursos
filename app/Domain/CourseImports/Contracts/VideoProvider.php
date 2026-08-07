<?php

namespace App\Domain\CourseImports\Contracts;

interface VideoProvider
{
    public function name(): string;

    public function lessonType(): string;

    public function source(array $provider, array $lesson): string;
}
