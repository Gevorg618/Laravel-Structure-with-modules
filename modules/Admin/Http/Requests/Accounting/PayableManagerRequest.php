<?php

namespace Modules\Admin\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PayableManagerRequest extends FormRequest
{
    
    /**
     * Create code in constructor so we can set validation rule
     * PayableManagerRequest constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $request->request->get('formData');
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
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->request->get('formData');
        return $rules = [
            'daterange'  => 'required',
        ];
    }
    
}
