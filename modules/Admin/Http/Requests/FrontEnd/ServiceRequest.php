<?php
namespace Modules\Admin\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ServiceRequest extends FormRequest
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
        if ($this->method() === 'post' || $this->method() === 'put') {
            return [
                'logo' => 'mimes:jpeg,jpg,bmp,png',
                'title' => 'required|max:255',
                'description' => 'required',
            ];
        }

        return [

        ];
    }
}
