<?php

namespace Modules\Admin\Http\Requests\Management\AdminTeamsManager;

use Illuminate\Foundation\Http\FormRequest;

class AdminTeamsRequest extends FormRequest
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
        if ($this->method() == "POST") {
            return [
                'team_title' => 'required',
                'team_key' => 'required|unique:admin_teams'
            ];
        } elseif($this->method() == "PUT") {
            return [
                'team_title' => 'required',
                'team_key' => 'required'
            ];
        }
    }

    public function messages()
    {
        return [
            'team_title.required' => 'Team Title is a required field.',
            'team_key.required'  => 'Team Key is a required field.',
            'team_key.unique'  => 'Team Key is already taken.'
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
            'team_title' => $this->team_title,
            'team_key' => $this->team_key,
            'supervisor' => $this->supervisor,
            'team_type' => $this->type,
            'is_active' => $this->is_active,
            'team_phone' => $this->phone,
            'team_cap' => $this->cap,
            'descrip' => $this->team_title,
            'status_select_sort' => $this->status_select_sort,
            'qc_uw_pipeline_color' => $this->color,
            'is_in_status_select' => $this->is_in_status_select,
        ];
        $data['relations'] = [
            'members' => isset($this->members_selection) ? $this->members_selection : [],
            'clients' => isset($this->client_selection) ? $this->client_selection : [],
            'states' => isset($this->state_selection) ? $this->state_selection : [],
            'staff' => isset($this->statuse_staff) ? $this->statuse_staff : [],
            'statuses' => isset($this->statuses) ? $this->statuses : [],
            'flags' => isset($this->flags) ? $this->flags : [],
            'loantypes' => isset($this->loan_types) ? $this->loan_types : [],
        ];
        return $data;
    }
}
