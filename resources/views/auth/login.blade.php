@extends('layouts.app')

@section('title', 'Login - DailyMoo')

@push('styles')
<style>
    /* Animated Background */
    .animated-bg {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
    }
    
    .animated-bg::before,
    .animated-bg::after {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(93, 185, 150, 0.1) 0%, transparent 70%);
        animation: float 20s infinite ease-in-out;
    }
    
    .animated-bg::before {
        top: -50%;
        left: -50%;
        animation-delay: 0s;
    }
    
    .animated-bg::after {
        bottom: -50%;
        right: -50%;
        animation-delay: 10s;
    }
    
    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }
    
    /* Glassmorphism Effect */
    .glass {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
    }
    
    /* Floating Label Animation */
    .input-group {
        position: relative;
    }
    
    .input-group input:focus ~ label,
    .input-group input:not(:placeholder-shown) ~ label {
        transform: translateY(-24px) scale(0.85);
        color: #5DB996;
    }
    
    .input-group label {
        position: absolute;
        left: 3.5rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        transition: all 0.3s ease;
        color: #9CA3AF;
        font-size: 0.95rem;
    }
    
    /* Password Toggle Animation */
    .password-toggle {
        transition: all 0.3s ease;
    }
    
    .password-toggle:hover {
        transform: scale(1.1);
    }
    
    /* Button Ripple Effect */
    .ripple {
        position: relative;
        overflow: hidden;
    }
    
    .ripple::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .ripple:active::after {
        width: 300px;
        height: 300px;
    }
    
    /* Shimmer Effect */
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    
    .shimmer {
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.3) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        background-size: 1000px 100%;
        animation: shimmer 3s infinite;
    }
    
    /* Pulse Animation for Logo */
    @keyframes pulse-glow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(93, 185, 150, 0.4);
        }
        50% {
            box-shadow: 0 0 40px rgba(93, 185, 150, 0.8);
        }
    }
    
    .logo-pulse {
        animation: pulse-glow 3s ease-in-out infinite;
    }
    
    /* Slide In Animation */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .slide-in {
        animation: slideInUp 0.6s ease-out;
    }
    
    /* Input Focus Glow */
    .input-focus-glow:focus {
        box-shadow: 0 0 0 4px rgba(93, 185, 150, 0.1);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex relative overflow-hidden" x-data="{ showPassword: false }">
    <!-- Animated Background -->
    <div class="animated-bg"></div>
    
    <!-- Left Side - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-8 relative z-10">
        <div class="w-full max-w-md slide-in">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary via-primary to-primary-dark rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 transition-all duration-300 logo-pulse">
                        <span class="text-4xl">🐄</span>
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3 bg-gradient-to-r from-primary to-primary-dark bg-clip-text text-transparent">
                    Selamat Datang
                </h1>
                <p class="text-gray-600 text-lg">Masuk ke akun DailyMoo Anda</p>
            </div>

            <!-- Form Card -->
            <div class="glass rounded-3xl shadow-2xl border border-white/50 overflow-hidden backdrop-blur-xl">
                <div class="p-8 sm:p-10">
                    <form action="{{ route('login.post') }}" method="POST" class="space-y-6" x-data="{ submitting: false }" @submit="submitting = true">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-xl animate-pulse" role="alert">
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
                                    <svg class="w-5 h-5 text-gray-400 transition-colors duration-300" :class="{'text-primary': document.getElementById('email')?.value}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder=" "
                                    class="pl-12 pr-4 w-full py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300 input-focus-glow bg-white/50"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                />
                                <label for="email" class="text-gray-500">Email</label>
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="input-group">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="w-5 h-5 text-gray-400 transition-colors duration-300" :class="{'text-primary': document.getElementById('password')?.value}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input
                                    :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    placeholder=" "
                                    class="pl-12 pr-12 w-full py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300 input-focus-glow bg-white/50"
                                    required
                                    autocomplete="current-password"
                                />
                                <label for="password" class="text-gray-500">Password</label>
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center password-toggle"
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

                        <!-- Forgot Password -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-primary-dark font-medium transition-all duration-300 hover:underline">
                                Lupa password?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="submitting"
                            class="ripple w-full px-4 py-4 bg-gradient-to-r from-primary via-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 active:translate-y-0 shimmer relative overflow-hidden"
                        >
                            <span x-show="!submitting" class="relative z-10">Masuk</span>
                            <span x-show="submitting" class="relative z-10 flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>

                        <!-- Register Link -->
                        <div class="text-center pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-600">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="text-primary hover:text-primary-dark font-semibold transition-all duration-300 hover:underline inline-flex items-center">
                                    Daftar di sini
                                    <svg class="w-4 h-4 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-primary transition-all duration-300 inline-flex items-center group">
                    <svg class="w-4 h-4 mr-2 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <!-- Right Side - Image with Overlay -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img 
                src="https://images.pexels.com/photos/3656870/pexels-photo-3656870.jpeg" 
                alt="Peternakan Sapi" 
                class="w-full h-full object-cover transform scale-105 hover:scale-100 transition-transform duration-700"
                onerror="this.src='https://placehold.co/800x1200/5DB996/ffffff?text=DailyMoo+Peternakan+Sapi'"
            />
            <div class="absolute inset-0 bg-gradient-to-br from-primary/95 via-primary/85 to-primary-dark/95"></div>
        </div>
        
        <!-- Content Overlay -->
        <div class="relative w-full h-full flex items-center justify-center p-12 z-10">
            <div class="text-center text-white space-y-6">
                <div class="mb-8 transform hover:scale-110 transition-transform duration-300">
                    <div class="w-32 h-32 mx-auto bg-white/20 backdrop-blur-lg rounded-full flex items-center justify-center border-4 border-white/30 shadow-2xl">
                        <span class="text-7xl">🐄</span>
                    </div>
                </div>
                <h2 class="text-5xl font-bold mb-4 drop-shadow-lg">DailyMoo</h2>
                <p class="text-xl text-white/90 max-w-md mx-auto leading-relaxed">
                    Platform digitalisasi peternakan sapi perah yang modern dan terpercaya
                </p>
                <div class="flex justify-center space-x-4 mt-8">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
