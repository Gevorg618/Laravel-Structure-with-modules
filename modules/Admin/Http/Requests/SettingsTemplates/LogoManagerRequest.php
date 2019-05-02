<?php

namespace Modules\Admin\Http\Requests\SettingsTemplates;

use Illuminate\Foundation\Http\FormRequest;

class LogoManagerRequest extends FormRequest
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
            'title' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_date' => 'required',
            'end_date' => 'required',
        ];
    }
}
