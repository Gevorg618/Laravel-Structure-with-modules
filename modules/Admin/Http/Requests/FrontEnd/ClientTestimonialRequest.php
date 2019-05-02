<?php

namespace Modules\Admin\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ClientTestimonialRequest extends FormRequest
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
        if ($this->method() === 'PUT' || $this->method() === 'POST') {
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
            'name' => 'required|max:255',
            'title' => 'required|max:255',
            'content' => 'required',
        ];
    }
}
