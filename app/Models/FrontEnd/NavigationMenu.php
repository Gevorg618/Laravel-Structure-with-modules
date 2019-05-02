<?php

namespace App\Models\FrontEnd;

use Illuminate\Database\Eloquent\Model;

class NavigationMenu extends Model
{
    protected $table = 'navigation_menu';

    protected $casts = [
        'childes' => 'array'
    ];

    protected $fillable = [
        'title',
        'url',
        'slug',
        'is_active',
        'is_drop_down',
        'is_quick_link',
        'childes'
    ];

    public function scopeActive($query)
    {
      $query->where('is_active', 1);
    }
}
