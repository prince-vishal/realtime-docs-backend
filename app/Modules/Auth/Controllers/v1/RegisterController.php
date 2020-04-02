<?php

namespace App\Modules\Auth\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Helpers\v1\AuthHelper;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    protected $authHelper;

    /**
     * Create a new controller instance.
     *
     * @param AuthHelper $authHelper
     */
    public function __construct(AuthHelper $authHelper)
    {
        $this->authHelper = $authHelper;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        return $this->authHelper->register($request);
    }


}
