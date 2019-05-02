<?php

namespace Modules\Admin\Http\Requests\Accounting\Receivable;

use Illuminate\Foundation\Http\FormRequest;

class ViewClientsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids' => 'required',
            'filter' => 'nullable',
            'credits' => 'nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
