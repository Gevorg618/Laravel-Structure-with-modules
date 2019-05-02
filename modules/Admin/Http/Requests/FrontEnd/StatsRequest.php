<?php
namespace Modules\Admin\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StatsRequest extends FormRequest
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
        if($this->method() === 'PUT' || $this->method() === 'POST') {
            return [
                'title' => 'required',
                'icon' => 'required',
                'stat_number' => 'required',
            ];
        }
        return [

        ];
    }
}
