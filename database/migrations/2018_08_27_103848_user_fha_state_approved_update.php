<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserFhaStateApprovedUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_fha_state_approved');

        Schema::create('user_fha_state_approved', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(0)->index();
            $table->string('state', 2)->index();
            $table->string('license_number')->index();
            $table->string('license_type')->index();
            $table->date('expiration')->index();

            $table->timestamps();

//            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_fha_state_approved');
    }
}
