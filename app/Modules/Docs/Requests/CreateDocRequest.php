<?php
/**
 * User: Prince Sinha
 * Date: 3/4/20
 * Time: 5:41 PM
 */

namespace App\Modules\Docs\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CreateDocRequest extends FormRequest
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
            'title' => 'required',
            'data' => 'nullable|array',
            'metadata' => 'nullable|array'
        ];
    }
}
