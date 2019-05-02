<?php
namespace Modules\Admin\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LatestNewsRequest extends FormRequest
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
            'image' => 'required|mimes:jpeg,jpg,bmp,png|dimensions:min_width=612,min_height=408',
            'title' => 'required',
            'short_description' => 'required',
            'content' => 'required',
        ];
    }

    /**
     * @return array
     */
    private function update()
    {
        return [
            'image' => 'mimes:jpeg,jpg,bmp,png|dimensions:min_width=612,min_height=408',
            'title' => 'required',
            'short_description' => 'required',
            'content' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'image.dimensions' => 'The image has invalid image dimensions. Min width must be 612px, Min height must be 408px',
        ];
    }
}
