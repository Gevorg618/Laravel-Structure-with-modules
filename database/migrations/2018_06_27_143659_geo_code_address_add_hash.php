<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeoCodeAddressAddHash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address_geo_code', function (Blueprint $table) {
          $table->string('hash', 40)->after('id')->default(null)->unique();
          $table->dropColumn('address_not_formatted');
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
          $table->dropColumn('hash');
        });
    }
}
