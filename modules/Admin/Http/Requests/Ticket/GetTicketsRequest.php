<?php

namespace Modules\Admin\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class GetTicketsRequest extends FormRequest
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
        return [

        ];
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        if ($this->method() == 'GET') {
            $input = $this->all();
            $fields = $this->only([
                'search.value', 'grouped', 'open_or_close', 'status', 'category', 'priority', 'timezone'
            ]);

            $input['hashedQuery'] = base64_encode(http_build_query($fields));

            $this->replace($input);
        }

        return parent::getValidatorInstance();
    }
}
