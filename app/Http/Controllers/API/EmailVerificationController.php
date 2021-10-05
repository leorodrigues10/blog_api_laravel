<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verifyEmail(Request  $request) {

         $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return response((['message:' => 'Already verified']));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response((['message:' => 'Email has been verified']));
   }
}
