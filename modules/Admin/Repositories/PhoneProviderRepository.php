<?php

namespace Modules\Admin\Repositories;


use App\Models\PhoneProvider;

class PhoneProviderRepository
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getPhoneProviders()
    {
        return PhoneProvider::orderBy('id')->pluck('name', 'id');
    }
}