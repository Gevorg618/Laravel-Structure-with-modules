<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserStateFhaLicenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_fha_license');

        Schema::create('user_fha_license', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname')->default(null)->nullable()->index();
            $table->string('lastname')->default(null)->nullable()->index();
            $table->string('middlename')->default(null)->nullable()->index();
            $table->string('address')->default(null)->nullable()->index();
            $table->string('city')->default(null)->nullable()->index();
            $table->string('state')->default(null)->nullable()->index();
            $table->string('zip')->default(null)->nullable()->index();
            $table->string('company')->default(null)->nullable()->index();
            $table->string('license_number')->default(null)->nullable()->index();
            $table->string('license_type')->default(null)->nullable()->index();
            $table->date('expiration')->default(null)->nullable()->index();

            $table->decimal('pos_lat', 15, 10)->nullable()->default(null)->index();
            $table->decimal('pos_long', 15, 10)->nullable()->default(null)->index();

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
        Schema::dropIfExists('user_fha_license');
    }
}
