<?php

namespace Modules\Admin\Http\Requests\Management\WholesaleLenders;

use Illuminate\Foundation\Http\FormRequest;

class LenderRequest extends FormRequest
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
        $rules = [
            'lender' => 'required',
            'clients' => 'required',
        ];
        if($this->input('send_final_report')) {
            $rules['final_report_emails'] = 'required';
        }
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'lender.required' => 'Please enter the lender name.',
            'clients.required' => 'Please select at least one client.',
            'final_report_emails.required' => 'Please enter at least one email address.',
        ];
    }
}
