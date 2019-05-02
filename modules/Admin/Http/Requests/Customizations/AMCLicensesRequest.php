<?php
namespace Modules\Admin\Http\Requests\Customizations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AMCLicensesRequest extends FormRequest
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
        if ($this->method() == "POST" || $this->method() == "PUT") {
            return [
                'state' => 'required|size:2|in:'.getStateKeys().'|unique:amc_registration,state,'.$this->get('update_id'),
                'reg_number' => 'required|string|regex:/a-z0-9\-/i',
                'expires' => 'required|date_format:"Y-m-d"',
                'sec_expires' => 'required|date_format:"Y-m-d"'
            ];
        } else {
            return [

            ];
        }
    }

    protected function getValidatorInstance()
    {
        if ($this->method() == "POST" || $this->method() == "PUT") {
            $user = admin()->id;
            $this->merge(['adminid' => $user]);
        }
        return parent::getValidatorInstance();
    }


}
