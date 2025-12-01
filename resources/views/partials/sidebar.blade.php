<aside x-data="{ 
    mobileMenuOpen: false,
    isDesktop: window.innerWidth >= 1024
}" 
x-init="
    // Update isDesktop on resize
    const updateDesktop = () => {
        isDesktop = window.innerWidth >= 1024;
        if (isDesktop) {
            mobileMenuOpen = false;
            document.body.style.overflow = '';
        }
    };
    
    $watch('mobileMenuOpen', value => {
        if (value && !isDesktop) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    });
    
    // Close mobile menu on resize to desktop
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            updateDesktop();
        }, 100);
    });
"
class="fixed inset-y-0 left-0 z-50 flex flex-col">
    <!-- Sidebar -->
    <div 
        x-show="isDesktop || mobileMenuOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full lg:translate-x-0"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full lg:translate-x-0"
        @click.away="if (!isDesktop) { mobileMenuOpen = false; document.body.style.overflow = ''; }"
        class="w-64 bg-gradient-to-b from-white via-white to-gray-50 shadow-2xl border-r border-gray-200 flex flex-col h-full overflow-y-auto sidebar-scroll z-[9999] lg:translate-x-0 lg:shadow-none">
        
        <!-- Logo Section -->
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-primary/5 to-secondary/30">
            <a href="{{ route('home') }}" 
               @click="if (window.innerWidth < 1024) { mobileMenuOpen = false; document.body.style.overflow = ''; }"
               class="flex items-center group">
                <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <span class="text-white text-2xl">🐄</span>
                </div>
                <div>
                    <span class="text-xl font-bold text-primary block">DailyMoo</span>
                    <span class="text-xs text-gray-500">Peternakan Cerdas</span>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <!-- Beranda -->
            <a href="{{ route('home') }}" 
               @click="if (window.innerWidth < 1024) { mobileMenuOpen = false; document.body.style.overflow = ''; }"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('home') ? 'bg-gradient-to-r from-primary to-primary-dark text-white shadow-lg shadow-primary/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-primary/10 hover:to-secondary/20 hover:text-primary' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('home') ? 'text-white' : 'text-gray-500 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Beranda</span>
                @if(request()->routeIs('home'))
                    <span class="ml-auto w-2 h-2 bg-white rounded-full"></span>
                @endif
            </a>

            @auth
                <!-- Monitoring / Transaksi -->
                <a href="{{ route('monitoring') }}" 
                   @click="if (window.innerWidth < 1024) { mobileMenuOpen = false; document.body.style.overflow = ''; }"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('monitoring') ? 'bg-gradient-to-r from-primary to-primary-dark text-white shadow-lg shadow-primary/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-primary/10 hover:to-secondary/20 hover:text-primary' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('monitoring') ? 'text-white' : 'text-gray-500 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>{{ auth()->user()->role === 'pembeli' ? 'Transaksi' : 'Monitoring' }}</span>
                    @if(request()->routeIs('monitoring'))
                        <span class="ml-auto w-2 h-2 bg-white rounded-full"></span>
                    @endif
                </a>

                @if(in_array(auth()->user()->role, ['superadmin', 'pegawai']))
                    @php
                        $pendingCount = \App\Models\Transaction::where('status', 'payment_verification')->count();
                    @endphp
                    <!-- Verifikasi Pembayaran -->
                    <a href="{{ route('payment.verification') }}" 
                       @click="if (window.innerWidth < 1024) { mobileMenuOpen = false; document.body.style.overflow = ''; }"
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('payment.verification') ? 'bg-gradient-to-r from-primary to-primary-dark text-white shadow-lg shadow-primary/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-primary/10 hover:to-secondary/20 hover:text-primary' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('payment.verification') ? 'text-white' : 'text-gray-500 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="flex-1">Verifikasi Pembayaran</span>
                        @if($pendingCount > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs bg-red-500 text-white rounded-full font-bold animate-pulse">{{ $pendingCount }}</span>
                        @endif
                        @if(request()->routeIs('payment.verification'))
                            <span class="ml-2 w-2 h-2 bg-white rounded-full"></span>
                        @endif
                    </a>

                    <!-- Atur Bobot Pakan -->
                    <a href="{{ route('weight.index') }}" 
                       @click="if (window.innerWidth < 1024) { mobileMenuOpen = false; document.body.style.overflow = ''; }"
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('weight*') ? 'bg-gradient-to-r from-primary to-primary-dark text-white shadow-lg shadow-primary/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-primary/10 hover:to-secondary/20 hover:text-primary' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('weight*') ? 'text-white' : 'text-gray-500 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                        <span>Atur Bobot Pakan</span>
                        @if(request()->routeIs('weight*'))
                            <span class="ml-auto w-2 h-2 bg-white rounded-full"></span>
                        @endif
                    </a>
                @endif

                @if(auth()->user()->role === 'superadmin')
                    <!-- Kelola Akun -->
                    <a href="{{ route('account.index') }}" 
                       @click="if (window.innerWidth < 1024) { mobileMenuOpen = false; document.body.style.overflow = ''; }"
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('account*') ? 'bg-gradient-to-r from-primary to-primary-dark text-white shadow-lg shadow-primary/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-primary/10 hover:to-secondary/20 hover:text-primary' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('account*') ? 'text-white' : 'text-gray-500 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Kelola Akun</span>
                        @if(request()->routeIs('account*'))
                            <span class="ml-auto w-2 h-2 bg-white rounded-full"></span>
                        @endif
                    </a>
                @endif
            @endauth

            <!-- MooKnowledge -->
            <a href="{{ route('knowledge') }}" 
               @click="if (window.innerWidth < 1024) { mobileMenuOpen = false; document.body.style.overflow = ''; }"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('knowledge') ? 'bg-gradient-to-r from-primary to-primary-dark text-white shadow-lg shadow-primary/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-primary/10 hover:to-secondary/20 hover:text-primary' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('knowledge') ? 'text-white' : 'text-gray-500 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>MooKnowledge</span>
                @if(request()->routeIs('knowledge'))
                    <span class="ml-auto w-2 h-2 bg-white rounded-full"></span>
                @endif
            </a>

            <!-- Shop -->
            <a href="{{ route('shop') }}" 
               @click="if (window.innerWidth < 1024) { mobileMenuOpen = false; document.body.style.overflow = ''; }"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('shop') && !request()->routeIs('cart*') && !request()->routeIs('checkout') && !request()->routeIs('transactions*') ? 'bg-gradient-to-r from-primary to-primary-dark text-white shadow-lg shadow-primary/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-primary/10 hover:to-secondary/20 hover:text-primary' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('shop') && !request()->routeIs('cart*') && !request()->routeIs('checkout') && !request()->routeIs('transactions*') ? 'text-white' : 'text-gray-500 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Shop</span>
                @if(request()->routeIs('shop') && !request()->routeIs('cart*') && !request()->routeIs('checkout') && !request()->routeIs('transactions*'))
                    <span class="ml-auto w-2 h-2 bg-white rounded-full"></span>
                @endif
            </a>
        </nav>

        <!-- User Section -->
        <div class="p-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            @auth
                <div class="mb-3 px-4 py-2 bg-gradient-to-r from-secondary/50 to-primary/10 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-3 shadow-md">
                            <span class="text-white text-sm font-bold">{{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->username ?? 'User' }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role === 'superadmin' ? 'Superadmin' : ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 hover:border-red-300 transition-all duration-300 group">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            @else
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="block w-full px-4 py-2.5 text-sm font-medium text-center border border-primary text-primary rounded-xl hover:bg-primary hover:text-white transition-all duration-300">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block w-full px-4 py-2.5 text-sm font-medium text-center bg-gradient-to-r from-primary to-primary-dark text-white rounded-xl hover:shadow-lg hover:shadow-primary/30 transition-all duration-300">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div 
        x-show="mobileMenuOpen"
        class="lg:hidden"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 z-[9998] lg:hidden"
        @click="mobileMenuOpen = false; document.body.style.overflow = '';">
    </div>

    <!-- Mobile Toggle Button -->
    <button 
        @click="mobileMenuOpen = !mobileMenuOpen"
        :aria-expanded="mobileMenuOpen"
        aria-label="Toggle menu"
        class="lg:hidden fixed top-4 left-4 z-50 p-2.5 bg-white rounded-lg shadow-lg border border-gray-200 hover:bg-gray-50 active:bg-gray-100 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
        <svg x-show="!mobileMenuOpen" x-cloak class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</aside>

