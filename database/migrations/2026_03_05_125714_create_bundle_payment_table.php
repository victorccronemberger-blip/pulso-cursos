<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bundle_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('bundle_id')->nullable();
            $table->string('payment_method', 255)->nullable();
            $table->string('payment_details', 255)->nullable();
            $table->string('amount', 255)->nullable();
            $table->string('admin_revenue', 255)->nullable();
            $table->string('instructor_revenue', 255)->nullable();
            $table->string('tax', 255)->nullable();
            $table->integer('status')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundle_payments');
    }
};
