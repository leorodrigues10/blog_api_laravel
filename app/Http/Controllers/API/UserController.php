<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        event(new Registered($user));

        $token = $user->createToken('blogpost')->plainTextToken;

        $response = [
             'user' => $user,
             'token' => $token
        ];
        return response($response, 201);
    }



    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

       //check email
        $user = $this->checkEmail($fields['email']);

        //check password
        if($this->checkPassword($user, $fields['password'])){
            return response([
                'message' => 'bad creds'
            ], 401);
        }

        $token = $this->getToken($user);

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    private function checkPassword($user, $password){
        return !$user || !Hash::check($password, $user->password);
    }

    private function checkEmail($email) {
        return User::where('email', $email)->first();
    }

    private function getToken($user) {
        return $user->createToken('blogPost')->plainTextToken;
    }


    public function logout() {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logged out'
        ]);
    }
}
