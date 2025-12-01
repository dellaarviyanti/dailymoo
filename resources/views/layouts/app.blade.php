<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DailyMoo - Digitalisasi Peternakan Sapi Perah')</title>
    
    <!-- Favicon DailyMoo -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5DB996',
                        'primary-dark': '#118B50',
                        secondary: '#E3F0AF',
                        'bg-cream': '#FBF6E9',
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .hover-scale {
            transition: transform 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        /* Custom Scrollbar for Sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-scroll::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 10px;
        }
        
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #5DB996;
            border-radius: 10px;
        }
        
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #118B50;
        }
        
        /* Hide elements with x-cloak until Alpine.js loads */
        [x-cloak] {
            display: none !important;
        }
        
        /* Sidebar always visible on desktop - override x-show */
        @media (min-width: 1024px) {
            aside > div:first-child[x-show] {
                display: flex !important;
                transform: translateX(0) !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
        }
        
        /* Mobile optimizations */
        @media (max-width: 1023px) {
            body {
                overflow-x: hidden;
            }
        }
        
        /* Ensure sidebar is above content on mobile */
        @media (max-width: 1023px) {
            aside {
                z-index: 9999;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-20 right-4 lg:right-6 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg fade-in">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-20 right-4 lg:right-6 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg fade-in">
            {{ session('error') }}
        </div>
    @endif
    
    <!-- Main Content & Footer Wrapper -->
    <div class="lg:ml-64 transition-all duration-300 flex flex-col min-h-screen pt-16 lg:pt-0" x-data="{ sidebarVisible: window.innerWidth >= 1024 }" x-init="
        window.addEventListener('resize', () => {
            sidebarVisible = window.innerWidth >= 1024;
        });
    ">
        <!-- Main Content -->
        <main class="flex-1">
            <div class="p-4 sm:p-6 lg:p-6">
                @yield('content')
            </div>
        </main>
        
        <!-- Footer -->
        @hasSection('footer')
            @yield('footer')
        @else
            @include('partials.footer')
        @endif
    </div>
    
    @stack('scripts')
</body>
</html>
