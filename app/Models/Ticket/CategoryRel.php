<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class CategoryRel extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_category_rel';
    protected $fillable = [
        'ticket_id',
        'category_id',
    ];

    public $timestamps = false;

    /**
     * @param $query
     * @param bool|array $categories
     * @return mixed
     */
    public function scopeOfCategories($query, $categories = false)
    {
        if ($categories) {
            return $query->whereIn('category_id', $categories);
        }
    }
}
