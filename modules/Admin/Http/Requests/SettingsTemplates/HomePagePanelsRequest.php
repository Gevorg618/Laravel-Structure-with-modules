<?php
namespace Modules\Admin\Http\Requests\SettingsTemplates;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class HomePagePanelsRequest extends FormRequest
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
            'link' => 'required',
            'slogan' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png'
        ];
    }
}
