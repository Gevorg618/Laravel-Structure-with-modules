<?php

namespace Modules\Admin\Repositories;


use App\Models\Documents\UserDocument;

class UserDocumentsRepository
{
    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAdditionalDocuments($userId)
    {
        return UserDocument::where('userid', $userId)->get();
    }
}