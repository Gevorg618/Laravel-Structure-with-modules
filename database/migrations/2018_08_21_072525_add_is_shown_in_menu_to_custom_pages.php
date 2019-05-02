<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsShownInMenuToCustomPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_pages', function (Blueprint $table) {
            $table->boolean('is_shown_in_menu')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_pages', function (Blueprint $table) {
            $table->dropColumn('is_shown_in_menu');
        });
    }
}
