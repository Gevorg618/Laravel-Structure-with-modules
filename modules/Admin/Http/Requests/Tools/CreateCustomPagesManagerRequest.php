<?php
namespace Modules\Admin\Http\Requests\Tools;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
    
class CreateCustomPagesManagerRequest extends FormRequest
{
    
    /**
     * Create code in constructor so we can set validation rule
     * CustomPagesManagerRequest constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $timestamp = strtotime("now");
        $request->request->add(['created_by' => admin()->id, 'created_date' => $timestamp, 'modified_date' => $timestamp, 'modified_by' => admin()->id]);
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
        if ($this->method() === "POST") {
            return $this->store();
        } elseif ($this->method() === "PUT") {
            return $this->update();
        }

        return [

        ];
    }

    /** {@inheritdoc} */
    public function attributes()
    {
        return [
            'name'       => 'Name',
            'title'   => 'Title',
            'content' => 'Content',
            'logo_image' => "The image has invalid Logo image dimensions. Min width must be 1080px"
        ];
    }

    /**
     * @return array
     */
    private function store()
    {
        return [
            'name'        => 'required|string|min:3|max:125',
            'title'       => 'required|string|min:3|max:125',
            'content'     => 'required|string',
            'logo_image'  => 'required|mimes:jpeg,jpg,png|dimensions:min_width=1080'
        ];
    }

    /**
     * @return array
     */
    private function update()
    {
        return [
            'name'        => 'required|string|min:3|max:125',
            'title'       => 'required|string|min:3|max:125',
            'content'     => 'required|string',
            'logo_image'  => 'mimes:jpeg,jpg,png|dimensions:min_width=1080'
        ];
    }
}
