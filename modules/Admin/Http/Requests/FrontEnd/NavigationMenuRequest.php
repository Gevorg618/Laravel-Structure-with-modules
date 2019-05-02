<?php
namespace Modules\Admin\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class NavigationMenuRequest extends FormRequest
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
        if($this->method() === 'POST' || $this->method() === 'PUT') {
            return [
                'title' => 'required|min:3',
                'url' => 'required',
                'slug' => 'required',
            ];
        }

        return [

        ];

    }
}
