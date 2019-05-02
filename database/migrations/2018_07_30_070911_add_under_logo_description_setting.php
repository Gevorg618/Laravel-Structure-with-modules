<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnderLogoDescriptionSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        settingCategory(['title' => 'Footer Content', 'key' => 'footer_content']);
        setting(['title' => 'Under Logo Description', 'category_id' => 'footer_content', 'type' => 'textarea']);

    }
}
