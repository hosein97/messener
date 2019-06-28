<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Chat;
use App\User;
use App\Message;
use Illuminate\Support\Facades\Config;

class ChatController extends Controller
{
    public function create(Request $request){
        $usernames = $request->input('usernames');
        $chatName = $request->input('chat_name');
        $isGroup = $request->input('is_group') === 'true';
        $users = [];
        foreach ($usernames as $username) {
            $user = User::where('username', $username)->get();
            if (count($user) == 0){
                return response()->json(['user_not_found'], 404);
            }
            array_push($users, $user[0]);
        }
        $chat = new Chat;
        $chat->save();
        if (count($users) > 1){
            if ($isGroup){
                foreach ($users as $user)
                {
                    $chat->users()->save($user, ['permissions' => Config::get('constants.permissions.SEND_MESSAGE')]);
                }
            $chat->users()->save(JWTAuth::user(), ['permissions' => Config::get('constants.permissions.SEND_MESSAGE') + Config::get('constants.permissions.ADD_MEMBER')]);
            
        }else{ //channel
            foreach ($users as $user)
            {
                $chat->users()->save($user, ['permissions' => Config::get('constants.permissions.NULL')]);
            }
            $chat->users()->save(JWTAuth::user(), ['permissions' => Config::get('constants.permissions.SEND_MESSAGE') + Config::get('constants.permissions.ADD_MEMBER')]);
            }
        }else if (count($users) == 1){
            $chat->users()->save($users[0], ['permissions' => Config::get('constants.permissions.SEND_MESSAGE')]);
            $chat->users()->save(JWTAuth::user(), ['permissions' => Config::get('constants.permissions.SEND_MESSAGE')]);
        }

        return response()->json($chat,200);
    }

    public function getAll(Request $request){
        $chats = Chat::with(['users' => function ($query) {
            $query->where('id', JWTAuth::user()->id);
        }])->get();
        if (count($chats) > 0){
            return response()->json($chats,200);
        }
        return response()->json('Chat not found!',404);
    }

    public function addMessage(Request $request, $chatId){
        $chat = Chat::where('id', $chatId)->get();
        if (count($chat) > 0){
            $text = $request->input('text');
            $message = new Message;
            $message->user()->associate(JWTAuth::user());
            $message->chat()->associate($chat[0]);
            $message->text = $text;
            $message->save();
            return response()->json($message,200);
        }
        return response()->json('Chat not found',404);
    }
}
