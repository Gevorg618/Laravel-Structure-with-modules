<?php

namespace Modules\Admin\Repositories;


use App\Models\PhoneType;

class PhoneTypeRepository
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getPhoneTypes()
    {
        return PhoneType::orderBy('id')->pluck('name', 'id');
    }
}