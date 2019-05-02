<?php

namespace Modules\Admin\Repositories;


use App\Models\Language;

class LanguageRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getLanguages()
    {
        return Language::orderBy('name')->get();
    }
}