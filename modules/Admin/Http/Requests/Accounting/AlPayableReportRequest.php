<?php

namespace Modules\Admin\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class AlPayableReportRequest extends FormRequest
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
        $dateFrom = $this->has('date_from');
        $dateTo = $this->has('date_to');
        if ($dateFrom && $dateTo) {

            return [
                'date_from' => 'nullable|date_format:Y-m-d',
                'date_to' => 'required|date_format:Y-m-d',
                'states' => 'nullable|array',
                'clients' => 'nullable|array'
            ];    
        }
        return [];
    }

    /**
     * messages for validation
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'date_from.required'       => 'The Date From field is required',
            'date_to.required'       => 'The Date To field is required',
        ];
    }
}
