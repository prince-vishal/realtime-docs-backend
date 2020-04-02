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

    }
);
