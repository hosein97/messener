<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('user/contacts', 'UserController@getContacts');
    Route::post('user/contacts/create', 'UserController@addContact');
    Route::post('chat', 'ChatController@create');
    Route::get('chats', 'ChatController@getAll');
    Route::post('chats/{chat_id}', 'ChatController@addMessage');
    Route::get('chats/{chat_id}', 'ChatController@getMessages');
    Route::get('chats/{chat_id}/permissions', 'ChatController@getPermissions');
    Route::post('chats/{chat_id}/members/add', 'ChatController@addMember');
    Route::get('chats/{chat_id}/members', 'ChatController@getMembers');
    Route::post('chats/{chat_id}/members/delete', 'ChatController@deleteMembers');
    Route::post('chats/{chat_id}/members/{member_id}/block', 'ChatController@blockMember');
    Route::post('chats/{chat_id}/members/{member_id}/unblock', 'ChatController@unblockMember');
});