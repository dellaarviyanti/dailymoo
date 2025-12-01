@extends('layouts.app')

@section('title', 'DailyMoo - Digitalisasi Peternakan Sapi Perah')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-bg-cream via-white to-secondary/30 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8 fade-in">
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                    Digitalisasi Peternakan Sapi Perah dengan DailyMoo
                </h1>

                <p class="text-lg text-gray-600">
                    Sistem pemantauan dan pemberian pakan otomatis berbasis IoT untuk meningkatkan produktivitas dan efisiensi peternakan sapi perah Anda.
                </p>

                <div class="flex flex-wrap gap-4">
                    @auth
                        <a href="{{ route('knowledge') }}" class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-all duration-300 hover:shadow-xl">
                            Baca Selengkapnya
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-all duration-300 hover:shadow-xl">
                            Login
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        
                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border-2 border-primary text-primary rounded-lg font-medium hover:bg-primary hover:text-white transition-all duration-300">
                            Daftar Sekarang
                        </a>
                    @endauth
                </div>

                <div class="flex items-center gap-8 pt-8">
                    <div>
                        <p class="text-3xl font-bold text-primary">500+</p>
                        <p class="text-sm text-gray-600">Peternak Aktif</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-primary">24/7</p>
                        <p class="text-sm text-gray-600">Monitoring</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-primary">98%</p>
                        <p class="text-sm text-gray-600">Kepuasan</p>
                    </div>
                </div>
            </div>

            <div class="relative fade-in">
                <img src="https://images.pexels.com/photos/162801/cows-dairy-cows-milk-food-162801.jpeg" alt="Dairy Farming" class="rounded-2xl shadow-2xl hover-scale">
                <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-xl shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Real-time</p>
                            <p class="font-semibold text-gray-900">IoT Monitoring</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan DailyMoo</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Solusi lengkap untuk meningkatkan produktivitas peternakan dengan teknologi IoT
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-gradient-to-br from-bg-cream to-secondary/40 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 hover-scale">
                <div class="w-14 h-14 bg-primary rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Monitoring Real-time</h3>
                <p class="text-gray-600">
                    Pantau suhu, kelembapan, dan berat pakan secara real-time melalui sensor IoT canggih.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-gradient-to-br from-secondary/30 to-secondary/60 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 hover-scale">
                <div class="w-14 h-14 bg-primary-dark rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Pemberian Pakan Otomatis</h3>
                <p class="text-gray-600">
                    Robot pemberi pakan otomatis memastikan sapi mendapat nutrisi tepat waktu dan terukur.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-gradient-to-br from-primary/10 to-primary/20 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 hover-scale">
                <div class="w-14 h-14 bg-primary rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Analisis Data</h3>
                <p class="text-gray-600">
                    Dapatkan insight mendalam tentang produktivitas dan kesehatan sapi dengan analisis data historis.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-primary to-primary-dark text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
        <h2 class="text-3xl md:text-4xl font-bold text-white">
            Siap Meningkatkan Produktivitas Peternakan Anda?
        </h2>
        
        <p class="text-secondary text-lg">
            Bergabunglah dengan ratusan peternak yang telah merasakan manfaat DailyMoo
        </p>

        <div class="flex flex-wrap gap-4 justify-center">
            @auth
                <a href="{{ route('knowledge') }}" class="inline-flex items-center px-8 py-4 bg-white text-primary rounded-lg font-medium hover:bg-gray-100 transition-all duration-300 hover:shadow-xl">
                    Baca Selengkapnya
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white text-primary rounded-lg font-medium hover:bg-gray-100 transition-all duration-300 hover:shadow-xl">
                    Daftar Sekarang
                </a>
            @endauth
            
            <a href="{{ route('shop') }}" class="inline-flex items-center px-8 py-4 border-2 border-white text-white rounded-lg font-medium hover:bg-white hover:text-primary transition-all duration-300">
                Lihat Produk
            </a>
        </div>
    </div>
</section>
@endsection
