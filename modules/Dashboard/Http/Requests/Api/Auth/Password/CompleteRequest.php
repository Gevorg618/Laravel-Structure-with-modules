<?php
namespace Dashboard\Http\Requests\Api\Auth\Password;

use App\Models\Users\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Dashboard\Http\Requests\Api\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
    
class CompleteRequest extends BaseRequest
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
          'token' => 'required',
          'password' => 'required|min:8',
          'passwordConfirmation' => 'required|same:password'
        ];
    }

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        if ($this->method() != 'POST') {
            return $validator;
        }

        $validator->after(function () use ($validator) {
            $user = User::byEmail($this->get('email'))->first();
            $exists = Password::getRepository()->exists($user, $this->get('token'));

            if (!$exists) {
                $validator->errors()->add('token', 'Sorry, We were not able to locate or verify the password reset request. Please submit a new one.');
            }
        });

        return $validator;
    }

    public function messages()
    {
      return [
        'email.exists' => 'Sorry, the email provided is invalid or does not exist.'
      ];
    }
}
