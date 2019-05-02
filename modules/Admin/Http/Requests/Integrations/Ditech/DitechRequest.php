<?php
namespace Modules\Admin\Http\Requests\Integrations\Ditech;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
    
class DitechRequest extends FormRequest
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
            'daterange'        => 'required',
        ];
        return $rules;
    }

    /** {@inheritdoc} */
    public function attributes()
    {
        return [
            'daterange'       => 'The Date Range was required',
        ];
    }
    
}
