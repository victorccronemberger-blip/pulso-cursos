<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            // relations
            $table->foreignId('exam_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // files
            $table->string('submitted_pdf')->nullable();     // student or scanned copy
            $table->json('annotation_data')->nullable();     // PDF.js annotations
            $table->string('annotated_pdf')->nullable();     // final checked script

            // evaluation
            $table->integer('obtained_marks')->nullable();
            $table->text('remarks')->nullable();

            // workflow status
            $table->enum('status', [
                'pending',     // submitted but not checked
                'checking',    // teacher opened
                'checked',     // evaluated
                'published'    // visible to student
            ])->default('pending');

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('checked_at')->nullable();

            $table->timestamps();

            // prevent duplicate submission
            $table->unique(['exam_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
