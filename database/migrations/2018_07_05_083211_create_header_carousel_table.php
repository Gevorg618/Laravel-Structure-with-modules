<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeaderCarouselTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('header_carousel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('desktop_image');
            $table->string('mobile_image');
            $table->string('title');
            $table->string('description');
            $table->string('position');
            $table->boolean('is_active')->default(0);
            $table->json('buttons');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('header_carousel');
    }
}
