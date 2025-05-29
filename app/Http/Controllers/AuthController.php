<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function forgotPassword()
    {
        return view('auth.forgot-password', [
            'title' => 'Lupa Password'
        ]);
    }

    public function sendResetPasswordEmail(Request $request)
    {
        $request->validate(['username' => 'required', 'string', 'max:255']);

        $user = User::where('username', $request->get('username'))->first();

        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan');
        }

        $status = Password::sendResetLink(
            ['email' => $user->email]
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => 'Tautan reset password berhasil terkirim ke ' . $user->email . '. Silahkan cek kotak masuk email Anda!'])
            : back()->with(['error' => 'Tautan reset password gagal terkirim ke ' . $user->email . '. Silahkan cek kembali email yang terdaftar!']);
    }

    public function passwordResetToken(Request $request, $token)
    {
        return view('auth.reset-password', [
            'title' => 'Reset Password',
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email:dns',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset. Silahkan login kembali!')
            : back()->with(['error' => 'Password gagal direset.']);
    }
}
