<?php

namespace Modules\Admin\Http\Requests\Management\WholesaleLenders;

use Illuminate\Foundation\Http\FormRequest;

class UWContactInfoRequest extends FormRequest
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
        if($this->action == 'add') {
            return [
                'uw_fullname' => 'required',
                'email' => 'required|email|unique:user_group_lender_uw_contact_info',
                'uw_phone' => 'required',
            ];

        }elseif ($this->action == 'update') {
            return [
                'uw_fullname' => 'required',
                'email' => 'required|email',
                'uw_phone' => 'required',
            ];
        }
    }
}
