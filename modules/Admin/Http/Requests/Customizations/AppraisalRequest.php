<?php
namespace Modules\Admin\Http\Requests\Customizations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AppraisalRequest extends FormRequest
{
    
    /**
     * Create code in constructor so we can set validation rule
     * AppraisalRequest constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $request->request->add(['code' => getCode($request->title)]);
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
        if($this->route('id'))
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
            'title'         =>    'required|unique:docuvault_appr_type',
            'code'          =>    'required|unique:docuvault_appr_type',
            'is_active'     =>    'required|boolean',
        ];
    }
    
    /**
     * Set rules for updating existing docuvault appraisal
     * @return array
     */
    private function update()
    {
        return [
            'title'         =>    'required|unique:docuvault_appr_type,title,'.$this->route('id'),
            'is_active'     =>    'required|boolean',
        ];
    }
}
