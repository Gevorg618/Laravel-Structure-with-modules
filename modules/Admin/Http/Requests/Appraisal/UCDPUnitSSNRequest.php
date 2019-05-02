<?php

namespace Modules\Admin\Http\Requests\Appraisal;

use Illuminate\Foundation\Http\FormRequest;

class UCDPUnitSSNRequest extends FormRequest
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
            'ssn_id' => 'numeric',
            'title' => 'string',
		];
	}
    protected function getValidatorInstance()
    {
        $input = $this->all();
        $input[$this->get('name')] = $this->get('value');
        $this->replace($input);
        return parent::getValidatorInstance();
    }

}
