<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('monitoring');
        }
        
        return view('auth.login');
    }
    
    /**
     * Show register form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('monitoring');
        }
        
        return view('auth.register');
    }
    
    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Remember me feature removed
        if (Auth::attempt($credentials, false)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('monitoring'));
        }
        
        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }
    
    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pembeli', // Default role
        ]);
        
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login untuk melanjutkan.');
    }
    
    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
        } catch (\Exception $e) {
            \Log::error('Logout Error: ' . $e->getMessage());
            
            // Force logout even if session is invalid
            Auth::logout();
            
            return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
        }
    }
    
    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect()->route('monitoring');
        }
        
        return view('auth.forgot-password');
    }
    
    /**
     * Send password reset link (simplified)
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak ditemukan dalam sistem.',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }
        
        // Generate token sederhana
        $token = Str::random(64);
        
        // Simpan token ke database (update atau insert)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );
        
        // Langsung redirect ke reset form (untuk testing)
        // Di production, kirim email dengan link: /reset-password?token=xxx&email=xxx
        return redirect()->route('password.reset', [
            'token' => $token,
            'email' => $request->email,
        ])->with('status', 'Silakan reset password Anda.');
    }
    
    /**
     * Show reset password form
     */
    public function showResetPassword(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('monitoring');
        }
        
        $token = $request->query('token');
        $email = $request->query('email');
        
        // Validasi token
        if (!$token || !$email || !$this->isValidToken($token, $email)) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'Token tidak valid atau sudah kadaluarsa.']);
        }
        
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }
    
    /**
     * Reset password (simplified)
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.exists' => 'Email tidak ditemukan dalam sistem.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
        
        // Validasi token
        if (!$this->isValidToken($request->token, $request->email)) {
            return back()->withErrors(['token' => 'Token tidak valid atau sudah kadaluarsa.']);
        }
        
        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Hapus token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        return redirect()->route('login')
            ->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
    
    /**
     * Check if token is valid (simplified)
     */
    private function isValidToken($token, $email)
    {
        if (!$token || !$email) {
            return false;
        }
        
        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();
        
        if (!$reset) {
            return false;
        }
        
        // Check if token expired (60 minutes)
        if (now()->diffInMinutes($reset->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return false;
        }
        
        // Verify token
        return Hash::check($token, $reset->token);
    }
}
