<?php
namespace Modules\Admin\Http\Requests\Documents;

use Illuminate\Foundation\Http\FormRequest;

class UserDocumentTypesRequest extends FormRequest
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
                'title' => 'required|string|max:255',
                'code' => 'required|unique:user_document_type',
            ];
        } elseif ($this->method() == "PUT") {
            return [
                'title' => 'required|string|max:255',
            ];
        } else {
            return [

            ];
        }
    }

    protected function getValidatorInstance()
    {
        if ($this->method() == "POST") {
            $code = getCode($this->get('title'));
            $this->merge(['code' => $code]);
        }
        return parent::getValidatorInstance();
    }


}
