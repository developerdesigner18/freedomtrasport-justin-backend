<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->date('dob')->nullable();
            $table->text('emg_name')->nullable();
            $table->text('emg_number')->nullable();
            $table->text('emg_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn('dob');
            $table->dropColumn('emg_name');
            $table->dropColumn('emg_number');
            $table->dropColumn('emg_email');
        });
    }
}
