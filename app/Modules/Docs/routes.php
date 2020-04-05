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
        Route::get('/viewed', 'DocController@viewedDocs');
        Route::get('/{doc}', 'DocController@show');
        Route::get('/{doc}/viewers', 'DocController@showViewers');
        Route::post('/', 'DocController@create');


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
