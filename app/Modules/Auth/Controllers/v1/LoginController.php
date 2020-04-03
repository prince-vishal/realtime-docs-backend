<?php

namespace App\Modules\Auth\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Helpers\v1\AuthHelper;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    protected $authHelper;

    public function __construct(AuthHelper $authHelper)
    {
        $this->authHelper = $authHelper;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function login(Request $request)
    {
        return $this->authHelper->login($request);

    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        return $this->authHelper->logout($request);

    }


}
