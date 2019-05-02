<?php

namespace App\Models\FrontEnd;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $table = 'stats';

    protected $fillable = ['title', 'icon', 'stat_number'];
}
