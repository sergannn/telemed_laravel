<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyEmailControllerSerg extends Controller
{
    public function __invoke(Request $request)
    {
        Log::debug('Email Verification Request Received');
        Log::debug('User ID: ' . $request->input('id'));

        $userId = $request->input('id');
        $token = $request->input('token');
        $expires = $request->input('expires');
        $signature = $request->input('signature');

        // Verify signature (you might need to adjust this based on your exact requirements)
        $expectedSignature = hash_hmac('sha256', $userId . $token . $expires, env('API_SECRET_KEY'));
        
        /*if ($expectedSignature !== $signature) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }*/

        if (time() > $expires) {
            return response()->json(['error' => 'Token has expired'], 400);
        }

        $user = User::find($userId);

        if (!$user)
        {
            return response()->json(['error' => 'User not found '], 404);
        } 
        if($user->hasVerifiedEmail()) {
            return response()->json(['error' => 'email already verified'], 404);
        }

        $user->markEmailAsVerified();

        return response()->json([
            'status' => 'success',
            'message' => 'Email successfully verified!'
        ]);
    }
}
