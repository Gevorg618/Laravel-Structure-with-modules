<?php
namespace Modules\Admin\Http\Requests\Integrations\Mercury;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
    
class EquityEdgeRequest extends FormRequest
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
        if($this->method() === "PUT") {
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
            'product_name'  => 'required',
            'appr_type' => 'required'
        ];
    }
    
    /**
     * Set rules for updating existing docuvault appraisal
     * @return array
     */
    private function update()
    {
       return [
            'product_name.required'       => 'The Product Name field is required',
            'appr_type.required' => 'The Apprasial Type field is required',
        ];
    }

    /** {@inheritdoc} */
    public function messages()
    {
        return [
            'product_name.required'       => 'The Product Name field is required',
            'appr_type.required'       => 'The Apprasial Type field is required',
        ];
    }
    
}
