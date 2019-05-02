<?php

namespace Modules\Admin\Http\Requests\UW;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
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
                'correction' => 'required|string|max:255',
                'is_active' => 'required|in:0,1',
                'category_id' => 'required'
            ];
        } else {
            return [

            ];
        }
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
