<?php

namespace Modules\Admin\Http\Requests\UW;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
                'title' => 'required|string',
                'is_active' => 'required|in:1,0'
            ];
        } else {
            return [

            ];
        }
	}

}
