<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateZipCodeColumnsTable extends Migration
{

    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zip_code', function (Blueprint $table) {
            $table->renameColumn('ZIPCode', 'zip_code');
            $table->renameColumn('ZIPCodeType', 'type');
            $table->renameColumn('City', 'city');
            $table->renameColumn('County', 'county');
            $table->renameColumn('State', 'state');
            $table->renameColumn('Longitude', 'long');
            $table->renameColumn('Latitude', 'lat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zip_code', function (Blueprint $table) {
            $table->renameColumn('zip_code', 'ZIPCode');
            $table->renameColumn('type', 'ZIPCodeType');
            $table->renameColumn('city', 'City');
            $table->renameColumn('county', 'County');
            $table->renameColumn('state', 'State');
            $table->renameColumn('long', 'Longitude');
            $table->renameColumn('lat', 'Latitude');
        });
    }
}
