<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // 🔥 Récupérer l'utilisateur
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email non trouvé'
            ], 400);
        }

        // 🔥 Envoyer manuellement la notification
        try {
            $token = \Illuminate\Support\Str::random(60);

            // Stocker le token dans password_reset_tokens
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => bcrypt($token),
                    'created_at' => now()
                ]
            );

            // Envoyer la notification
            $user->notify(new \App\Notifications\ResetPasswordNotification($token, $user->email));

            return response()->json([
                'status' => true,
                'message' => 'Un lien de réinitialisation a été envoyé.'
            ], 200);

        } catch (\Exception $e) {

            // \Log::error('Erreur envoi email: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Erreur technique: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);

        // Vérifier le token
        $resetRecord = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord || !\Illuminate\Support\Facades\Hash::check($request->token, $resetRecord->token)) {
            return response()->json([
                'status' => false,
                'message' => 'Token invalide'
            ], 400);
        }

        // Vérifier expiration (60 minutes)
        $expires = now()->diffInMinutes($resetRecord->created_at);
        if ($expires > 60) {
            return response()->json([
                'status' => false,
                'message' => 'Le lien a expiré. Veuillez refaire une demande.'
            ], 400);
        }

        // Mettre à jour le mot de passe
        $user = \App\Models\User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Supprimer le token
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Mot de passe réinitialisé avec succès!'
        ], 200);
    }
}
