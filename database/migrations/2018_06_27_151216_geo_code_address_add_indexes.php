<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeoCodeAddressAddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address_geo_code', function (Blueprint $table) {
          $table->index('address');
          $table->index('city');
          $table->index('state');
          $table->index('zip');
          $table->index('country');
          $table->index('lat');
          $table->index('long');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
