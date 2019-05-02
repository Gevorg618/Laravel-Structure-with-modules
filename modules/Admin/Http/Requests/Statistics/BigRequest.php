<?php
namespace Modules\Admin\Http\Requests\Statistics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
    
class BigRequest extends FormRequest
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
        $rules = [
            'date'        => 'required|date_format:Y-m-d',
        ];
        return $rules;
    }

    /** {@inheritdoc} */
    public function attributes()
    {
        return [
            'date.required'       => 'Date is requeired',
            'date.date_format'       => 'Date format wrong!',
        ];
    }
    
}
