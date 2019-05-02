<?php

namespace Modules\Admin\Http\Requests\Integrations\Google;


use Illuminate\Foundation\Http\FormRequest;

class SearchEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'term' => 'nullable|string'
        ];
    }
}