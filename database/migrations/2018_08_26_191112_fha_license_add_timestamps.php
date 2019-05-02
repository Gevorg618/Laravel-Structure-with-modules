<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FhaLicenseAddTimestamps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('appr_fha_license', 'user_fha_license');

        Schema::table('user_fha_license', function (Blueprint $table) {
            $table->timestamps();
            $table->dropColumn('created');
            $table->dropColumn('updated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('user_fha_license', 'appr_fha_license');

        Schema::table('appr_fha_license', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->integer('created');
            $table->integer('updated');
        });
    }
}
