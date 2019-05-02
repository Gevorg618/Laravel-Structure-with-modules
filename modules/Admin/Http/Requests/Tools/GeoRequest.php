<?php
namespace Modules\Admin\Http\Requests\Tools;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
    
class GeoRequest extends FormRequest
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
        if($this->method() === "PUT")
        {
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
            'state'  => 'required|string|unique:states,state',
            'abbr'   => 'required|string|min:2|max:2|unique:states,abbr',
            'state_2' => 'exists:states,id',
            'timezone_id' => 'required|exists:timezones,id',
            'region_id' => 'required|exists:regions,id'
        ];
    }
    
    /**
     * Set rules for updating existing docuvault appraisal
     * @return array
     */
    private function update()
    {
        return [
            'state'  => 'required|string|unique:states,state,'.$this->route('id'),
            'abbr'   => 'required|string|min:2|max:2|unique:states,abbr,'.$this->route('id'),
            'state_2' => 'exists:states,id',
            'timezone_id' => 'required|exists:timezones,id',
            'region_id' => 'required|exists:regions,id'
        ];
    }

    /** {@inheritdoc} */
    public function attributes()
    {
        return [
            'state.required'       => 'The State Name is required',
            'state.unique'       => 'The State Name is unique',
        ];
    }
    
}
