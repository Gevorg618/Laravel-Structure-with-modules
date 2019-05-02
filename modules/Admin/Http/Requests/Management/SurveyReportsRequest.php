<?php
    
namespace Modules\Admin\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;

class SurveyReportsRequest extends FormRequest
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
        return [
            'survey_id'     =>  'required',
            'date_start'    =>  'required',
            'date_end'      =>  'required'
        ];
    }
}
