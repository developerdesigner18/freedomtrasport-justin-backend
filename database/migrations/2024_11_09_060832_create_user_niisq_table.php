<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNiisqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_niisq', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('user_id');
            $table->boolean('participant');
            $table->text('number')->nullable();
            $table->string('plan_manager_name')->nullable();
            $table->string('plan_manager_phone')->nullable();
            $table->string('plan_manager_email')->nullable();
            $table->boolean('health_awareness')->nullable();
            $table->longText('health_details')->nullable();
            $table->longText('other')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_niisq');
    }
}
