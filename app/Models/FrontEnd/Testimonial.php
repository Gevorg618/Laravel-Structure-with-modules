<?php

namespace App\Models\FrontEnd;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'title',
        'content',
    ];

    public $timestamps = false;
}
