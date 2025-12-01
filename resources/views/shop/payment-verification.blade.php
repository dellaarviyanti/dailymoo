@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - DailyMoo')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-widest text-primary font-semibold">Admin</p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Verifikasi Pembayaran</h1>
                    <p class="text-gray-600 mt-1">Verifikasi bukti pembayaran dari pembeli</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg font-semibold">
                        {{ $pendingVerifications->total() }} Menunggu Verifikasi
                    </span>
                </div>
            </div>
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

        {{-- Pending Verifications List --}}
        @if($pendingVerifications->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Pembayaran yang Perlu Diverifikasi</h3>
                <p class="text-gray-600">Semua pembayaran sudah diverifikasi.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($pendingVerifications as $transaction)
                    <div class="bg-white rounded-2xl shadow-sm border-2 border-orange-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Transaksi #{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}
                                        </h3>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-700">
                                            Menunggu Verifikasi
                                        </span>
                                    </div>
                                    <div class="space-y-1 text-sm text-gray-600">
                                        <p><span class="font-medium">Pembeli:</span> {{ $transaction->user->username }} ({{ $transaction->user->email }})</p>
                                        <p><span class="font-medium">Nama:</span> {{ $transaction->customer_name }}</p>
                                        <p><span class="font-medium">Telepon:</span> {{ $transaction->customer_phone }}</p>
                                        <p><span class="font-medium">Alamat:</span> {{ $transaction->customer_address }}</p>
                                        <p><span class="font-medium">Tanggal:</span> {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <p class="text-2xl font-bold text-primary mb-2">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Payment Proof --}}
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Bukti Pembayaran:</h4>
                                @if($transaction->payment_proof)
                                    <div class="mb-4">
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
                                                id="payment-proof-{{ $transaction->id }}"
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
                                                onclick="window.open(this.src, '_blank')"
                                            >
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-300 flex items-center justify-center pointer-events-none">
                                                <span class="opacity-0 group-hover:opacity-100 text-white bg-black bg-opacity-50 px-3 py-1 rounded text-sm transition-opacity duration-300">
                                                    Klik untuk melihat ukuran penuh
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Klik gambar untuk melihat dalam ukuran penuh</span>
                                            <span class="text-primary">|</span>
                                            <a href="{{ $finalUrl }}" target="_blank" class="text-primary hover:underline">
                                                Buka di tab baru
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <p class="text-sm text-yellow-800 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            Bukti pembayaran belum diupload
                                        </p>
                                    </div>
                                @endif
                            </div>

                            {{-- Transaction Items --}}
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Item Pembelian:</h4>
                                <div class="space-y-2">
                                    @foreach($transaction->items as $item)
                                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center space-x-3 flex-1">
                                                @if($item->product && $item->product->image)
                                                    <img 
                                                        src="{{ $item->product->image_url }}" 
                                                        alt="{{ $item->product->name }}"
                                                        class="w-12 h-12 object-cover rounded-lg"
                                                        onerror="this.src='https://placehold.co/48x48/F4F4F4/888888?text=DailyMoo'"
                                                    >
                                                @endif
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $item->product->name ?? 'Produk tidak ditemukan' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                                <a 
                                    href="{{ route('transactions.show', $transaction) }}" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    Lihat Detail Lengkap
                                </a>
                                <form action="{{ route('transactions.approve-payment', $transaction) }}" method="POST" class="inline">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors shadow-md hover:shadow-lg"
                                        onclick="return confirm('Setujui pembayaran ini? Pesanan akan diproses.')"
                                    >
                                        ✓ Setujui Pembayaran
                                    </button>
                                </form>
                                <form action="{{ route('transactions.reject-payment', $transaction) }}" method="POST" class="inline">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition-colors shadow-md hover:shadow-lg"
                                        onclick="return confirm('Tolak pembayaran ini? Stok produk akan dikembalikan dan transaksi akan dibatalkan.')"
                                    >
                                        ✗ Tolak Pembayaran
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $pendingVerifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

