<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMercuryCustomProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercury_custom_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name')->default('');
            $table->integer('appr_type')->default('0');
            $table->integer('loan_reason')->default('0');
            $table->integer('loan_type')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mercury_custom_products');
    }
}
