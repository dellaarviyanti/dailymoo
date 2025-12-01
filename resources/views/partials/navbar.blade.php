<nav x-data="{ mobileMenuOpen: false }" class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center group">
                <div class="w-8 h-8 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2 group-hover:scale-110 transition-transform duration-300">
                    <span class="text-white text-lg">🐄</span>
                </div>
                <span class="text-base font-semibold text-primary">DailyMoo</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}" class="relative px-2.5 py-1.5 text-sm font-medium transition-colors duration-300 {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                    Beranda
                    @if(request()->routeIs('home'))
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary rounded-full"></span>
                    @endif
                </a>
                
                @auth
                    <a href="{{ route('monitoring') }}" class="relative px-2.5 py-1.5 text-sm font-medium transition-colors duration-300 {{ request()->routeIs('monitoring') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                        @if(auth()->user()->role === 'pembeli')
                            Transaksi
                        @else
                            Monitoring
                        @endif
                        @if(request()->routeIs('monitoring'))
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary rounded-full"></span>
                        @endif
                    </a>
                    @if(in_array(auth()->user()->role, ['superadmin', 'pegawai']))
                        @php
                            $pendingCount = \App\Models\Transaction::where('status', 'payment_verification')->count();
                        @endphp
                        <a href="{{ route('payment.verification') }}" class="relative px-2.5 py-1.5 text-sm font-medium transition-colors duration-300 {{ request()->routeIs('payment.verification') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                            <span class="flex items-center">
                                Verifikasi Pembayaran
                                @if($pendingCount > 0)
                                    <span class="ml-2 px-2 py-0.5 text-xs bg-red-500 text-white rounded-full font-bold animate-pulse">{{ $pendingCount }}</span>
                                @endif
                            </span>
                            @if(request()->routeIs('payment.verification'))
                                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary rounded-full"></span>
                            @endif
                        </a>
                    @endif
                    @if(in_array(auth()->user()->role, ['superadmin', 'pegawai']))
                        <a href="{{ route('weight.index') }}" class="relative px-2.5 py-1.5 text-sm font-medium transition-colors duration-300 {{ request()->routeIs('weight*') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                            Atur Bobot Pakan
                            @if(request()->routeIs('weight*'))
                                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary rounded-full"></span>
                            @endif
                        </a>
                    @endif
                    @if(auth()->user()->role === 'superadmin')
                        <a href="{{ route('account.index') }}" class="relative px-2.5 py-1.5 text-sm font-medium transition-colors duration-300 {{ request()->routeIs('account*') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                            Kelola Akun
                            @if(request()->routeIs('account*'))
                                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary rounded-full"></span>
                            @endif
                        </a>
                    @endif
                @endauth
                
                <a href="{{ route('knowledge') }}" class="relative px-2.5 py-1.5 text-sm font-medium transition-colors duration-300 {{ request()->routeIs('knowledge') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                    MooKnowledge
                    @if(request()->routeIs('knowledge'))
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary rounded-full"></span>
                    @endif
                </a>
                
                <a href="{{ route('shop') }}" class="relative px-2.5 py-1.5 text-sm font-medium transition-colors duration-300 {{ request()->routeIs('shop') && !request()->routeIs('cart*') && !request()->routeIs('checkout') && !request()->routeIs('transactions*') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                    Shop
                    @if(request()->routeIs('shop') && !request()->routeIs('cart*') && !request()->routeIs('checkout') && !request()->routeIs('transactions*'))
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary rounded-full"></span>
                    @endif
                </a>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-3">
                @auth
                    <div class="px-3 py-1.5 bg-secondary text-primary rounded-lg capitalize text-xs font-medium">
                        {{ auth()->user()->role === 'superadmin' ? 'Superadmin' : ucfirst(auth()->user()->role) }}
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-all duration-300">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 text-xs font-medium border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-3 py-1.5 text-xs font-medium bg-primary text-white rounded-lg hover:bg-primary-dark transition-all duration-300 shadow-md hover:shadow-lg">
                        Register
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-700">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden py-3 border-t">
            <div class="space-y-1">
                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('home') ? 'text-primary bg-secondary' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                    Beranda
                </a>
                @auth
                    <a href="{{ route('monitoring') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('monitoring') ? 'text-primary bg-secondary' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        @if(auth()->user()->role === 'pembeli')
                            Transaksi
                        @else
                            Monitoring
                        @endif
                    </a>
                    @if(in_array(auth()->user()->role, ['superadmin', 'pegawai']))
                        <a href="{{ route('weight.index') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('weight*') ? 'text-primary bg-secondary' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                            Atur Bobot Pakan
                        </a>
                    @endif
                    @if(auth()->user()->role === 'superadmin')
                        <a href="{{ route('account.index') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('account*') ? 'text-primary bg-secondary' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                            Kelola Akun
                        </a>
                    @endif
                    @if(in_array(auth()->user()->role, ['superadmin', 'pegawai']))
                        @php
                            $pendingCount = \App\Models\Transaction::where('status', 'payment_verification')->count();
                        @endphp
                        <a href="{{ route('payment.verification') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('payment.verification') ? 'text-primary bg-secondary' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                            <span class="flex items-center">
                                Verifikasi Pembayaran
                                @if($pendingCount > 0)
                                    <span class="ml-2 px-2 py-0.5 text-xs bg-red-500 text-white rounded-full font-bold">{{ $pendingCount }}</span>
                                @endif
                            </span>
                        </a>
                    @endif
                @endauth
                <a href="{{ route('knowledge') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('knowledge') ? 'text-primary bg-secondary' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                    MooKnowledge
                </a>
                <a href="{{ route('shop') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('shop') && !request()->routeIs('cart*') && !request()->routeIs('checkout') && !request()->routeIs('transactions*') ? 'text-primary bg-secondary' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                    Shop
                </a>
                   </div>
            
            <div class="px-4 pt-3 border-t mt-3 space-y-2">
                @auth
                    <div class="px-3 py-1.5 bg-secondary text-primary rounded-lg capitalize text-center text-xs font-medium">
                        {{ auth()->user()->role === 'superadmin' ? 'Superadmin' : ucfirst(auth()->user()->role) }}
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-3 py-1.5 text-xs font-medium border border-red-300 text-red-600 rounded-lg hover:bg-red-50">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full px-3 py-1.5 text-xs font-medium border border-primary text-primary rounded-lg hover:bg-primary hover:text-white text-center transition-all">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block w-full px-3 py-1.5 text-xs font-medium bg-primary text-white rounded-lg hover:bg-primary-dark text-center transition-all">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
