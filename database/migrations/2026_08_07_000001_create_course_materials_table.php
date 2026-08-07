<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->string('source_key');
            $table->string('title');
            $table->string('file_name');
            $table->string('mime_type', 100)->default('application/pdf');
            $table->unsignedBigInteger('size_bytes');
            $table->binary('contents');
            $table->timestamps();

            $table->unique(['course_id', 'source_key']);
            $table->index(['course_id', 'section_id']);
            $table->index('lesson_id');
        });

        // Laravel only exposes Blueprint::binary() (BLOB). Course PDFs need
        // the larger native MySQL type while remaining opaque binary data.
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE course_materials MODIFY contents LONGBLOB NOT NULL');
        }

        Schema::create('course_quiz_contexts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->unsignedBigInteger('quiz_lesson_id');
            $table->string('source_key');
            $table->string('kind', 30)->default('topic');
            $table->timestamps();

            $table->unique('quiz_lesson_id');
            $table->unique(['course_id', 'source_key']);
            $table->index(['course_id', 'section_id', 'kind']);
            $table->index('lesson_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_quiz_contexts');
        Schema::dropIfExists('course_materials');
    }
};
