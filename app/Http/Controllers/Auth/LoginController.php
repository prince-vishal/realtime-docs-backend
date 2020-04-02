<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    private $tokenRepository;
    private $clientRepository;

    /**
     * Create a new controller instance.
     *
     * @param TokenRepository  $tokenRepository
     * @param ClientRepository $clientRepository
     */
    public function __construct(TokenRepository $tokenRepository, ClientRepository $clientRepository)
    {
        $this->middleware('guest')->except('logout');
        $this->tokenRepository = $tokenRepository;
        $this->clientRepository = $clientRepository;
    }


    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param  mixed  $user
     *
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $personalAccessClient = $this->clientRepository->personalAccessClient();
        $token = Passport::token()->where([["user_id", $user->id], ["client_id", $personalAccessClient->id]])->first();

        if ($token) {
            $token->delete();
        }

        // Creating a new token without scopes...
        $token = $user->createToken('Token When Logged In')->accessToken;
        return response()->json(["success" => true, "user" => $user, "access_token" => $token]);
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
        $user = Auth::user();
        // Revoke this token
        $user->token()->revoke();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/');
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
        return response()->json(["success" => true, "message" => "Successfully Logged Out"]);
    }


}
