<?php

namespace Modules\Admin\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LocatePaymentRequest
 * @package Modules\Admin\Http\Requests
 */
class LocatePaymentRequest extends FormRequest
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
        $term = $this->has('term');
 
        if ($term) {
            return $this->search();
        }
        return [];
    }
    
    /**
     * Set rules for storing new tiger AMC
     * @return array
     */
    private function search()
    {
        return [
            'term'  => 'required|string|min:3',
        ];
    }

    /** {@inheritdoc} */
    public function messages()
    {
        return [
            'term.required'       => 'The Title was required',
            'term.min'       => 'The Title must be at least 3 characters',
        ];
    }
    
}
