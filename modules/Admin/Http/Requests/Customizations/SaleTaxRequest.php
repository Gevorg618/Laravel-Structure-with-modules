<?php

namespace Modules\Admin\Http\Requests\Customizations;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SaleTaxRequest extends FormRequest
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
            'state' => 'required|min:2',
            'county.*' => 'numeric',
        ];
    }

}
