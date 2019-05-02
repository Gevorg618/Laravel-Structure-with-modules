<?php
namespace Modules\Dashboard\Http\Requests;

use App\Models\Management\Survey;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SurveysRequest extends FormRequest
{
    
    private $survey;
    
    /**
     * Validate survey active and required questions
     * SurveysRequest constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->survey =  Survey::where('id', $request->survey_id)
            ->with('questions')
            ->first();
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
        $required = [];
    
        foreach ($this->survey->questions AS $question) {
            if ($question->is_required && $question->is_active) {
                $required['question-' . $question->id] = 'required';
            }
        }
        
        return $required;
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];
    
        foreach ($this->survey->questions AS $question) {
            if ($question->is_required && $question->is_active) {
                $messages['question-' . $question->id . '.required'] = 'The ' . $question->title . ' field is required';
            }
        }
    
        return $messages;
    }
}
