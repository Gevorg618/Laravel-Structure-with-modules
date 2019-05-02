<?php
namespace Dashboard\Http\Requests\Api\Auth;

use Dashboard\Http\Requests\Api\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
    
class LoginRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !user();
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'email' => 'required|email',
          'password' => 'required',
          'captcha' => 'required|captcha'
        ];
    }
}
