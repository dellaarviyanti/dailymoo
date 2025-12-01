@extends('layouts.app')

@push('styles')
<style>
    .animated-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #5DB996 0%, #118B50 100%);
        opacity: 0.1;
        animation: gradient 15s ease infinite;
        background-size: 400% 400%;
    }

    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .glass {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    .input-group {
        position: relative;
    }

    .input-group input:focus + label,
    .input-group input:not(:placeholder-shown) + label {
        transform: translateY(-1.5rem) translateX(-0.5rem) scale(0.85);
        color: #5DB996;
    }

    .input-group label {
        position: absolute;
        left: 1rem;
        top: 1rem;
        transition: all 0.3s ease;
        pointer-events: none;
        background: white;
        padding: 0 0.5rem;
    }

    .slide-in {
        animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex relative overflow-hidden" x-data="{ showPassword: false, showPasswordConfirmation: false }">
    <!-- Animated Background -->
    <div class="animated-bg"></div>
    
    <!-- Left Side - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-8 relative z-10">
        <div class="w-full max-w-md slide-in">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary via-primary to-primary-dark rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 transition-all duration-300">
                        <span class="text-4xl">🐄</span>
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3 bg-gradient-to-r from-primary to-primary-dark bg-clip-text text-transparent">
                    Reset Password
                </h1>
                <p class="text-gray-600 text-lg">Masukkan password baru Anda</p>
            </div>

            <!-- Form Card -->
            <div class="glass rounded-3xl shadow-2xl border border-white/50 overflow-hidden backdrop-blur-xl">
                <div class="p-8 sm:p-10">
                    <form action="{{ route('password.update') }}" method="POST" class="space-y-6" x-data="{ submitting: false }" @submit="submitting = true">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token ?? '' }}">
                        <input type="hidden" name="email" value="{{ $email ?? '' }}">
                        
                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-xl" role="alert">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <ul class="list-none text-sm space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Email Field (Read-only) -->
                        <div class="input-group">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    value="{{ $email }}"
                                    class="pl-12 w-full py-4 border-2 border-gray-200 rounded-xl bg-gray-100 cursor-not-allowed"
                                    disabled
                                />
                                <label class="text-gray-500">Email</label>
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="input-group">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="w-5 h-5 text-gray-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input
                                    :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    placeholder=" "
                                    class="pl-12 pr-12 w-full py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300 bg-white/50"
                                    required
                                    autocomplete="new-password"
                                    minlength="8"
                                />
                                <label for="password" class="text-gray-500">Password Baru</label>
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center"
                                >
                                    <svg x-show="!showPassword" class="w-5 h-5 text-gray-400 hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPassword" class="w-5 h-5 text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.736m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Password Confirmation Field -->
                        <div class="input-group">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="w-5 h-5 text-gray-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input
                                    :type="showPasswordConfirmation ? 'text' : 'password'"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder=" "
                                    class="pl-12 pr-12 w-full py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300 bg-white/50"
                                    required
                                    autocomplete="new-password"
                                    minlength="8"
                                />
                                <label for="password_confirmation" class="text-gray-500">Konfirmasi Password Baru</label>
                                <button
                                    type="button"
                                    @click="showPasswordConfirmation = !showPasswordConfirmation"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center"
                                >
                                    <svg x-show="!showPasswordConfirmation" class="w-5 h-5 text-gray-400 hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPasswordConfirmation" class="w-5 h-5 text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.736m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="submitting"
                            class="w-full px-4 py-4 bg-gradient-to-r from-primary via-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 active:translate-y-0 relative overflow-hidden"
                        >
                            <span x-show="!submitting" class="relative z-10">Reset Password</span>
                            <span x-show="submitting" class="relative z-10">Mengatur ulang...</span>
                        </button>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-sm text-primary hover:text-primary-dark font-medium transition-all duration-300 hover:underline">
                                ← Kembali ke Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Image/Decoration -->
    <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center p-8">
        <div class="text-center">
            <div class="w-64 h-64 bg-gradient-to-br from-primary/20 to-primary-dark/20 rounded-full blur-3xl"></div>
        </div>
    </div>
</div>
@endsection

