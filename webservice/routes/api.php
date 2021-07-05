<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\UserController;
use App\Models\Content;
use App\Models\User;
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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::put('/profile', [UserController::class, 'profile']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/content/add', [ContentController::class, 'add']);
    // Route::prefix('content')->group(function () {
    //     Route::post('add', [ContentController::class, 'add']);
    // });
});

Route::get('/testes', function (Request $request) {
    $user = User::find(1);
    /* conteudos */
    // $user->contents()->create(
    //     [
    //         'title' => 'title',
    //         'text' => 'texto',
    //         'image' => 'image',
    //         'link' => 'link'
    //     ]
    // );
    // return $user->contents;


    /* adicionando amigos */
    /*
    $user2 = User::find(2);
    // $user->friends()->attach($user2->id);
    // $user->friends()->detach($user2->id);
    $user->friends()->toggle($user2->id);
    return $user->friends;
    */

    /**add amigos */
    /*
    $content = Content::find(1);
    // $user->likes()->toggle($content->id);
    return $content->likes;
    return $user->likes;
    */

    /* add comentários*/
    /*
    $content = Content::find(1);
    $user = User::find(1);
    $user->comments()->create(
        [
            'content_id' =>  $content->id,
            'text' => 'usuário 1',
        ]
    );
    $user = User::find(2);
    $user->comments()->create(
        [
            'content_id' =>  $content->id,
            'text' => 'usuário 2',
        ]
    );
    return $content->comments;
*/

    return "ok";
});
