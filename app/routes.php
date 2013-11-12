<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/** ------------------------------------------
 *  Route model binding
 *  ------------------------------------------
 */
Route::model('user', 'User');
Route::model('comment', 'Comment');
Route::model('post', 'Post');
Route::model('role', 'Role');
Route::model('boards', 'Boards');




#get user profile
Route::get('user/{username}/profile', 'UserController@getUserProfile');

#Return Board
Route::get('boards/{boardsSlug}', 'BoardController@getBlocks');

#Return Block
Route::get('block/{id}', 'BoardController@getBlock');

/** ------------------------------------------
 *must be logged in
 * ------------------------------------------
 */
Route::group(array('before' => 'auth'), function()
{
    # Create Board
    Route::get('create/board', function(){
        return View::make('user/boards/create');
    });

    Route::post('board/create', 'BoardController@createBoard');

    Route::post('order', 'BoardController@orderBlocks');

    Route::post('{block}/delete', 'BoardController@deleteBlock');

    Route::post('boards/{board}/delete', 'BoardController@deleteBoard');

    Route::post('edit/board/title', 'BoardController@editTitle');

    Route::post('edit/board/description', 'BoardController@editDescription');

    Route::post('edit/board/cover', 'BoardController@editCover');

    Route::post('edit/block/title', 'BoardController@editBlockTitle');

    Route::post('edit/block/text', 'BoardController@editBlockText');

    Route::post('edit/block/photo', 'BoardController@editBlockPhoto');

    Route::post('block/add/comment', 'BoardController@addComment');

    Route::post('follow', 'RelationshipController@subscribe');

    Route::post('unsubscribe', 'RelationshipController@unsubscribe');

    Route::post('board/{boardsSlug}/block/create', 'BoardController@createBlock');

});


Route::post('/user/login', 'UserController@postLogin');

Route::controller('user', 'UserController');

Route::get('/', array('before' => 'auth','uses' => 'UserController@getUserHome'));


