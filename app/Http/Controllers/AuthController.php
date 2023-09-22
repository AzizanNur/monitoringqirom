<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{

    public function register(Request $request){
        return $user = User::create([
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'password'  => Hash::make($request->input('password')),
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $user->remember_token = $token;
        $user->save();
        $cookie = cookie('jwt', $token, 60 * 24); // 1 day

        return response([
            'id' => $user['id'],
            'first_name' => $user['name'],
            'last_name' => '',
            'email' => $user['email'],
            'email_verified_at' => $user['email_verified_at'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at'],
            'api_token' => $token
        ])->withCookie($cookie);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Success'
        ])->withCookie($cookie);
    }

    public function user(){
        return Auth::user();
    }

    public function verify_token(Request $request){
        $users = User::where('remember_token', $request->input('api_token'))->get()->toArray();
        $response = array();
        if(count($users) > 0){
            $user  = $users[0];
            $response = array(
                'id' => $user['id'],
                'first_name' => $user['name'],
                'last_name' => '',
                'email' => $user['email'],
                'email_verified_at' => $user['email_verified_at'],
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at'],
                'api_token' => $user['remember_token']
            );
        }
        return response($response);
    }
}
