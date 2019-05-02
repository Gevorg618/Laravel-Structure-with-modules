<?php

namespace Modules\Admin\Http\Requests\Management\WholesaleLenders;

use Illuminate\Foundation\Http\FormRequest;

class ExcludedUsersRequest extends FormRequest
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
            'lender' => 'required',
            'lender_file' => 'required|mimes:csv,txt'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'lender.required' => 'Sorry, You must select a lender.',
            'lender_file.required' => 'Sorry, You must upload a file.',
            'lender_file.mimes' => 'Sorry, You must upload a csv file.',
        ];
    }
}
