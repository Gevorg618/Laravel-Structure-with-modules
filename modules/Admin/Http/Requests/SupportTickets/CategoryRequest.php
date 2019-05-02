<?php
namespace Modules\Admin\Http\Requests\SupportTickets;

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
        return [
        	'name'            => 'required|string',
        	'bgcolor'         => 'string',
        	'textcolor'       => 'string',
        	'user_type_id'    => 'integer',
        	'order_status_id' => 'integer',
        ];
    }

    /**
     * Get the id of user_type relationship
     *
     * @return integer
     */
    public function userTypeInput()
    {
    	return $this->input('user_type_id');
    }

    /**
     * Get the id of order_status relationship
     *
     * @return integer
     */
    public function orderStatusInput()
    {
    	return $this->input('order_status_id');
    }
}
