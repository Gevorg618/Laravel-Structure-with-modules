<?php
namespace Modules\Admin\Http\Requests\Tiger;

use App\Models\Customizations\Appraisal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AmcsRequest extends FormRequest
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
     * Set rules for storing new tiger AMC
     * @return array
     */
    private function store()
    {
        return [
            'title'             =>    'required|unique:tiger.amc',
            'company_name'      =>    'required|unique:tiger.amc',
            'company_phone'     =>    'required',
            'incoming_email'    =>    'required|email',
            'outgoing_email'    =>    'required|email'
        ];
    }
    
    /**
     * Set rules for updating existing Tiger AMC
     * @return array
     */
    private function update()
    {
        return [
            'title'             =>    'required|unique:tiger.amc,title,'.$this->route('id'),
            'company_name'      =>    'required|unique:tiger.amc,company_name,'.$this->route('id'),
            'company_phone'     =>    'required',
            'incoming_email'    =>    'required|email',
            'outgoing_email'    =>    'required|email'
        ];
    }
}
