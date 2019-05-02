<?php

namespace Modules\Admin\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GeneralReportsRequest
 * @package Modules\Admin\Http\Requests
 */
class GeneralReportsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'report' => 'required|string',
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
