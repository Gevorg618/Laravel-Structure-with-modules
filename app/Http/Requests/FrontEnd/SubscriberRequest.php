<?php

namespace App\Http\Requests\FrontEnd;

use App\Http\Requests\Request;

class SubscriberRequest extends Request
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
            'subscribe_email' => 'required|email',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'subscribe_email.email' => 'The email must be a valid email address.',
        ];
    }
}
