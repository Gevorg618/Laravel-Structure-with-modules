<?php
    namespace Modules\Admin\Http\Requests\Management;
    
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Request;
    
    class GroupRequest extends FormRequest
    {
        
        /**
         * Create gkey in constructor so we can set validation rule
         * AppraisalRequest constructor.
         * @param Request $request
         */
        public function __construct(Request $request)
        {
            $request->request->add(['gkey' => getCode($request->title)]);
        }
        
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
         * Set rules for storing new docuvault appraisal
         * @return array
         */
        private function store()
        {
            return [
                'title'         =>    'required|unique:user_group',
                'gkey'          =>    'required|unique:user_group',
                'is_default'     =>    'required|boolean',
            ];
        }
        
        /**
         * Set rules for updating existing docuvault appraisal
         * @return array
         */
        private function update()
        {
            return [
                'title'         =>    'required|unique:user_group,title,'.$this->route('id'),
                'is_default'     =>    'required|boolean',
            ];
        }
    }
