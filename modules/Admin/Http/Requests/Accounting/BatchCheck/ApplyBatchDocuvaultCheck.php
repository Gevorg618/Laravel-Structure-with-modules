<?php

namespace Modules\Admin\Http\Requests\Accounting\BatchCheck;

use Illuminate\Foundation\Http\FormRequest;

class ApplyBatchDocuvaultCheck extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids' => 'required',
            'ordertype' => 'required',
            'check_number' => 'required',
            'from' => 'required',
            'date' => 'required',
            'type' => 'required',
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
