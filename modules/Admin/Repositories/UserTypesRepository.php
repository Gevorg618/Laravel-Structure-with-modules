<?php

namespace Modules\Admin\Repositories;


use App\Models\Management\UserType;

class UserTypesRepository
{
    public function getUserTypes()
    {
        return UserType::pluck('descrip', 'id');
    }

}