<?php

namespace Modules\Admin\Http\Requests\Accounting\BatchCheck;

use Illuminate\Foundation\Http\FormRequest;

class ApplyBatchCheckRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'required',
            'check_number' => 'required',
            'from' => 'required',
            'type' => 'required',
            'ids' => 'required|array'
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
