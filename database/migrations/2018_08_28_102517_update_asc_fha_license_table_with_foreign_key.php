<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAscFhaLicenseTableWithForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('user_fha_state_approved', function (Blueprint $table) {
            $table->integer('fha_id')->unsigned()->default(0)->after('user_id')->index();
            $table->foreign('fha_id')->references('id')->on('user_fha_license')->onDelete('cascade');
        });

        Schema::table('user_asc_license', function (Blueprint $table) {
            $table->integer('asc_id')->unsigned()->default(0)->after('user_id')->index();
            $table->foreign('asc_id')->references('id')->on('asc_data')->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('user_fha_state_approved', function (Blueprint $table) {
            $table->dropForeign('user_fha_state_approved_fha_id_foreign');
            $table->dropColumn('fha_id');
        });

        Schema::table('user_asc_license', function (Blueprint $table) {
            $table->dropForeign('user_asc_license_asc_id_foreign');
            $table->dropColumn('asc_id');
        });

        Schema::enableForeignKeyConstraints();
    }
}
