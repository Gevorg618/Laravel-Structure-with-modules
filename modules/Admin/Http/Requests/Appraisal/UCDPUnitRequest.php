<?php

namespace Modules\Admin\Http\Requests\Appraisal;

use Illuminate\Foundation\Http\FormRequest;

class UCDPUnitRequest extends FormRequest
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
        if ($this->method() == "POST" || $this->method() == "PUT") {
            return [
                'unit_id' => 'required|alpha_num|max:255|unique:ucdp_unit,unit_id,'.$this->get('update_id'),
                'title' => 'required|string|max:255',
                'is_active' => 'required|in:0,1',
                'fnm_active' => 'required|in:0,1',
                'fre_active' => 'required|in:0,1',
                'clients' => 'required_without:lenders',
                'lenders' => 'required_without:clients',
                'fnm_ssn.*.ssn_id' => 'required|numeric',
                'fnm_ssn.*.title' => 'required|string',
                'fre_ssn.*.ssn_id' => 'required|numeric',
                'fre_ssn.*.title' => 'required|string',
            ];
        } else {
            return [

            ];
        }
	}

}
