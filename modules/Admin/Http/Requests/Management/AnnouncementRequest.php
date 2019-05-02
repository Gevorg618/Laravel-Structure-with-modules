<?php

namespace Modules\Admin\Http\Requests\Management;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
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
                'is_active' => 'required|in:1,0',
                'from_date' => 'required',
                'expired_date' => 'required',
                'redirect_title' => 'required|string|max:255',
                'redirect_link' => 'required|url',
                'content' => 'required|min:11',
                'user_types.*' => 'required|in:'.userTypeIds(),
            ];
        } else {
            return [

            ];
        }
    }
    protected function getValidatorInstance()
    {
        if ($this->method() == "POST") {
            $created_by = admin()->id;
            $created_date = Carbon::now()->timestamp;
            $input = $this->all();
            $input['from_date'] = strtotime($input['from_date']);
            $input['expired_date'] = strtotime($input['expired_date']);
            $input['created_by'] = $created_by;
            $input['created_date'] = $created_date;
            $this->replace($input);
        }
        if($this->method() == "PUT"){
            $input = $this->all();
            $input['from_date'] = strtotime($input['from_date']);
            $input['expired_date'] = strtotime($input['expired_date']);
            $this->replace($input);
        }
        return parent::getValidatorInstance();
    }
}
