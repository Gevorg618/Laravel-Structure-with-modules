<?php

namespace Modules\Admin\Http\Requests\Management;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class EmailTemplatesRequest extends FormRequest
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
                'category' => 'required|string',
                'content' => 'required|min:11'
            ];
        }else{
            return [

            ];
        }
	}

    protected function getValidatorInstance()
    {
        if ($this->method() == "POST") {
            $created_at = Carbon::now()->timestamp;
            $this->merge(['created_at' => $created_at]);
        }
        return parent::getValidatorInstance();
    }

}
