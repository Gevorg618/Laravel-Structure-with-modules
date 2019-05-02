<?php

namespace Modules\Admin\Http\Requests\Accounting\BatchCheck;

use Illuminate\Foundation\Http\FormRequest;

class BatchCheckShowOrders extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date_from' => 'required',
            'date_to' => 'required',
            'type' => 'required',
            'clients' => 'nullable|array',
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

    /** {@inheritdoc} */
    public function messages()
    {
        return [
            'date_from.required'       => 'The Date From field is required',
            'date_to.required'       => 'The Date To field is required',
            'type.required'       => 'The Type field is required',
        ];
    }
}
