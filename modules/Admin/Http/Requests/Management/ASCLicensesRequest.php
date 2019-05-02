<?php
namespace Modules\Admin\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ASCLicensesRequest extends FormRequest
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
        if ($this->method() == "POST") {
            return [
                'filter.filter.from' => 'date_format:"Y-m-d"',
                'filter.filter.to' => 'date_format:"Y-m-d"',
                'filter.filter.state' => 'in:'.getStateKeys(),
                'filter.filter.license_status' => 'in:A,I',
                'filter.filter.license_type' => 'between:1,4'
            ];
        }
    }
}
