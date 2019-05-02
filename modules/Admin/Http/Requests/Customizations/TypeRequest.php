<?php

namespace Modules\Admin\Http\Requests\Customizations;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TypeRequest extends FormRequest
{
    
    /**
     * Create code in constructor so we can set validation rule
     * TypeRequest constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $form = $request->get('form');
        $shortDescrip = $request->get('short_descrip');
        $request->request->add(['code' => strtoupper(preg_replace('/[^a-z0-9]/i', '', $form.$shortDescrip))]);
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
            'baseprice_con' => 'required',
            'baseprice_fha' => 'required',
            'short_descrip' => 'required',
            'position' => 'required'
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
            'baseprice_con' => 'required',
            'baseprice_fha' => 'required',
            'short_descrip' => 'required',
            'position' => 'required'
        ];
    }

    /** {@inheritdoc} */
    public function messages()
    {
        return [
            'descrip.required'       => 'The Title field is required',
            'baseprice_con.required' => 'The Base Price Conventional  field is required',
            'baseprice_fha.required' => 'The Base Price FHA field is required',
            'short_descrip.required' => 'The Short Description field is required',
            'position.required' => 'The Position field is required',
        ];
    }

}
