<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
use App\Message;
use App\Chat;
use App\User;


Broadcast::channel('messages.{id}', function ($user, $id) {
   return true;
    // $chat = Chat::find($id);
    // $users = $chat->users()->get();
    // foreach ($users as $u){
    //     if ($u->id === $user->id){
    //         return true;
    //     }
    // }
    // return false;
});
