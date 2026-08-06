<?php

 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_histories', function (Blueprint $table) {

            $table->id(); 

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();

            $table->string('payment_type', 255)->nullable();

            $table->double('amount')->nullable();
            $table->string('invoice', 255)->nullable();

            $table->integer('date_added')->nullable();
            $table->integer('last_modified')->nullable();

            $table->double('admin_revenue')->nullable();
            $table->double('instructor_revenue')->nullable();

            $table->double('tax')->nullable();
            $table->integer('instructor_payment_status')->nullable();

            $table->string('transaction_id', 255)->nullable();
            $table->string('session_id', 255)->nullable();
            $table->string('coupon', 255)->nullable();

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_histories');
    }
}
