<?php

namespace Modules\Admin\Http\Requests\AutoSelect;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AutoSelectAppraiserCreateRequest extends FormRequest
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
        $rules = [
            'state' => 'required',
            'fees'  => 'required|file|mimes:csv,txt',
        ];
        return $rules;
    }

    /** {@inheritdoc} */
    public function attributes()
    {
        return [
            'state' => 'State',
            'fees'  => 'CSV file',
        ];
    }

}
