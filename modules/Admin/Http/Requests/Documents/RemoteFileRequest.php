<?php

namespace Modules\Admin\Http\Requests\Documents;

use Illuminate\Foundation\Http\FormRequest;

class RemoteFileRequest extends FormRequest
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
        if ($this->method() == 'POST') {
            return [
                'file' => 'required|file',
                'bucket' => 'required|string',
                'is_public' => 'required|in:1,0',
            ];
        } else {
            return [

            ];
        }
    }
}
