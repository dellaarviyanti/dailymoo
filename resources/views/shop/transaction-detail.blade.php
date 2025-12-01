@extends('layouts.app')

@section('title', 'Detail Transaksi - DailyMoo')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('transactions') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Transaksi
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Detail Transaksi</h1>
            <p class="text-gray-600 mt-1">Transaksi #{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Status & Info --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $transaction->status_badge }}">
                                {{ $transaction->status_label }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">
                            {{ $transaction->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>

                    @if($transaction->status === 'processing')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <p class="text-green-800 font-semibold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pesanan diproses, tunggu WhatsApp untuk informasi pengiriman
                            </p>
                        </div>
                    @endif

                    {{-- Customer Info --}}
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Pengiriman</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium text-gray-700">Nama:</span> {{ $transaction->customer_name ?? $transaction->user->username }}</p>
                            <p><span class="font-medium text-gray-700">Telepon:</span> {{ $transaction->customer_phone ?? '-' }}</p>
                            <p><span class="font-medium text-gray-700">Alamat:</span> {{ $transaction->customer_address ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Products --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk yang Dipesan</h3>
                    <div class="space-y-4">
                        @foreach($transaction->items as $item)
                            <div class="flex items-center gap-4 pb-4 border-b border-gray-100 last:border-0">
                                <img 
                                    src="{{ $item->product->image_url }}" 
                                    alt="{{ $item->product->name }}"
                                    class="w-20 h-20 object-cover rounded-lg"
                                    onerror="this.src='https://placehold.co/80x80/F4F4F4/888888?text=DailyMoo'"
                                >
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-gray-600">
                                        {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <p class="text-lg font-bold text-primary">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Payment Proof Upload (for customer) --}}
                @if($transaction->user_id === auth()->id() && in_array($transaction->status, ['pending_payment', 'payment_verification']))
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Bukti Pembayaran</h3>
                        
                        @if($transaction->payment_proof)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Bukti pembayaran yang sudah diupload:</p>
                                <img 
                                    src="{{ $transaction->payment_proof_url }}" 
                                    alt="Bukti Pembayaran"
                                    class="max-w-md rounded-lg border border-gray-200 cursor-pointer hover:scale-105 transition-transform"
                                    onerror="this.src='https://placehold.co/400x300/F4F4F4/888888?text=Gambar+tidak+ditemukan'; this.onerror=null;"
                                    onclick="window.open('{{ $transaction->payment_proof_url }}', '_blank')"
                                >
                            </div>
                        @endif

                        @if($transaction->status === 'pending_payment')
                            <form action="{{ route('transactions.upload-payment', $transaction) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                            Upload Bukti Pembayaran <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="file" 
                                            name="payment_proof" 
                                            id="payment_proof" 
                                            accept="image/*"
                                            required
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark"
                                        >
                                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, maksimal 2MB</p>
                                    </div>
                                    <button 
                                        type="submit" 
                                        class="w-full px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-semibold transition-colors"
                                    >
                                        Upload Bukti Pembayaran
                                    </button>
                                </div>
                            </form>
                        @elseif($transaction->status === 'payment_verification')
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-yellow-800 text-sm">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Bukti pembayaran sedang diverifikasi oleh admin. Mohon tunggu.
                                </p>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Admin Actions --}}
                @if(in_array(auth()->user()->role, ['superadmin', 'pegawai']) && $transaction->status === 'payment_verification')
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Verifikasi Pembayaran</h3>
                        
                        @if($transaction->payment_proof)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Bukti pembayaran:</p>
                                @php
                                    // Use route-based URL (most reliable)
                                    $paymentProofUrl = route('payment.proof', $transaction->id);
                                    
                                    // Generate fallback URLs
                                    $paymentProofPath = $transaction->payment_proof;
                                    $fallbackUrls = [$paymentProofUrl];
                                    
                                    // Try Storage URL
                                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($paymentProofPath)) {
                                        $storageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($paymentProofPath);
                                        // Make sure URL is absolute
                                        if (!\Illuminate\Support\Str::startsWith($storageUrl, ['http://', 'https://'])) {
                                            $storageUrl = url($storageUrl);
                                        }
                                        $fallbackUrls[] = $storageUrl;
                                    }
                                    
                                    // Try asset path
                                    $assetUrl = asset('storage/' . ltrim($paymentProofPath, '/'));
                                    $fallbackUrls[] = $assetUrl;
                                    
                                    // Use route URL as primary
                                    $finalUrl = $paymentProofUrl;
                                @endphp
                                <div class="relative group">
                                    <img 
                                        src="{{ $finalUrl }}" 
                                        alt="Bukti Pembayaran"
                                        class="max-w-full md:max-w-md rounded-lg border-2 border-gray-200 shadow-md cursor-pointer hover:scale-105 transition-transform duration-300"
                                        onerror="
                                            const img = this;
                                            const fallbacks = @json($fallbackUrls);
                                            let currentIndex = fallbacks.indexOf(img.src);
                                            if (currentIndex === -1) currentIndex = 0;
                                            
                                            if (currentIndex < fallbacks.length - 1) {
                                                img.src = fallbacks[currentIndex + 1];
                                            } else {
                                                img.src = 'https://placehold.co/600x400/F4F4F4/888888?text=Gambar+tidak+dapat+dimuat';
                                                img.onerror = null;
                                            }
                                        "
                                        onclick="window.open('{{ $finalUrl }}', '_blank')"
                                    >
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-300 flex items-center justify-center pointer-events-none">
                                        <span class="opacity-0 group-hover:opacity-100 text-white bg-black bg-opacity-50 px-3 py-1 rounded text-sm transition-opacity duration-300">
                                            Klik untuk melihat ukuran penuh
                                        </span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    <a href="{{ $finalUrl }}" target="_blank" class="text-primary hover:underline">
                                        Buka di tab baru
                                    </a>
                                </p>
                            </div>
                        @endif

                        <div class="flex gap-3">
                            <form action="{{ route('transactions.approve-payment', $transaction) }}" method="POST">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors"
                                    onclick="return confirm('Setujui pembayaran ini?')"
                                >
                                    Setujui Pembayaran
                                </button>
                            </form>
                            <form action="{{ route('transactions.reject-payment', $transaction) }}" method="POST">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition-colors"
                                    onclick="return confirm('Tolak pembayaran ini? Stok produk akan dikembalikan.')"
                                >
                                    Tolak Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan</h3>
                    
                    <div class="space-y-3 mb-6">
                        @php
                            // Calculate subtotal from items
                            $subtotal = $transaction->items->sum('subtotal');
                            // Shipping fee (flat rate)
                            $shippingFee = 30000;
                            // Total should be subtotal + shipping
                            $calculatedTotal = $subtotal + $shippingFee;
                        @endphp
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <div class="flex flex-col">
                                <span class="text-gray-600">Ongkir</span>
                                <span class="text-xs text-gray-500 mt-0.5">Ongkir dibuat flat untuk mengefisienkan sistem pembelian</span>
                            </div>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-4">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-primary">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Bank Account Info --}}
                    @if(in_array($transaction->status, ['pending_payment', 'payment_verification']))
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Informasi Rekening</h4>
                            <div class="bg-secondary rounded-lg p-4 space-y-2 text-sm">
                                <p><span class="font-medium">Bank:</span> {{ $bankAccount['bank'] }}</p>
                                <p><span class="font-medium">No. Rekening:</span> {{ $bankAccount['account_number'] }}</p>
                                <p><span class="font-medium">Atas Nama:</span> {{ $bankAccount['account_name'] }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">
                                Silakan transfer sesuai dengan total pembayaran dan upload bukti transfer.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

