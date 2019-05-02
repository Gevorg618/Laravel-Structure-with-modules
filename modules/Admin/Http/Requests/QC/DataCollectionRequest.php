<?php

namespace Modules\Admin\Http\Requests\QC;

use Illuminate\Foundation\Http\FormRequest;

class DataCollectionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'pos' => 'required|integer',
            'format' => 'required|string',
            'is_active' => 'required|bool',
            'is_required' => 'required|bool',
            'field_type' => 'required|string',
            'field_extra' => 'nullable|string',
            'default_value' => 'nullable|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
