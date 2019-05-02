<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCustomEmailApprTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_email_appr_type', function (Blueprint $table) {
            $table->renameColumn('emailid','custom_email_id');
            $table->renameColumn('typeid','appr_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_email_appr_type', function (Blueprint $table) {
            $table->renameColumn('custom_email_id','emailid');
            $table->renameColumn('appr_type_id','typeid');
        });
    }
}
