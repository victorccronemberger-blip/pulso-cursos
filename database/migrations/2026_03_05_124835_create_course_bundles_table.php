<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_bundles', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('course_ids', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->integer('subscription_limit')->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->string('price', 255)->nullable();
            $table->longText('bundle_details')->nullable();
            $table->string('status', 255)->nullable();
            $table->string('banner', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        
    }

    public function down(): void
    {
        Schema::dropIfExists('course_bundles');
        Schema::table('seo_fields', function (Blueprint $table) {
            $table->dropColumn('bundle_id');
        });
    }
};
