<?php
namespace Modules\Admin\Http\Requests\Documents;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class ResourceDocumentRequest extends FormRequest
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
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'link' => 'required|url',
                'type' => 'required|string',
            ];
        } else {
            return [

            ];
        }
    }

    protected function getValidatorInstance()
    {
        if ($this->method() == "POST") {
            $user = admin()->id;
            $this->merge(['created_by' => $user]);
        }
        return parent::getValidatorInstance();
    }


}
