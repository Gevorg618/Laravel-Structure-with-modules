<?php

namespace Modules\Admin\Contracts\Ticket;

interface CategoryContract
{
    /**
     * @param int $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesByType($type);

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategories();
}