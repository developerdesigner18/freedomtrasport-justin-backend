<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->string('line_item_base',255)->default('[04_104_0125_6_1]');
            $table->string('surname',255)->nullable();
//            $table->text('dob')->nullable();
//            $table->text('address')->nullable();
            $table->string('line_item_time',255)->default('[04_104_0125_6_1]');
            $table->string('line_item_km',255)->default('[04_509_0125_6_1]');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('surname',255)->nullable();
//            $table->text('dob')->nullable();
//            $table->text('address')->nullable();

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
            $table->dropColumn('line_item_base');
            $table->dropColumn('surname');
//            $table->dropColumn('dob');
//            $table->dropColumn('address');
            $table->dropColumn('line_item_time');
            $table->dropColumn('line_item_km');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('surname');
//            $table->dropColumn('dob');
//            $table->dropColumn('address');
        });
    }
}
