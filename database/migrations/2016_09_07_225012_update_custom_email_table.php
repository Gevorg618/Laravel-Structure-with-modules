<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCustomEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_email', function (Blueprint $table) {
            $table->softDeletes();
            $table->unique('email_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_email', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropUnique('email_key');
        });
    }
}
