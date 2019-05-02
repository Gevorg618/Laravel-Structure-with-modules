<?php

namespace Modules\Admin\Http\Requests\Customizations;

use Illuminate\Foundation\Http\FormRequest;

class PropertyTypesRequest extends FormRequest
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
        if($this->route('id'))
        {
            return $this->update();
        }
        
        return $this->store();
    }
    
    /**
     * Set rules for storing new prop types
     * @return array
     */
    private function store()
    {
        return [
            'descrip'   =>    'required|unique:prop_types',
        ];
    }
    
    /**
     * Set rules for updating existing prop types
     * @return array
     */
    private function update()
    {
        return [
            'descrip'   =>    'required|unique:prop_types,descrip,'.$this->route('id')
        ];
    }
    
    
}
