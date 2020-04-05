<?php

namespace App\Modules\Auth\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Auth\Helpers\v1\AuthHelper;
use App\Responses\SuccessResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{


    public function __construct()
    {
    }

    /**
     * List User Emails and Name.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function listUserEmailsNames(Request $request)
    {
        $users = User::all(['email','name']);
        return (new SuccessResponse($users))->send();

    }



}
