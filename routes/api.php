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

/**
 * @param \Illuminate\Database\Eloquent\Model|string $model
 * @return \Illuminate\Http\JsonResponse
 */
function collection($model)
{
    if (is_string($model))
    {
        $model = $model::all();
    }
    return response()->json(['data' => $model->toArray()]);
}

/**
 * @param \Illuminate\Database\Eloquent\Model $model
 * @return \Illuminate\Http\JsonResponse
 */
function single($model)
{
    if (!$model->exists)
    {
        return response(null, 404)->json(['status' => 404, 'reason' => 'not found']);
    }
    return response()->json($model->toArray());
}

function createJwtToken($id, $name)
{
    $payload = [
        "sub" => $id,
        "name" => $name,
        "iat" => now()->getTimestamp(),
    ];
    return \Firebase\JWT\JWT::encode($payload, 'secret', 'HS256');
}

Route::middleware('auth:api')->prefix("/users")->group(
    function() {
        // get collection (get_all)
        Route::get(
            '/',
            function (Request $request)
            {
                return collection(\App\Models\User::class);
            }
        );

        // get entity (get_by_id)
        Route::get(
            '/{id}',
            function (Request $request, $id)
            {
                return single(\App\Models\User::first($id));
            }
        );

        // Update
        Route::post(
            '/{id}',
            function (Request $request, $id)
            {
                $request->wantsJson();
                $payload = $request->json();
                $email = $payload->get('email');
                $name = $payload->get('name');
                $model = \App\Models\User::first($id);
                if (!$model->exists)
                {

                }
                return response()->json(['data' => $model->toArray()]);
            }
        )->middleware('auth.token');

        // Create
        Route::post(
            '/',
            function (Request $request)
            {
                $request->wantsJson();
                $payload = $request->json();
                $email = $payload->get('email');
                $name = $payload->get('name');
                $token = $payload->get('token');
                /** @var \App\Models\User $user */
                if ($token)
                {
                    $model = \App\Models\User::create([
                        'email' => $email,
                        'name' => $name,
                        'jwt' => $token,
                    ]);
                }
                else
                {
                    $model = \App\Models\User::create([
                        'email' => $email,
                        'name' => $name,
                    ]);
                }
                return response()->json(['data' => $model->toArray()]);
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
                return collection(\App\Models\File::class);
            }
        );

        // get entity (get_by_id)
        Route::get(
            '/{id}',
            function (Request $request, $id)
            {
                return single(\App\Models\File::first($id));
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
                return collection(\App\Models\Tag::class);
            }
        );

        // get entity (get_by_id)
        Route::get(
            '/{id}',
            function (Request $request, $id)
            {
                return single(\App\Models\Tag::first($id));
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
                return collection(\App\Models\Post::with(['tags', 'owner', 'main_image'])->all());
            }
        );

        // get entity (get_by_id)
        Route::get(
            '/{id}',
            function (Request $request, $id)
            {
                return single(\App\Models\Post::with(['tags', 'owner', 'main_image'])->first($id));
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
