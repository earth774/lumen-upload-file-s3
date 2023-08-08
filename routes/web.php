<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->post('/s3/upload', function (Request $request) {
    $file = $request->file('file');
    $path = Storage::disk('s3')->put('uploads', $file);

    return response()->json(['message' => 'File uploaded', 'path' => $path]);
});

$router->get('/s3/download', function (Request $request) {

    $url = Storage::disk('s3')->url($request->key);

    return response()->json(['url' => $url]);
});

$router->get('/s3', function () {
    $files = Storage::disk('s3')->files('uploads/');

    return response()->json(['files' => $files]);
});

$router->post('/s3/update', function (Request $request) {
    $file = $request->file('file');
    $key = $request->input('key');

    Storage::disk('s3')->delete($key);
    $path = Storage::disk('s3')->put('uploads', $file);

    return response()->json(['message' => 'File updated', 'path' => $path]);
});

$router->post('/s3/delete', function (Request $request) {
    $key = $request->input('key');

    Storage::disk('s3')->delete($key);

    return response()->json(['message' => 'File deleted']);
});




