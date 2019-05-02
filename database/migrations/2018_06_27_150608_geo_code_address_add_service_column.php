<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeoCodeAddressAddServiceColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address_geo_code', function (Blueprint $table) {
          $table->string('service', 40)->after('hash')->default(null)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address_geo_code', function (Blueprint $table) {
          $table->dropColumn('service');
        });
    }
}
