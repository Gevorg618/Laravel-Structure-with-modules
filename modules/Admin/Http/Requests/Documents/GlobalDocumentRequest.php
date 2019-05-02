<?php
namespace Modules\Admin\Http\Requests\Documents;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
    
class GlobalDocumentRequest extends FormRequest
{
    
    /**
     * Create code in constructor so we can set validation rule
     * GlobalDocumentRequest constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        
        if ($request->method() === "POST") {
            
            $timestamp = strtotime("now");

            $data = [
                'created_by' => admin()->id,
                'created_date' => $timestamp
            ];

            $request->request->add($data);
        } 
        
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
            'file_name'  => 'required',
            'file_location'  => 'required',
            'is_active'  => 'required',
            'is_client_visible'  => 'required',
            'is_appr_visible'  => 'required'
        ];
    }
    
    /**
     * Set rules for updating existing docuvault appraisal
     * @return array
     */
    private function update()
    {
       return [
            'file_name.required'       => 'The File Name field is required',
            'is_active.required'       => 'The Active field is required',
            'is_client_visible.required'       => 'The Client Visible field is required',
            'is_appr_visible.required'       => 'The Appraiser Visible field is required',
        ];
    }

    /** {@inheritdoc} */
    public function messages()
    {
        return [
            'file_name.required'       => 'The File Name field is required',
            'file_location.required'       => 'The File location field is required',
            'is_active.required'       => 'The Active field is required',
            'is_client_visible.required'       => 'The Client Visible field is required',
            'is_appr_visible.required'       => 'The Appraiser Visible field is required',
        ];
    }
    
}
