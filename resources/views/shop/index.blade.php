@extends('layouts.app')

@section('title', 'DailyMoo Shop - Produk Peternakan')

@section('content')
@php
    $isAdmin = auth()->check() && in_array(auth()->user()->role, ['superadmin', 'pegawai']);
    $productFormContext = old('form_context');
    $editingProductId = $productFormContext === 'productUpdate' ? old('product_id') : null;
    $categoryOptions = $categoryOptions ?? [];
@endphp

<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-primary to-primary-dark text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">DailyMoo Shop</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">
                Temukan produk berkualitas untuk kebutuhan peternakan sapi perah Anda
            </p>
        </div>
    </section>

    <!-- Admin Dashboard Panel -->
    @if($isAdmin && $adminData)
        <section class="py-8 bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Dashboard Shop</h2>
                    <div class="flex items-center gap-3">
                        <a 
                            href="{{ route('transactions') }}" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Riwayat Penjualan
                        </a>
                        <button 
                            id="toggle-admin-panel" 
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-medium"
                            onclick="toggleAdminPanel()"
                        >
                            <span id="toggle-text">Sembunyikan</span> Panel
                        </button>
                    </div>
                </div>
                
                <div id="admin-panel" class="space-y-6">
                    {{-- Stats Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Total Produk</p>
                                    <p class="text-3xl font-bold mt-2">{{ $adminData['totalProducts'] }}</p>
                                </div>
                                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium">Penjualan Hari Ini</p>
                                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($adminData['todaySales'], 0, ',', '.') }}</p>
                                </div>
                                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Penjualan Bulan Ini</p>
                                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($adminData['monthSales'], 0, ',', '.') }}</p>
                                    <p class="text-purple-200 text-xs mt-1">{{ $adminData['monthCompletedTransactions'] ?? 0 }} transaksi</p>
                                </div>
                                <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl p-6 shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-orange-100 text-sm font-medium">Stok Rendah</p>
                                    <p class="text-3xl font-bold mt-2">{{ $adminData['lowStockProducts'] }}</p>
                                    @if($adminData['outOfStockProducts'] > 0)
                                        <p class="text-orange-200 text-xs mt-1">{{ $adminData['outOfStockProducts'] }} habis</p>
                                    @endif
                                </div>
                                <svg class="w-12 h-12 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Charts Row --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Daily Sales Chart --}}
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Penjualan 7 Hari Terakhir</h3>
                            <canvas id="dailySalesChart" height="100"></canvas>
                        </div>
                        
                        {{-- Monthly Sales Chart --}}
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Penjualan 12 Bulan Terakhir</h3>
                            <canvas id="monthlySalesChart" height="100"></canvas>
                        </div>
                    </div>

                    {{-- Best Selling Products & Low Stock --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Best Selling Products --}}
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk Terlaris</h3>
                            <div class="space-y-3">
                                @php
                                    $validProducts = collect($adminData['bestSellingProducts'])->filter(function($item) {
                                        return $item->product !== null;
                                    });
                                @endphp
                                @forelse($validProducts as $index => $item)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <span class="flex items-center justify-center w-8 h-8 bg-primary text-white rounded-full font-bold text-sm">
                                                {{ $index + 1 }}
                                            </span>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                                <p class="text-sm text-gray-500">Terjual: {{ $item->total_sold }} unit</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">Belum ada data penjualan</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Low Stock Products --}}
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Produk Stok Rendah</h3>
                                @if($adminData['lowStockProducts'] > 0)
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                        {{ $adminData['lowStockProducts'] }} produk
                                    </span>
                                @endif
                            </div>
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @forelse($adminData['lowStockProductsList'] as $product)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                            <p class="text-sm text-gray-500">Stok: <span class="font-semibold {{ $product->stock == 0 ? 'text-red-600' : 'text-orange-600' }}">{{ $product->stock }}</span></p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input 
                                                type="number" 
                                                value="{{ $product->stock }}" 
                                                min="0"
                                                class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                                                data-product-id="{{ $product->id }}"
                                                onchange="quickUpdateStock({{ $product->id }}, this.value)"
                                            >
                                            <button 
                                                onclick="quickUpdateStock({{ $product->id }}, document.querySelector('[data-product-id=\'{{ $product->id }}\']').value)"
                                                class="px-3 py-1 bg-primary text-white rounded text-sm hover:bg-primary-dark transition-colors"
                                            >
                                                Update
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">Semua produk memiliki stok cukup</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('payment.verification') }}" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors font-medium">
                                Verifikasi Pembayaran 
                                @if($adminData['pendingVerifications'] > 0)
                                    <span class="ml-2 px-2 py-0.5 bg-red-500 rounded-full text-xs">{{ $adminData['pendingVerifications'] }}</span>
                                @endif
                            </a>
                            <button 
                                onclick="scrollToAddProductForm()"
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium"
                            >
                                Tambah Produk Baru
                            </button>
                            <a href="{{ route('transactions') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium">
                                Lihat Semua Transaksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Products Section -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter & Search -->
            <div class="flex flex-col md:flex-row gap-4 mb-8">
    <!-- Search -->
    <form method="GET" action="{{ route('shop') }}" class="flex-1">
        <div class="relative">
            <input
                type="text"
                name="search"
                placeholder="Cari produk..."
                value="{{ request('search') }}"
                class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
            />
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </form>

    <!-- Filter kategori -->
<form method="GET" action="{{ route('shop') }}">
    {{-- biar filter lain (search & sort) tetap keikut --}}
    <input type="hidden" name="search" value="{{ request('search') }}">
    <input type="hidden" name="sort" value="{{ request('sort') }}">

    <select name="category" onchange="this.form.submit()"
        class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
        
        {{-- Opsi default --}}
        <option value="">Semua Kategori</option>

        {{-- Daftar kategori tanpa "All" --}}
        @foreach($categories as $cat)
            @if(strtolower($cat) !== 'all')
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                    {{ ucfirst($cat) }}
                </option>
            @endif
        @endforeach
    </select>
</form>

<!-- Sort harga -->
<form method="GET" action="{{ route('shop') }}">
    <!-- biar filter lain (seperti kategori & search) nggak hilang -->
    <input type="hidden" name="search" value="{{ request('search') }}">
    <input type="hidden" name="category" value="{{ request('category') }}">

    <select name="sort" onchange="this.form.submit()" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
        <option value="">Urutkan Harga</option>
        <option value="lowest" {{ request('sort') == 'lowest' ? 'selected' : '' }}>Tertinggi</option>
        <option value="highest" {{ request('sort') == 'highest' ? 'selected' : '' }}>Terendah</option>
    </select>
</form>

            </div>

            @if($isAdmin)
                <div id="add-product-form" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-10">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                        <div>
                            <p class="text-sm uppercase tracking-widest text-primary font-semibold">Admin</p>
                            <h2 class="text-2xl font-bold text-gray-900 mt-1">Tambah Produk Baru</h2>
                            <p class="text-gray-600">Kelola katalog langsung dari halaman Shop.</p>
                        </div>
                    </div>

                    @if ($errors->productStore->any())
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-600 px-4 py-3 text-sm">
                            Form tambah produk memiliki {{ $errors->productStore->count() }} error.
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        <input type="hidden" name="form_context" value="productStore">

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                            <input type="text" name="name" value="{{ $productFormContext === 'productStore' ? old('name') : '' }}" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                            @error('name', 'productStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                            <input type="number" name="price" min="0" step="100" value="{{ $productFormContext === 'productStore' ? old('price') : '' }}" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                            @error('price', 'productStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                            <input type="number" name="stock" min="0" value="{{ $productFormContext === 'productStore' ? old('stock') : '' }}" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                            @error('stock', 'productStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <input list="product-categories" type="text" name="category" value="{{ $productFormContext === 'productStore' ? old('category') : '' }}" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" placeholder="Contoh: Minuman">
                            <datalist id="product-categories">
                                @foreach ($categoryOptions as $option)
                                    <option value="{{ $option }}"></option>
                                @endforeach
                            </datalist>
                            @error('category', 'productStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar</label>
                            <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark file:cursor-pointer">
                            <p class="text-xs text-gray-500 mt-1">Pilih salah satu: Upload file ATAU masukkan URL</p>
                            @error('image', 'productStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">atau URL Gambar</label>
                            <input type="url" name="image_url" value="{{ $productFormContext === 'productStore' ? old('image_url') : '' }}" placeholder="https://..." class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                            @error('image_url', 'productStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none">{{ $productFormContext === 'productStore' ? old('description') : '' }}</textarea>
                            @error('description', 'productStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-white font-semibold hover:bg-primary-dark transition-all">
                                Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    @php
                        $isEditingProduct = $isAdmin && $editingProductId == $product->id;
                        $productUpdateActive = $isEditingProduct && $productFormContext === 'productUpdate';
                    @endphp
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        <div class="aspect-square bg-gray-100 overflow-hidden">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        </div>
                        
                        <div class="p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-1 bg-secondary text-primary-dark text-xs rounded-full">
                                    {{ $product->category ?? 'Umum' }}
                                </span>
                                <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Stock: {{ $product->stock }}
                                </span>
                            </div>
                            
                            <h3 class="font-semibold text-gray-900">{{ $product->name }}</h3>
                            
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $product->description }}</p>
                            
                            <div class="pt-4 border-t border-gray-100">
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 mb-1">Harga</p>
                                    <span class="text-2xl font-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($product->stock > 0)
                                    @if(!in_array(auth()->user()->role ?? null, ['superadmin', 'pegawai']))
                                        <div class="flex flex-col gap-2">
                                            <button 
                                                type="button"
                                                onclick="addToCart({{ $product->id }}, {{ json_encode($product->name) }})"
                                                class="w-full group relative overflow-hidden px-4 py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl active:scale-[0.98]"
                                            >
                                                <span class="relative z-10 flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                    </svg>
                                                    <span>Tambah Keranjang</span>
                                                </span>
                                                <span class="absolute inset-0 bg-gradient-to-r from-primary-dark to-primary opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                            </button>
                                            @auth
                                                <form action="{{ route('checkout') }}" method="GET">
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="w-full group relative overflow-hidden px-4 py-3 bg-white border-2 border-primary text-primary rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-[1.02] hover:bg-primary hover:text-white hover:shadow-xl active:scale-[0.98]">
                                                        <span class="relative z-10 flex items-center justify-center gap-2">
                                                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                            </svg>
                                                            <span>Beli Sekarang</span>
                                                        </span>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('login') }}" class="w-full group relative overflow-hidden px-4 py-3 bg-white border-2 border-primary text-primary rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-[1.02] hover:bg-primary hover:text-white hover:shadow-xl active:scale-[0.98] inline-flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                    <span>Beli Sekarang</span>
                                                </a>
                                            @endauth
                                        </div>
                                    @else
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl">
                                            <p class="text-sm text-gray-600 text-center">
                                                <span class="font-medium">Stok:</span> 
                                                <span class="text-primary font-semibold">{{ $product->stock }}</span>
                                            </p>
                                        </div>
                                    @endif
                                @else
                                    <div class="px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-center">
                                        <p class="text-sm text-gray-500 font-medium flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span>Stok Habis</span>
                                        </p>
                                    </div>
                                @endif
                            </div>

                            @if($isAdmin)
                                <div x-data="{ editing: {{ $isEditingProduct ? 'true' : 'false' }} }" class="mt-6 border-t border-gray-100 pt-4">
                                    <div class="flex items-center justify-between">
                                        <button type="button" @click="editing = !editing" class="px-4 py-2 rounded-lg border border-primary text-primary hover:bg-primary hover:text-white transition">
                                            <span x-text="editing ? 'Tutup Editor' : 'Edit Produk'"></span>
                                        </button>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')" >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                    <div x-show="editing" x-transition class="mt-4 bg-gray-50 rounded-xl p-4 space-y-4">
                                        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="form_context" value="productUpdate">
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                                                <input type="text" name="name" value="{{ $productUpdateActive ? old('name') : $product->name }}" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                                @if ($productUpdateActive && $errors->productUpdate->has('name'))
                                                    <p class="text-sm text-red-500">{{ $errors->productUpdate->first('name') }}</p>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">Harga</label>
                                                    <input type="number" name="price" min="0" step="100" value="{{ $productUpdateActive ? old('price') : $product->price }}" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                                    @if ($productUpdateActive && $errors->productUpdate->has('price'))
                                                        <p class="text-sm text-red-500">{{ $errors->productUpdate->first('price') }}</p>
                                                    @endif
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">Stok</label>
                                                    <input type="number" name="stock" min="0" value="{{ $productUpdateActive ? old('stock') : $product->stock }}" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                                    @if ($productUpdateActive && $errors->productUpdate->has('stock'))
                                                        <p class="text-sm text-red-500">{{ $errors->productUpdate->first('stock') }}</p>
                                                    @endif
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                                    <input list="product-categories" type="text" name="category" value="{{ $productUpdateActive ? old('category') : $product->category }}" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                                    @if ($productUpdateActive && $errors->productUpdate->has('category'))
                                                        <p class="text-sm text-red-500">{{ $errors->productUpdate->first('category') }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                                <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none">{{ $productUpdateActive ? old('description') : $product->description }}</textarea>
                                                @if ($productUpdateActive && $errors->productUpdate->has('description'))
                                                    <p class="text-sm text-red-500">{{ $errors->productUpdate->first('description') }}</p>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">Upload Gambar</label>
                                                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark file:cursor-pointer">
                                                    <p class="text-xs text-gray-500">Pilih salah satu: Upload file ATAU masukkan URL</p>
                                                    @if ($productUpdateActive && $errors->productUpdate->has('image'))
                                                        <p class="text-sm text-red-500">{{ $errors->productUpdate->first('image') }}</p>
                                                    @endif
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">atau URL Gambar</label>
                                                    <input type="url" name="image_url" value="{{ $productUpdateActive ? old('image_url') : '' }}" placeholder="https://..." class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                                    @if ($productUpdateActive && $errors->productUpdate->has('image_url'))
                                                        <p class="text-sm text-red-500">{{ $errors->productUpdate->first('image_url') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($product->image && !\Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://']))
                                                <div class="text-xs text-gray-500 bg-gray-50 p-2 rounded">
                                                    <strong>Gambar saat ini:</strong> {{ $product->image }}
                                                </div>
                                            @endif

                                            <div class="flex items-center justify-end gap-3">
                                                <button type="button" @click="editing = false" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-white">
                                                    Batal
                                                </button>
                                                <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-white font-semibold hover:bg-primary-dark">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">Tidak ada produk ditemukan</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </section>
</div>

{{-- Floating Cart Button (Sticky - hanya di halaman shop) --}}
@if(!in_array(auth()->user()->role ?? null, ['superadmin', 'pegawai']))
    @php
        $cartCount = count(Session::get('cart', []));
    @endphp
    <a 
        href="{{ route('cart.index') }}" 
        id="floating-cart-button"
        class="fixed bottom-8 right-8 z-50 flex items-center justify-center w-16 h-16 bg-primary hover:bg-primary-dark text-white rounded-full shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-110 group animate-bounce-subtle"
        title="Keranjang Belanja ({{ $cartCount }} item)"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        @if($cartCount > 0)
            <span id="cart-badge" class="absolute -top-1 -right-1 flex items-center justify-center min-w-[24px] h-6 px-2 bg-red-500 text-white text-xs font-bold rounded-full border-2 border-white shadow-lg">
                {{ $cartCount > 99 ? '99+' : $cartCount }}
            </span>
        @else
            <span id="cart-badge" class="absolute -top-1 -right-1 flex items-center justify-center min-w-[24px] h-6 px-2 bg-red-500 text-white text-xs font-bold rounded-full border-2 border-white shadow-lg hidden">
                0
            </span>
        @endif
        {{-- Pulse animation when cart has items --}}
        @if($cartCount > 0)
            <span id="cart-pulse" class="absolute inset-0 rounded-full bg-primary animate-ping opacity-20"></span>
        @else
            <span id="cart-pulse" class="absolute inset-0 rounded-full bg-primary animate-ping opacity-20 hidden"></span>
        @endif
    </a>
    
    <style>
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .animate-bounce-subtle {
            animation: bounce-subtle 2s ease-in-out infinite;
        }
    </style>
@endif

{{-- Toast Notification --}}
<div id="cart-toast" class="fixed top-20 right-4 z-[10000] transform translate-x-full transition-transform duration-300 ease-in-out">
    <div class="bg-white rounded-lg shadow-2xl border border-gray-200 p-4 min-w-[300px] max-w-md">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <div id="toast-icon" class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-900" id="toast-title">Berhasil!</p>
                <p class="text-sm text-gray-600 mt-1" id="toast-message">Produk berhasil ditambahkan ke keranjang</p>
            </div>
            <button onclick="hideToast()" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
// Function to add product to cart via AJAX
function addToCart(productId, productName) {
    // Show loading state
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="relative z-10 flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Menambahkan...</span></span>';

    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show toast notification
            showToast('Berhasil!', data.message || 'Produk berhasil ditambahkan ke keranjang');
            
            // Update cart count
            updateCartCount(data.cart_count);
        } else {
            // Show error toast
            showToast('Error', data.message || 'Gagal menambahkan produk ke keranjang', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'Terjadi kesalahan saat menambahkan produk', 'error');
    })
    .finally(() => {
        // Restore button
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

// Function to show toast notification
function showToast(title, message, type = 'success') {
    const toast = document.getElementById('cart-toast');
    const toastTitle = document.getElementById('toast-title');
    const toastMessage = document.getElementById('toast-message');
    const toastIcon = document.getElementById('toast-icon');
    
    toastTitle.textContent = title;
    toastMessage.textContent = message;
    
    // Change icon based on type
    if (type === 'error') {
        toastIcon.className = 'w-10 h-10 bg-red-100 rounded-full flex items-center justify-center';
        toastIcon.innerHTML = '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
    } else {
        toastIcon.className = 'w-10 h-10 bg-green-100 rounded-full flex items-center justify-center';
        toastIcon.innerHTML = '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
    }
    
    // Show toast
    toast.classList.remove('translate-x-full');
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        hideToast();
    }, 3000);
}

// Function to hide toast
function hideToast() {
    const toast = document.getElementById('cart-toast');
    toast.classList.add('translate-x-full');
}

// Function to update cart count
function updateCartCount(count) {
    // Update floating cart button badge
    const cartBadge = document.getElementById('cart-badge');
    const cartPulse = document.getElementById('cart-pulse');
    const cartButton = document.getElementById('floating-cart-button');
    
    if (cartBadge) {
        if (count > 0) {
            cartBadge.textContent = count > 99 ? '99+' : count;
            cartBadge.classList.remove('hidden');
            if (cartPulse) {
                cartPulse.classList.remove('hidden');
            }
        } else {
            cartBadge.classList.add('hidden');
            if (cartPulse) {
                cartPulse.classList.add('hidden');
            }
        }
    }
    
    // Update cart button title
    if (cartButton) {
        cartButton.setAttribute('title', `Keranjang Belanja (${count} item)`);
    }
}
</script>

@if($isAdmin && $adminData)
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Toggle Admin Panel
function toggleAdminPanel() {
    const panel = document.getElementById('admin-panel');
    const toggleText = document.getElementById('toggle-text');
    
    if (panel.style.display === 'none') {
        panel.style.display = 'block';
        toggleText.textContent = 'Sembunyikan';
    } else {
        panel.style.display = 'none';
        toggleText.textContent = 'Tampilkan';
    }
}

// Scroll to Add Product Form
function scrollToAddProductForm() {
    const form = document.getElementById('add-product-form');
    if (form) {
        const headerOffset = 100; // Offset untuk header sticky
        const elementPosition = form.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
        
        // Highlight form dengan border
        form.style.border = '2px solid #3b82f6';
        form.style.transition = 'border 0.3s';
        setTimeout(() => {
            form.style.border = '';
        }, 2000);
    }
}

// Quick Update Stock
function quickUpdateStock(productId, stock) {
    if (stock < 0) {
        alert('Stok tidak boleh negatif');
        return;
    }

    fetch(`/products/${productId}/quick-update-stock`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            stock: parseInt(stock)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the input value
            const input = document.querySelector(`[data-product-id="${productId}"]`);
            if (input) {
                input.value = data.stock;
            }
            
            // Show success message
            const message = document.createElement('div');
            message.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            message.textContent = 'Stok berhasil diperbarui!';
            document.body.appendChild(message);
            
            setTimeout(() => {
                message.remove();
            }, 3000);
            
            // Reload page after 1 second to update stats
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            alert(data.message || 'Gagal memperbarui stok');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui stok');
    });
}

// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    @if($isAdmin && $adminData)
    // Daily Sales Chart
    const dailyCtx = document.getElementById('dailySalesChart');
    if (dailyCtx) {
        const salesData = @json($adminData['salesData']);
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: salesData.map(item => item.date),
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: salesData.map(item => parseFloat(item.sales)),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + Math.round(context.parsed.y).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'Rb';
                                }
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Monthly Sales Chart
    const monthlyCtx = document.getElementById('monthlySalesChart');
    if (monthlyCtx) {
        const monthlyData = @json($adminData['monthlySalesData']);
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: monthlyData.map(item => parseFloat(item.sales)),
                    backgroundColor: 'rgba(139, 92, 246, 0.8)',
                    borderColor: 'rgb(139, 92, 246)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + Math.round(context.parsed.y).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'Rb';
                                }
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endif

@endsection
