<?php

use Illuminate\Support\Facades\Route;

$namespace = 'App\Modules\Auth\Controllers\v1';
$guardingMiddleware = ['auth:api', 'bindings','jwt.auth'];
$authPrefix = 'auth/v1';
$usersPrefix = 'api/v1/users';
// Guarded Routes
Route::group(
    [
        'namespace' => $namespace,
        'prefix' => $authPrefix,
        'middleware' => $guardingMiddleware
    ],
    function () {
        Route::post('/logout', 'LoginController@logout');

    }
);

// Unguarded Routes
Route::group(
    [
        'namespace' => $namespace,
        'prefix' => $authPrefix,
        'middleware' => ['bindings']
    ],
    function () {
        Route::post('/register', 'RegisterController@register');
        Route::post('/login', 'LoginController@login');
    }
);
// Unguarded Routes
Route::group(
    [
        'namespace' => $namespace,
        'prefix' => $usersPrefix,
        'middleware' => $guardingMiddleware
    ],
    function () {
        Route::get('/', 'UsersController@listUserEmailsNames');
    }
);
