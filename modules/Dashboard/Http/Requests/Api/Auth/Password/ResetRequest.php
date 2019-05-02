<?php
namespace Dashboard\Http\Requests\Api\Auth\Password;

use Illuminate\Validation\Rule;
use Dashboard\Http\Requests\Api\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
    
class ResetRequest extends BaseRequest
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
          'email' => [
            'required',
            'email', 
            Rule::exists('user')->where(function ($query) {
              $query->where('active', 'Y');
            })
          ],
          'captcha' => 'captcha'
        ];
    }

    public function messages()
    {
      return [
        'email.exists' => 'Sorry, the email provided is invalid or does not exist.'
      ];
    }
}
