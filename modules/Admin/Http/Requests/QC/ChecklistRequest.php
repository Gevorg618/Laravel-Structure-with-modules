<?php

namespace Modules\Admin\Http\Requests\QC;

use Illuminate\Foundation\Http\FormRequest;

class ChecklistRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'qc_correction' => 'nullable|string',
            'client_correction' => 'nullable|string',
            'category' => 'nullable|integer',
            'realview_rule_id' => 'nullable|integer',
            'parent_question' => 'nullable|integer',
            'is_active' => 'required|bool',
            'clients' => 'nullable|array',
            'is_required' => 'required|bool',
            'lenders' => 'nullable|array',
            'loan_type' => 'nullable|array',
            'appraisal_type' => 'nullable|array',
            'loan_reason' => 'nullable|array',
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
