<?php

namespace Modules\Admin\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class ModerationRequest extends FormRequest
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
        if ($this->method() == 'POST' || $this->method() == 'PUT') {
            return [
                'title' => 'required|string|max:125',
                'is_active' => 'required|in:0,1',
                'public_comment' => 'required|in:0,1',
                'reply' => 'required|in:0,1',
                'reply_all' => 'required|in:0,1',
            ];
        } else {
            return [

            ];
        }
    }
}