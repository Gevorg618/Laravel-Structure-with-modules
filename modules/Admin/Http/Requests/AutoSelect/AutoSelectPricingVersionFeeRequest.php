<?php

namespace Modules\Admin\Http\Requests\AutoSelect;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AutoSelectPricingVersionFeeRequest extends FormRequest
{

    /**
     * Create code in constructor so we can set validation rule
     * AutoSelectPricingVersionFeeRequest constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $timestamp = strtotime("now");
        
        if ($request->method() === "PUT") {
            
            $data = [
                    'last_updated_date' => $timestamp,
                    'last_updated_by' => admin()->id
                ];        
        } else {

            $data = [
                    'created_by' => admin()->id,
                    'created_date' => $timestamp,
                    'last_updated_date' => $timestamp,
                    'last_updated_by' => admin()->id
                ];
        }

        $request->request->add($data);
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
            'group_id'  => 'required|unique:autoselect_pricing_version_fees,pricing_version_id,NULL,id,deleted_at,NULL'
        ];
    }
    
    /**
     * Set rules for updating existing docuvault appraisal
     * @return array
     */
    private function update()
    {
       return [
            // 'group_id'  => 'required|unique:autoselect_pricing_version_fees,pricing_version_id,'.$this->route('id').',pricing_version_id'
        ];
    }

    /** {@inheritdoc} */
    public function messages()
    {
        return [
            'group_id.required'       => 'The Version field is required',
            'group_id.unique'       => 'Sorry, That group already has a specific group. Please edit it rather than trying to create a new one.',
        ];
    }

}
