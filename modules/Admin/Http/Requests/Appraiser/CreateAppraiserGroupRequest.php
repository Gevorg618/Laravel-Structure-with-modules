<?php

namespace Modules\Admin\Http\Requests\Appraiser;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateAppraiserGroupRequest extends FormRequest
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
        $rules = [
            'title'       => 'required|string|min:3|max:125',
            'description' => 'required|string|min:3|max:125',
            'managerid'   => 'required|exists:user,id',
        ];
        return $rules;
    }

    /** {@inheritdoc} */
    public function attributes()
    {
        return [
            'title'       => 'Title',
            'managerid'   => 'Manager',
            'description' => 'Description',
        ];
    }

}
