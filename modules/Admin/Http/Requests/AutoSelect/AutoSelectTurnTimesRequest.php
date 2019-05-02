<?php

namespace Modules\Admin\Http\Requests\AutoSelect;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AutoSelectTurnTimesRequest extends FormRequest
{

    /**
     * Create code in constructor so we can set validation rule
     * AutoSelectTurnTimesRequest constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $timestamp = strtotime("now");
        
        if ($request->method() === "PUT") {
            
            $data = [
                    'last_edited_date' => $timestamp,
                    'last_edited_by' => admin()->id
                ];        
        } else {

            $data = [
                    'created_by' => admin()->id,
                    'created_date' => $timestamp,
                    'last_edited_date' => $timestamp,
                    'last_edited_by' => admin()->id
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
        if ($this->route('slug') == 'default') {
            return [];
        }

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
            'client_id'  => 'required|unique:autoselect_client_turn_times,client_id,NULL,id,deleted_at,NULL'
        ];
    }
    
    /**
     * Set rules for updating existing docuvault appraisal
     * @return array
     */
    private function update()
    {
        return [
            'client_id'  => 'required|unique:autoselect_client_turn_times,client_id,'.$this->route('slug').',client_id'
        ];
    }

    /** {@inheritdoc} */
    public function messages()
    {
        return [
            'client_id.required'       => 'The Client field is required',
            'client_id.unique'       => 'Sorry, That client already has a specific turn time record. Please edit it rather than trying to create a new one.',
        ];
    }

}
