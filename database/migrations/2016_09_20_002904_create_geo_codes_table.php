<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_geo_code', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address');
            $table->string('address_not_formatted');
            $table->string('city');
            $table->string('state');
            $table->string('zip')->nullable();
            $table->string('country');
            $table->double('lat',15,12);
            $table->double('long',15,12);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address_geo_code');
    }
}
