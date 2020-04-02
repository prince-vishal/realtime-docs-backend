<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/4/20
 * Time: 8:02 PM
 */

namespace App\Modules\Auth\Helpers\v1;


use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class AuthHelper
{

    protected $validationHelper;

    public function __construct(ValidationHelper $validationHelper)
    {
        $this->validationHelper = $validationHelper;
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function registrationValidator(array $data)
    {
        return $this->validationHelper->getRegistrationValidator($data);
    }


    public function register(Request $request)
    {
        $this->registrationValidator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 201)
            : redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'] ?? "thisIsASecretPassword"),
            'company' => $data['company'] ?? null,
            'source' => $data['source'] ?? null,
            'oauth_token' => $data['token'] ?? null,

        ]);
    }

    /**
     * The user has been registered.
     *
     * @param  Request $request
     * @param  mixed   $user
     *
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $token = auth()->login($user);
        return response()->json([
            "success" => true,
            "user" => $user,
            "access_token" => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Where to redirect
     *
     * @return string
     */
    private function redirectPath()
    {
        return "/";
    }


}
