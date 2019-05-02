<?php

namespace Modules\Admin\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class DailyBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date_from' => 'required|string',
            'date_to' => 'required|string',
            'type' => 'nullable|string',
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
