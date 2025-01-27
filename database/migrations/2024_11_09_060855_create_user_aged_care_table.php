<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAgedCareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_aged_care', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('user_id');
            $table->boolean('participant');
            $table->text('number')->nullable();
            $table->text('provider_name')->nullable();
            $table->enum('care_package',['LEVEL_1','LEVEL_2','LEVEL_3','LEVEL_4']);
            $table->text('case_manager_name')->nullable();
            $table->text('case_manager_phone')->nullable();
            $table->text('case_manager_email')->nullable();
            $table->boolean('chsp_support')->nullable()->comment('Commonwealth Home Support Program');
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
        Schema::dropIfExists('user_aged_care');
    }
}
