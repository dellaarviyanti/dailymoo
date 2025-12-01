@extends('layouts.app')

@section('title', 'Edit Bobot Sapi - DailyMoo')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-widest text-primary font-semibold">Superadmin</p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Edit Bobot Sapi</h1>
                    <p class="text-gray-600 mt-1">Edit data bobot sapi nomor {{ $weight->cow_id }}</p>
                </div>
                <a href="{{ route('weight.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                    ← Kembali
                </a>
            </div>
        </div>

        {{-- Form Edit --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('weight.update', $weight) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Info Sapi --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 text-primary font-bold text-xl">
                            {{ $weight->cow_id }}
                        </span>
                        <div>
                            <p class="text-sm text-gray-500">Nomor Sapi</p>
                            <p class="text-lg font-semibold text-gray-900">Sapi #{{ $weight->cow_id }}</p>
                        </div>
                    </div>
                </div>

                {{-- Bobot --}}
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                        Bobot (kg) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="weight" 
                        id="weight"
                        step="0.1"
                        min="0"
                        max="1000"
                        value="{{ old('weight', $weight->weight) }}"
                        class="w-full md:w-64 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary"
                        required
                    >
                    @error('weight')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Umur (Hari) --}}
                <div>
                    <label for="umur_hari" class="block text-sm font-medium text-gray-700 mb-2">
                        Umur (hari) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="umur_hari" 
                        id="umur_hari"
                        min="0"
                        max="5000"
                        value="{{ old('umur_hari', $weight->umur_hari ?? '') }}"
                        class="w-full md:w-64 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary"
                        placeholder="Masukkan umur sapi (hari)"
                        required
                    >

                    @error('umur_hari')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Pengukuran --}}
                <div>
                    <label for="measured_at" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Pengukuran <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="measured_at" 
                        id="measured_at"
                        value="{{ old('measured_at', $weight->measured_at->format('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}"
                        class="w-full md:w-64 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary"
                        required
                    >
                    @error('measured_at')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>
                    <textarea 
                        name="notes" 
                        id="notes"
                        rows="4"
                        maxlength="500"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary"
                        placeholder="Catatan tambahan (opsional)"
                    >{{ old('notes', $weight->notes) }}</textarea>
                    @error('notes')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t">
                    <a href="{{ route('weight.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold shadow-md hover:shadow-lg"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- History Bobot Sapi Ini --}}
        @if($cowWeights->count() > 1)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">History Bobot Sapi #{{ $weight->cow_id }}</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bobot (kg)
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Umur (hari)
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Catatan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cowWeights as $w)
                        <tr class="hover:bg-gray-50 transition-colors {{ $w->id === $weight->id ? 'bg-primary/5' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ number_format($w->weight, 1) }} kg
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($w->measured_at)->format('d M Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">
                                    {{ $w->notes ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#btnPrediksi').click(function () {
        let weight = $('#weight').val();
        let umur = $('#umur').val();

        $.post('/predict-bk', { weight: weight, umur: umur }, function(response) {
            $('#hasilPrediksi').text('Prediksi BK: ' + response.bk + ' kg/hari');
        });
    });
</script>

@endsection

@section('footer')
{{-- Footer tidak ditampilkan di halaman ini --}}
@endsection

