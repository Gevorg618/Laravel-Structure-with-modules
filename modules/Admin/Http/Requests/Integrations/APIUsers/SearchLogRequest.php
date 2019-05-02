<?php

namespace Modules\Admin\Http\Requests\Integrations\APIUsers;

use Illuminate\Foundation\Http\FormRequest;

class SearchLogRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'per_page' => 'numeric|min:2'
        ];
    }
}
