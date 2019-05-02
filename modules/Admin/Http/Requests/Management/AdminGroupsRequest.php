<?php
namespace Modules\Admin\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AdminGroupsRequest extends FormRequest
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
            'title' => 'required|string',
            'color' => 'string',
            'style' => 'string'
        ];
    }
}
