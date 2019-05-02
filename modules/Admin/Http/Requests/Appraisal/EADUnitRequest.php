<?php

namespace Modules\Admin\Http\Requests\Appraisal;

use Illuminate\Foundation\Http\FormRequest;

class EADUnitRequest extends FormRequest
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
                'unit_id' => 'required|alpha_num|max:255|unique:ead_unit,unit_id,'.$this->get('update_id'),
                'title' => 'required|string|max:255',
                'fha_lenderid' => 'required|numeric',
                'is_active' => 'required|in:0,1',
                'clients' => 'required_without:lenders',
                'lenders' => 'required_without:clients',
            ];
        } else {
            return [

            ];
        }
	}

}
