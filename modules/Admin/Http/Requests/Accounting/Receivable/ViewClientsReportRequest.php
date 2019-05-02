<?php

namespace Modules\Admin\Http\Requests\Accounting\Receivable;

use Illuminate\Foundation\Http\FormRequest;

class ViewClientsReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clients' => 'required',
            'ignore' => 'required|array',
            'printlargelabels' => 'nullable',
            'printinvoices' => 'nullable',
            'filter' => 'nullable',
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
