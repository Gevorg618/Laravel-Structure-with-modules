<?php

namespace Modules\Admin\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;

class CustomEmailTemplatesRequest extends FormRequest
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
                'title' => 'required|string|max:255',
                'email_key' => 'required|unique:custom_email,email_key,'.$this->get('update_id'),
                'software_fee' => 'required|in:0,1'
            ];
        } else {
            return [

            ];
        }
    }
    protected function getValidatorInstance()
    {
        if ($this->method() == "POST") {
            $input = $this->all();
            $input['email_key'] = getCode($input['email_key']);
            $this->replace($input);
        }
        return parent::getValidatorInstance();
    }

}
