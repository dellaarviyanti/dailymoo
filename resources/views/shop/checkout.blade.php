@extends('layouts.app')

@section('title', 'Checkout - DailyMoo')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
            <p class="text-gray-600 mt-1">Lengkapi informasi pengiriman Anda</p>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @if(isset($selectedItems) && !empty($selectedItems))
                <input type="hidden" name="selected_items" value="{{ implode(',', $selectedItems) }}">
            @elseif(request()->has('selected_items'))
                <input type="hidden" name="selected_items" value="{{ request()->selected_items }}">
            @endif

            {{-- Form Data Pengiriman --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Data Pengiriman</h2>

                    <div class="space-y-4">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="customer_name" 
                                id="customer_name" 
                                value="{{ old('customer_name', auth()->user()->username ?? '') }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('customer_name') border-red-500 @enderror"
                            >
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                No. Telepon <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="customer_phone" 
                                id="customer_phone" 
                                value="{{ old('customer_phone') }}"
                                required
                                placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('customer_phone') border-red-500 @enderror"
                            >
                            @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="customer_address" 
                                id="customer_address" 
                                rows="4"
                                required
                                placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('customer_address') border-red-500 @enderror"
                            >{{ old('customer_address') }}</textarea>
                            @error('customer_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Ringkasan Produk --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Produk yang Dipesan</h2>
                    <div class="space-y-4">
                        @foreach($cartItems as $productId => $item)
                            <div class="flex items-center gap-4 pb-4 border-b border-gray-100 last:border-0">
                                <img 
                                    src="{{ $item['product']->image_url }}" 
                                    alt="{{ $item['product']->name }}"
                                    class="w-16 h-16 object-cover rounded-lg"
                                    onerror="this.src='https://placehold.co/64x64/F4F4F4/888888?text=DailyMoo'"
                                >
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $item['product']->name }}</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $item['quantity'] }} x Rp {{ number_format($item['product']->price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <p class="text-lg font-bold text-primary">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Ringkasan Pesanan --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($subtotal ?? ($total - ($shippingFee ?? 30000)), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <div class="flex flex-col">
                                <span class="text-gray-600">Ongkir</span>
                                <span class="text-xs text-gray-500 mt-0.5">Ongkir dibuat flat untuk mengefisienkan sistem pembelian</span>
                            </div>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($shippingFee ?? 30000, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl"
                    >
                        Buat Pesanan
                    </button>

                    <a href="{{ route('cart.index') }}" class="block w-full text-center mt-4 text-sm text-gray-600 hover:text-primary transition-colors">
                        Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

