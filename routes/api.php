<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->prefix("/users")->group(
    function() {
        // get collection (get_all)
        Route::get(
            '/',
            function (Request $request)
            {
                //
            }
        );

        // get entity (get_by_id)
        Route::get(
            '/{id}',
            function (Request $request, $id)
            {
                //
            }
        );

        // Update
        Route::post(
            '/{id}',
            function (Request $request, $id)
            {
                //
            }
        )->middleware('auth.token');

        // Create
        Route::post(
            '/',
            function (Request $request)
            {
                //
            }
        );
    }
);

Route::middleware('auth:api')->prefix("/files")->group(
    function() {
        // get collection (get_all)
        Route::get(
            '/',
            function (Request $request)
            {
                //
            }
        );

        // get entity (get_by_id)
        Route::get(
            '/{id}',
            function (Request $request, $id)
            {
                //
            }
        );

        // Delete
        Route::delete(
            '/{id}',
            function (Request $request, $id)
            {
                //
            }
        )->middleware('auth.token');

        // Create
        Route::post(
            '/',
            function (Request $request)
            {
                //
            }
        )->middleware('auth.token');
    }
);

Route::middleware('auth:api')->prefix("/tags")->group(
    function() {
        // get collection (get_all)
        Route::get(
            '/',
            function (Request $request)
            {
                //
            }
        );

        // get entity (get_by_id)
        Route::get(
            '/{id}',
            function (Request $request, $id)
            {
                //
            }
        );

        // Update
        Route::post(
            '/{id}',
            function (Request $request, $id)
            {
                //
            }
        )->middleware('auth.token');

        // Delete
        Route::delete(
            '/{id}',
            function (Request $request, $id)
            {
                //
            }
        )->middleware('auth.token');

        // Create
        Route::post(
            '/',
            function (Request $request)
            {
                //
            }
        )->middleware('auth.token');
    }
);

Route::middleware('auth:api')->prefix("/posts")->group(
    function() {
        // get collection (get_all)
        Route::get(
            '/',
            function (Request $request)
            {
                //
            }
        );

        // get entity (get_by_id)
        Route::get(
            '/{id}',
            function (Request $request, $id)
            {
                //
            }
        );

        // Update
        Route::post(
            '/{id}',
            function (Request $request, $id)
            {
                //
            }
        )->middleware('auth.token');

        // Delete
        Route::delete(
            '/{id}',
            function (Request $request, $id)
            {
                //
            }
        )->middleware('auth.token');

        // Create
        Route::post(
            '/',
            function (Request $request)
            {
                //
            }
        )->middleware('auth.token');
    }
);
