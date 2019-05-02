<?php

namespace Modules\Admin\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UserTemplatesRequest extends FormRequest
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
				'is_approved' => 'required|in:1,0',
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
			$created_by = admin()->id;
			$this->merge(['created_at' => $created_at]);
			$this->merge(['user_id' => $created_by]);
		}
		return parent::getValidatorInstance();
	}
}
