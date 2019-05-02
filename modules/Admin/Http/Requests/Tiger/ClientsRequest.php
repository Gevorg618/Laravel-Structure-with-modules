<?php
    
    namespace Modules\Admin\Http\Requests\Tiger;
    
    use Illuminate\Foundation\Http\FormRequest;
    
    class ClientsRequest extends FormRequest
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
            if($this->route('id'))
            {
                return $this->update();
            }
        
            return $this->store();
        }
    
        /**
         * Set rules for storing new tiger client
         * @return array
         */
        private function store()
        {
            return [
                'title'     =>    'required|unique:tiger.client',
                'domain'    =>    'required|unique:tiger.client',
            ];
        }
    
        /**
         * Set rules for updating existing tiger client
         * @return array
         */
        private function update()
        {
            return [
                'title'     =>    'required|unique:tiger.client,title,'.$this->route('id'),
                'domain'    =>    'required|unique:tiger.client,domain,'.$this->route('id'),
            ];
        }
    }
