<?php

use Illuminate\Support\Facades\Route;

$namespace = 'App\Modules\Auth\Controllers\v1';
$guardingMiddleware = ['auth:api', 'bindings'];
$prefix = 'auth/v1';
// Guarded Routes
Route::group(
    [
        'namespace' => $namespace,
        'prefix' => $prefix,
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
        'prefix' => $prefix,
        'middleware' => ['bindings']
    ],
    function () {
        Route::post('/register', 'RegisterController@register');
        Route::post('/login', 'LoginController@login');
    }
);
