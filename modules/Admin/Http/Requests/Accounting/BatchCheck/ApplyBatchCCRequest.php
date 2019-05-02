<?php

namespace Modules\Admin\Http\Requests\Accounting\BatchCheck;

use Illuminate\Foundation\Http\FormRequest;

class ApplyBatchCCRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'card_number' => 'required',
            'card_exp_month' => 'required',
            'card_exp_year' => 'required',
            'card_cvv' => 'required',
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
