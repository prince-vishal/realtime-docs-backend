<?php

use Illuminate\Support\Facades\Route;

$namespace = 'App\Modules\Docs\Controllers\v1';
$guardingMiddleware = ['auth:api', 'bindings','jwt.auth'];
$prefix = 'api/v1/docs';
// Guarded Routes
Route::group(
    [
        'namespace' => $namespace,
        'prefix' => $prefix,
        'middleware' => $guardingMiddleware
    ],
    function () {

        Route::get('/', 'DocController@allDocs');
        Route::post('/', 'DocController@create');
        Route::get('/viewed', 'DocController@viewedDocs');
        Route::put('/{doc}/share', 'DocController@assignRolesToUserForADoc');
        Route::get('/{doc}/is_authorized', 'DocController@checkIfAuthorized');
        Route::get('/{doc}', 'DocController@show');
        Route::get('/{doc}/viewers', 'DocController@showViewers');


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


    }
);
