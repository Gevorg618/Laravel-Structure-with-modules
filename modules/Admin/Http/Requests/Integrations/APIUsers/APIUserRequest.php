<?php

namespace Modules\Admin\Http\Requests\Integrations\APIUsers;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class APIUserRequest extends FormRequest
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
            'title' => 'required',
            'company' => 'required',
        ];
    }

    /**
     * Sort inputs
     *
     * @param $inputs
     * @return array
     */
    public function sortInputs()
    {
        $data = [];

        $data['info'] = [
            'title' => $this->title,
            'company' => $this->company,
            'day_limit' => $this->day_limit,
            'month_limit' => $this->month_limit,
            'in_production' => $this->in_production,
            'is_active' => $this->is_active,
            'is_visible_all' => $this->is_visible_all,
            'permissions' => serialize($this->permissions),
            'contact_email_as_additional' => $this->contact_email_as_additional,
        ];
        $data['groups'] = $this->groups;
        $data['emailsetting'] = $this->emailsetting;

        return $data;
    }
}
