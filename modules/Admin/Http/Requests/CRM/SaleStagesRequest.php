<?php

namespace Modules\Admin\Http\Requests\CRM;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SaleStagesRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'visible' => 'required|in:1,0',
        ];

        if ($this->method() == "POST") {
            $rules['skey'] = 'unique:user_group_lead_sales_stage,skey';
            return $rules;
        } elseif($this->method() == "PUT") {
            return $rules;
        }
        else {
            return [

            ];
        }
    }

    /**
     * Mutator for creating the SaleStage
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        if ($this->method() == "POST") {
            $input = $this->all();
            $input['skey'] = getCode($input['title']);

            $this->replace($input);
        }
        return parent::getValidatorInstance();
    }
}
