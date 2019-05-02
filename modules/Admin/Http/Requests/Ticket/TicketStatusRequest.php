<?php

namespace Modules\Admin\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TicketStatusRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
        if($this->route('id'))
        {
            return $this->update();
        }

        return $this->store();
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

    /**
     * Set rules for storing new docuvault appraisal
     * @return array
     */
    private function store()
    {
        return [
            'name'          =>  'required|unique:tickets_status',
            'bgcolor'       =>  'required',
            'textcolor'     =>  'required'
        ];
    }

    /**
     * Set rules for updating existing docuvault appraisal
     * @return array
     */
    private function update()
    {
        return [
            'name'          =>  'required|unique:tickets_status,name,'.$this->route('id'),
            'bgcolor'       =>  'required',
            'textcolor'     =>  'required'
        ];
    }
}
