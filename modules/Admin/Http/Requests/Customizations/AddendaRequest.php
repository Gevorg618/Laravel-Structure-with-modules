<?php
namespace Modules\Admin\Http\Requests\Customizations;

use Illuminate\Foundation\Http\FormRequest;

class AddendaRequest extends FormRequest
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
                'descrip' => 'required|string|max:255',
                'invest' => 'required|in:N,Y',
                'price' => 'required|numeric'
            ];
        } else {
            return [

            ];
        }
    }
}
