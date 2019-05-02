<?php
namespace Modules\Admin\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class HeaderCarouselRequest extends FormRequest
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
        if($this->method() === 'PUT') {
            return $this->update();
        } else if($this->method() === 'POST') {
            return $this->store();
        }
        return [

        ];

    }

    /**
     * @return array
     */
    private function store()
    {
        return [
            'desktop_image' => 'required|mimes:jpeg,jpg,bmp,png|dimensions:min_width=1920,min_height=1080',
            'mobile_image' => 'required|mimes:jpeg,jpg,bmp,png|dimensions:min_width=960,min_height=560',
            'title' => 'required',
            'description' => 'required',
            'position' => 'required',
            'buttons_title' => 'required',
            'buttons_link' => 'required',
        ];
    }

    /**
     * @return array
     */
    private function update()
    {
        return [
            'desktop_image' => 'mimes:jpeg,jpg,bmp,png|dimensions:min_width=1920,min_height=1080',
            'mobile_image' => 'mimes:jpeg,jpg,bmp,png|dimensions:min_width=960,min_height=560',
            'title' => 'required',
            'description' => 'required',
            'position' => 'required',
            'buttons_title' => 'required',
            'buttons_link' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'desktop_image.dimensions' => 'The desktop image has invalid image dimensions. Min width must be 1920px, Min height must be 1080px',
            'mobile_image.dimensions' => 'The mobile image has invalid image dimensions. Min width must be 960px, Min height must be 560px'
        ];
    }
}
