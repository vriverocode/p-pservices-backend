<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $user = User::findOrFail($request->route('id'));
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Enlace inválido'], 400);
        }
        if (! $request->hasValidSignature()) {
            return response()->json(['message' => 'El enlace ha expirado'], 400);
        }
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new \Illuminate\Auth\Events\Verified($user));
        }
        return response()->json(['message' => 'Correo verificado exitosamente']);
    }
    public function resendVerificationEmail(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'El correo ya está verificado.'], 400);
        }
        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Enlace reenviado.']);
    }
}
