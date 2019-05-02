<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLoanType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_email_loan_type', function (Blueprint $table) {
            $table->renameColumn('emailid','custom_email_id');
            $table->renameColumn('loanid','loan_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_email_loan_type', function (Blueprint $table) {
            $table->renameColumn('custom_email_id','emailid');
            $table->renameColumn('loan_type_id','loanid');
        });
    }
}
