<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/4/20
 * Time: 7:53 PM
 */

namespace App\Modules\Auth\Helpers\v1;


use Illuminate\Support\Facades\Validator;

class ValidationHelper
{

    public function getRegistrationValidator($data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'source' => ['nullable', 'string', 'in:facebook,google,amazon,linkedin,github'],
            'password' => ['required_without:source', 'string', 'min:8'],
            'company' => ['nullable', 'string'],
            'token' => ['required_with:source', 'string', 'nullable'],
        ]);
    }

    public function getLoginValidator($data)
    {
        return Validator::make($data, [
            'name' => ['required_without:password', 'string', 'max:255'],
            'company' => ['nullable', 'string'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'source' => ['nullable', 'string', 'in:facebook,google,amazon,linkedin,github'],
            'password' => ['required_without:source', 'string', 'min:8'],
            'token' => ['nullable', 'required_with:source', 'string'],
        ]);
    }
}
