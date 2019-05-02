<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactUsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        settingCategory(['title' => 'Contact Us', 'key' => 'contact_us']);
        setting(['title' => 'Contact Us Recipients', 'category_id' => 'contact_us', 'type' => 'textarea']);
        setting(['title' => 'Latitude', 'category_id' => 'contact_us', 'type' => 'textfield']);
        setting(['title' => 'Longitude', 'category_id' => 'contact_us', 'type' => 'textfield']);
    }
}
