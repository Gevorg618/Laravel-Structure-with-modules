<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemoteFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remote_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('bucket');
            $table->string('path');
            $table->boolean('is_public')->default(false);
            $table->integer('created_by');
            $table->timestamp('created_at')->nullable();
            $table->integer('updated_by');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remote_files');
    }
}
