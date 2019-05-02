<?php

namespace Modules\Admin\Repositories;


use App\Models\Management\UserTemplate;

class UserTemplatesRepository
{
    /**
     * @param $userId
     * @param bool $approved
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getTemplates($userId, $approved = false)
    {
        $templates = UserTemplate::where('user_id', $userId);
        if ($approved) {
            $templates = $templates->where('is_approved', 1);
        }
        return $templates->orderBy('title')->get();
    }
}
