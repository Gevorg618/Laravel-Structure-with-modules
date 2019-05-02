<?php

namespace Modules\Admin\Repositories;


use App\Models\Documents\UserDocumentType;

class UserDocumentTypesRepository
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getUserDocumentTypes()
    {
        return UserDocumentType::pluck('title', 'id');
    }
}