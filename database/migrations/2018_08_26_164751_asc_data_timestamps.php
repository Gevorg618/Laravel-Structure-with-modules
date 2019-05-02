<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AscDataTimestamps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('asc_data');

        Schema::create('asc_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('st_abbr', 2)->nullable()->default(null)->index();
            $table->string('lic_number')->nullable()->default(null)->index();
            $table->string('lname')->nullable()->default(null)->index();
            $table->string('fname')->nullable()->default(null)->index();
            $table->string('mname')->nullable()->default(null)->index();
            $table->string('name_suffix', 25)->nullable()->default(null)->index();
            $table->string('street')->nullable()->default(null)->index();
            $table->string('city')->nullable()->default(null)->index();
            $table->string('state')->nullable()->default(null)->index();
            $table->string('county')->nullable()->default(null)->index();
            $table->string('county_code')->nullable()->default(null)->index();
            $table->string('zip')->nullable()->default(null)->index();
            $table->string('company')->nullable()->default(null)->index();
            $table->string('phone')->nullable()->default(null)->index();
            $table->boolean('status')->default(null)->nullable()->index();
            $table->tinyInteger('lic_type')->default(null)->nullable()->index();
            $table->datetime('exp_date')->nullable()->default(null)->index();
            $table->datetime('eff_date')->nullable()->default(null)->index();
            $table->datetime('issue_date')->nullable()->default(null)->index();

            $table->text('aqb_compliant')->nullable();
            $table->text('discipline_action')->nullable();
            $table->datetime('discipline_start_date')->nullable()->default(null)->index();
            $table->datetime('discipline_end_date')->nullable()->default(null)->index();

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
        
    }
}
