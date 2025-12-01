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
<div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Animated Background -->
    <div class="animated-bg"></div>
    
    <!-- Centered Form -->
    <div class="w-full max-w-md p-4 sm:p-8 relative z-10 slide-in">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary via-primary to-primary-dark rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 transition-all duration-300">
                        <span class="text-4xl">🐄</span>
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3 bg-gradient-to-r from-primary to-primary-dark bg-clip-text text-transparent">
                    Lupa Password
                </h1>
                <p class="text-gray-600 text-lg">Masukkan email Anda untuk mendapatkan link reset password</p>
            </div>

            <!-- Form Card -->
            <div class="glass rounded-3xl shadow-2xl border border-white/50 overflow-hidden backdrop-blur-xl">
                <div class="p-8 sm:p-10">
                    <form action="{{ route('password.email') }}" method="POST" class="space-y-6" x-data="{ submitting: false }" @submit="submitting = true">
                        @csrf
                        
                        @if (session('status'))
                            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-xl" role="alert">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm">{{ session('status') }}</span>
                                </div>
                            </div>
                        @endif
                        
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
                        
                        <!-- Email Field -->
                        <div class="input-group">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="w-5 h-5 text-gray-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder=" "
                                    value="{{ old('email') }}"
                                    class="pl-12 w-full py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300 bg-white/50"
                                    required
                                    autocomplete="email"
                                    autofocus
                                />
                                <label for="email" class="text-gray-500">Email</label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="submitting"
                            class="w-full px-4 py-4 bg-gradient-to-r from-primary via-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 active:translate-y-0 relative overflow-hidden"
                        >
                            <span x-show="!submitting" class="relative z-10">Lanjut Reset Password</span>
                            <span x-show="submitting" class="relative z-10">Mengirim...</span>
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
</div>
@endsection

