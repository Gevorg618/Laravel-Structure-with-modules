<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCustomEmailState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_email_state', function (Blueprint $table) {
            $table->renameColumn('state','state_id');
            $table->renameColumn('emailid','email_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_email_state', function (Blueprint $table) {
            $table->renameColumn('state_id','state');
            $table->renameColumn('email_id','emailid');
        });
    }
}
