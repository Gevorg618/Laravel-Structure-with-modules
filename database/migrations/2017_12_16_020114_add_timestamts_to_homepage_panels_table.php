<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestamtsToHomepagePanelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homepage_panels', function (Blueprint $table) {
            $table->dropColumn('created_date');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homepage_panels', function (Blueprint $table) {
            $table->integer('created_date', 10)->default(0);
            $table->dropTimestamps();
        });
    }
}
