<?php
namespace Modules\Admin\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ZipCodeRequest extends FormRequest
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
                'zip_code' => 'required|regex:/\b\d{5}\b/|unique:zip_code,zip_code,'.$this->get('update_id').',zip_code',
                'city' => 'required|string',
                'county' => 'required|string',
                'state' => 'required|',
                'long' => 'required|numeric',
                'lat' => 'required|numeric',
            ];
        } else {
            return [

            ];
        }
    }
}
