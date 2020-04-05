<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/4/20
 * Time: 8:02 PM
 */

namespace App\Modules\Auth\Helpers\v1;


use App\Models\User;
use App\Modules\Auth\Models\Role;
use App\Modules\Docs\Models\Doc;
use App\Modules\Docs\Models\DocUser;
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


    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function register(Request $request)
    {
        $this->registrationValidator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        auth()->login($user);

        $this->authorizeToViewDoc($user);

        $response = $this->registered($request, $user);
        return $response;


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
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function loginValidator(array $data)
    {
        return $this->validationHelper->getLoginValidator($data);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->loginValidator($request->all())->validate();
        $credentials = request(['email', 'password']);
        $token = null;
        /*
         * OAuth Login
         * */
        if (!isset($credentials['password'])) {

            return $this->oauthLogin($request);

        } else {
            /*
             * Login using password
             *
             * */
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Invalid Email or password', 'success' => false], 401);
            }
        }
        $response = $this->authenticated($request, $token);
        return $response;
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    private function oauthLogin(Request $request)
    {
        $user = User::where([
            'email' => $request->email,
        ])->first();
        if ($user) {

            if ($user->source != $request->source) {
                return response()->json([
                    'error' => 'An account already exists with same email and different provider',
                    'success' => false
                ], 401);

            }

            $user->oauth_token = $request->token;
            $user->save();
            if (!$token = auth()->tokenById($user->id)) {
                return response()->json(['error' => 'Invalid credentials', 'success' => false], 401);
            }

        } else {
            // Register this user
            return $this->register($request);
        }

        $response = $this->authenticated($request, $token);
        return $response;

    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param         $token
     *
     * @return mixed
     */
    protected function authenticated(Request $request, $token)
    {
        return $this->respondWithToken($token);
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
        auth()->logout();
        $response = $this->loggedOut($request);
        return $response;
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return response()->json(['message' => 'Successfully Logged Out', 'success' => true], 200);
    }

    /**
     * @param $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            "success" => true,
            'user' => Auth::user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /*
     * Authorize this user to view doc 1
     * */
    private function authorizeToViewDoc($user)
    {
        $doc = Doc::find(1);
        $sharedDocUser = new DocUser();
        $role = Role::where("name", "edit")->first();
        $existingDocUser = DocUser::where([
            "user_id" => $user->id,
            "doc_id" => $doc->id,
        ])->first();

        // Update role if it already exists
        if ($existingDocUser) {
            $sharedDocUser = $existingDocUser;
        }

        $sharedDocUser->doc_id = $doc->id;
        $sharedDocUser->role_id = $role->id;
        $sharedDocUser->user_id = $user->id;
        $sharedDocUser->save();
    }


}
