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
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::prefix('user')->group(function () {
        Route::put('profile', [UserController::class, 'profile']);
        Route::post('follow', [UserController::class, 'follow']);
        Route::get('friends/{id}', [UserController::class, 'friends']);
    });
    Route::prefix('content')->group(function () {
        Route::post('add', [ContentController::class, 'add']);
        Route::get('list', [ContentController::class, 'list']);
        Route::put('liked/{id}', [ContentController::class, 'liked']);
        Route::post('comment/{id}', [ContentController::class, 'comment']);
        Route::get('page/{id}', [ContentController::class, 'page']);
    });
});

Route::get('/testes', function (Request $request) {
    $content = Content::find(14);
    $user = User::find(5);
    $user->comments()->create(
        [
            'content_id' =>  $content->id,
            'text' => 'usuário 1',
        ]
    );
    return $content;
/*
    $contents = Content::all();
    foreach ($contents as $key => $value) {
        $value->delete();
    }
    */
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
    $content = Content::find(14);
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
