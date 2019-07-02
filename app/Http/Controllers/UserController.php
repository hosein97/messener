<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
            $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        
        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }
        
        $user = User::create([
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password'))
                ]);
                
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user','token'),201);
    }

    public function getAuthenticatedUser()
        {
                try {

                        if (! $user = JWTAuth::parseToken()->authenticate()) {
                                return response()->json(['user_not_found'], 404);
                        }

                } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                        return response()->json(['token_expired'], $e->getStatusCode());

                } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                        return response()->json(['token_invalid'], $e->getStatusCode());

                } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                        return response()->json(['token_absent'], $e->getStatusCode());

                }

                return response()->json(compact('user'));
        }


        public function addContact(Request $request){
            $contactName = $request->input('contact_name');
            $contact = User::where('username', $contactName)->get();
            
            if (count($contact) > 0){
                $user = JWTAuth::user();
                if ($user->contacts->contains('id',$contact[0]->id)){
                    return response()->json('Duplicate error',409);
                }
                $user->contacts()->save( $contact[0] );
                return response()->json($contact[0],200);
            }
            return response()->json('Contact not found',404);

        }

        public function getContacts(Request $request){
            $user = JWTAuth::user();
            $contacts = $user->contacts()->get();
            return response()->json($contacts,200);
        }
}
