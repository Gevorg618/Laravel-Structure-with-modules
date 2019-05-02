<?php

namespace Modules\Admin\Http\Requests\Management\WholesaleLenders;

use Illuminate\Foundation\Http\FormRequest;

class ProposedRequest extends FormRequest
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
            'range_start' => 'required',
            'range_end' => 'required',
            'amount' => 'required',
        ];
    }
}
