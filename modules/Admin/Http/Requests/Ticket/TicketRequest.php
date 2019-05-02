<?php

namespace Modules\Admin\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    /**
     * The route to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirectRoute = 'admin.ticket.manager';

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
            'params' => 'string',
            'lock_ticket' => 'numeric',
        ];
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        if ($this->method() == 'GET') {
            $input = $this->all();

            $input['hashedQuery'] = $input['params'] ?? '';
            $input['queryString'] = isset($input['params']) ? base64_decode($input['params']) : '';
            $input['status'] = !empty($input['status']) ? array_map('intval', $input['status']) : [];
            $input['category'] = !empty($input['category']) ? array_map('intval', $input['category']) : [];

            $this->replace($input);
        }

        return parent::getValidatorInstance();
    }
}
