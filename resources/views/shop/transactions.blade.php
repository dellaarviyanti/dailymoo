@extends('layouts.app')

@section('title', 'Transaksi - DailyMoo')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-widest text-primary font-semibold">
                        {{ in_array(auth()->user()->role, ['superadmin', 'pegawai']) ? 'Admin' : 'Pembeli' }}
                    </p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Riwayat Transaksi</h1>
                    <p class="text-gray-600 mt-1">
                        @if(request('filter') === 'today')
                            Transaksi hari ini
                        @elseif(request('filter') === 'month')
                            Transaksi bulan ini
                        @else
                            {{ in_array(auth()->user()->role, ['superadmin', 'pegawai']) ? 'Daftar semua transaksi' : 'Daftar semua transaksi pembelian Anda' }}
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Filter Buttons --}}
                    <a href="{{ route('transactions') }}" class="px-4 py-2 text-sm font-medium {{ !request('filter') ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} rounded-lg transition-colors">
                        Semua
                    </a>
                    <a href="{{ route('transactions', ['filter' => 'today']) }}" class="px-4 py-2 text-sm font-medium {{ request('filter') === 'today' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} rounded-lg transition-colors">
                        Hari Ini
                    </a>
                    <a href="{{ route('transactions', ['filter' => 'month']) }}" class="px-4 py-2 text-sm font-medium {{ request('filter') === 'month' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} rounded-lg transition-colors">
                        Bulan Ini
                    </a>
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

        {{-- Transactions List --}}
        @if($transactions->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Transaksi</h3>
                <p class="text-gray-600 mb-6">
                    {{ in_array(auth()->user()->role, ['superadmin', 'pegawai']) ? 'Belum ada transaksi yang masuk.' : 'Anda belum melakukan pembelian apapun.' }}
                </p>
                @if(!in_array(auth()->user()->role, ['superadmin', 'pegawai']))
                    <a href="{{ route('shop') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Mulai Belanja
                    </a>
                @endif
            </div>
        @else
            <div class="space-y-4">
                @foreach($transactions as $transaction)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Transaksi #{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}
                                        </h3>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $transaction->status_badge }}">
                                            {{ $transaction->status_label }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        {{ $transaction->created_at->format('d M Y, H:i') }}
                                    </p>
                                    @if(in_array(auth()->user()->role, ['superadmin', 'pegawai']) && $transaction->user)
                                        <p class="text-sm text-gray-600 mt-1">
                                            Pembeli: {{ $transaction->user->username }} ({{ $transaction->user->email }})
                                        </p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-primary">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
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
                                                @else
                                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <span class="text-gray-400 text-xs">No Image</span>
                                                    </div>
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
                            <div class="flex items-center justify-end space-x-3 mt-4 pt-4 border-t border-gray-200">
                                <a 
                                    href="{{ route('transactions.show', $transaction) }}" 
                                    class="px-4 py-2 text-sm font-medium text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-colors"
                                >
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

