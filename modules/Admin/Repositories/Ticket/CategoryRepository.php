<?php

namespace Modules\Admin\Repositories\Ticket;

use Modules\Admin\Contracts\Ticket\CategoryContract;
use App\Models\Ticket\Category;

class CategoryRepository implements CategoryContract
{
    private $category;

    /**
     * CategoryRepository constructor.
     *
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @param int $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesByType($type)
    {
        return $this->category->leftJoin(
            'tickets_category_visible', 'tickets_category_visible.category_id', '=', 'tickets_category.id'
        )->where('tickets_category_visible.user_type', '=', $type)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategories()
    {
        return $this->category->orderBy('name', 'asc');
    }
}