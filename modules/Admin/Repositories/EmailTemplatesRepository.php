<?php

namespace Modules\Admin\Repositories;


use App\Models\Management\EmailTemplate;

class EmailTemplatesRepository
{
    /**
     * @param $category
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getEmailTemplates($category = null)
    {
        $templates = EmailTemplate::orderBy('category')
            ->orderBy('title');
        if ($category) {
            $templates = $templates->where('category', $category);
        }
        return $templates->get();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getOne($id)
    {
        return EmailTemplate::find($id);
    }
}