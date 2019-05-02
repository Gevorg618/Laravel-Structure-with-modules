<?php
namespace Modules\Admin\Http\Requests\Integrations\Google;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OAuthCallbackRequest extends FormRequest
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
            'code' => 'required|string',
        ];
    }
}
