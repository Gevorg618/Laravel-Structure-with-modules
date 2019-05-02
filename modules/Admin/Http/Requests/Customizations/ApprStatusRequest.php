<?php

namespace Modules\Admin\Http\Requests\Customizations;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ApprStatusRequest extends FormRequest
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
        $this->request->remove('_token');
        
        if ($this->method() === "PUT") {
            $this->request->remove('_method');    
            return $this->update();
        }
        
        return $this->store();
    }

    /**
     * Set rules for storing new docuvault appraisal
     * @return array
     */
    private function store()
    {
        return [
            'descrip'  => 'required',
        ];
    }
    
    /**
     * Set rules for updating existing docuvault appraisal
     * @return array
     */
    private function update()
    {
        return [
            'descrip'  => 'required',
        ];
    }

    /** {@inheritdoc} */
    public function messages()
    {
        return [
            'descrip.required'  => 'The Title  is required'
        ];
    }

}
