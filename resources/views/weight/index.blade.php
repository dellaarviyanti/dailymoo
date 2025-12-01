@extends('layouts.app')

@section('title', 'Atur Bobot Pakan - DailyMoo')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 sm:py-8 lg:py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-widest text-primary font-semibold">Superadmin</p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Atur Bobot Pakan</h1>
                    <p class="text-gray-600 mt-1">Input bobot sapi untuk perhitungan pakan dengan Machine Learning</p>
                </div>
            </div>
        </div>

        {{-- Info Card --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-6 mb-6 sm:mb-8">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Panduan Pengukuran:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Lakukan pengukuran bobot setiap 1 bulan sekali</li>
                        <li>Input bobot untuk semua 10 sapi dalam satu kali pengukuran</li>
                        <li>Data akan otomatis tersimpan dan ditampilkan di grafik monitoring</li>
                        <li>Setelah data tersimpan, sistem akan siap untuk diproses oleh Machine Learning</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Form Input Bobot --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 sm:mb-6">Input Bobot Sapi</h2>

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

            <form id="weight-form" action="{{ route('weight.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Tanggal Pengukuran --}}
                <div>
                    <label for="measured_at" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Pengukuran <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="measured_at" 
                        id="measured_at"
                        value="{{ old('measured_at', date('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}"
                        class="w-full md:w-64 px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                        required
                    >
                    @error('measured_at')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Table Input Bobot --}}
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            No. Sapi
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Bobot Terakhir
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Bobot Baru (kg) <span class="text-red-500">*</span>
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Umur (hari) <span class="text-red-500">*</span>
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Saran Berat Pakan (kg)
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Persetujuan
                                        </th>
                                    </tr>
                                </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @for($i = 1; $i <= 10; $i++)
                                @php
                                    $latestWeight = $latestWeights[$i] ?? null;
                                    $index = $i - 1;
                                    $oldWeight = old('weights.' . $index);
                                    $oldAge = old('ages.' . $index);
                                    $oldSuggestion = old('feed_suggestions.' . $index);
                                    $oldAgreement = old('agreements.' . $index, 'agree');
                                    $oldCustomFeed = old('custom_feed.' . $index);
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors">

                                    {{-- No Sapi --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center w-10 h-10 
                                                    rounded-full bg-primary/10 text-primary font-semibold">
                                            {{ $i }}
                                        </span>
                                    </td>

                                    {{-- Bobot Terakhir --}}
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                        @if($latestWeight)
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ number_format($latestWeight->weight, 1) }} kg
                                            </span>
                                            <p class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($latestWeight->measured_at)->format('d M Y') }}
                                            </p>
                                        @else
                                            <span class="text-sm text-gray-400">Belum ada data</span>
                                        @endif
                                    </td>

                                    {{-- Bobot Baru --}}
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                        <input 
                                            type="number" 
                                            name="weights[{{ $index }}]" 
                                            step="0.1"
                                            min="0"
                                            value="{{ $oldWeight }}"
                                            class="w-full sm:w-32 px-3 py-2 border-2 border-gray-300 rounded-lg 
                                                focus:ring-2 focus:ring-primary focus:border-primary text-sm transition-colors"
                                            placeholder="0.0"
                                            data-weight-input
                                            data-index="{{ $index }}"
                                            required
                                        >
                                    </td>

                                    {{-- Umur (hari) --}}
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                        <input 
                                            type="number" 
                                            name="ages[{{ $index }}]" 
                                            step="1"
                                            min="0"
                                            max="5000"
                                            value="{{ $oldAge }}"
                                            placeholder="0"
                                            class="w-full sm:w-28 px-3 py-2 border-2 border-gray-300 rounded-lg 
                                                focus:ring-2 focus:ring-primary focus:border-primary text-sm transition-colors"
                                            data-index="{{ $index }}"
                                            required
                                        >
                                        @error('ages.' . $index)
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </td>

                                    {{-- Saran Pakan --}}
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="space-y-2">
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                                                <input 
                                                    type="number" 
                                                    name="feed_suggestions[{{ $index }}]"
                                                    step="0.1"
                                                    min="0"
                                                    max="1000"
                                                    value="{{ $oldSuggestion }}"
                                                    placeholder="0.0"
                                                    class="w-full sm:w-32 px-3 py-2 border border-amber-300 rounded-lg 
                                                        focus:ring-2 focus:ring-amber-500 focus:border-amber-500 
                                                        bg-amber-50 text-sm"
                                                    data-suggestion-input="{{ $index }}"
                                                >
                                                <span class="text-xs text-gray-500 hidden sm:inline">
                                                    Auto 3% dari bobot baru
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="space-y-3" data-approval-group data-index="{{ $index }}">
                                            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                                                <label class="relative cursor-pointer">
                                                    <input type="radio" 
                                                           name="agreements[{{ $index }}]" 
                                                           value="agree"
                                                           class="sr-only peer"
                                                           style="position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; z-index: 1;"
                                                           {{ $oldAgreement === 'agree' ? 'checked' : '' }}>
                                                    <span class="inline-flex items-center justify-center px-3 sm:px-4 py-2 rounded-lg border border-emerald-200 text-xs sm:text-sm font-medium text-emerald-600 bg-emerald-50 transition-all peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 shadow-sm cursor-pointer hover:bg-emerald-100 peer-checked:hover:bg-emerald-600 pointer-events-none">
                                                        Setuju
                                                    </span>
                                                </label>
                                                <label class="relative cursor-pointer">
                                                    <input type="radio" 
                                                           name="agreements[{{ $index }}]" 
                                                           value="reject"
                                                           class="sr-only peer"
                                                           style="position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; z-index: 1;"
                                                           {{ $oldAgreement === 'reject' ? 'checked' : '' }}>
                                                    <span class="inline-flex items-center justify-center px-3 sm:px-4 py-2 rounded-lg border border-red-200 text-xs sm:text-sm font-medium text-red-500 bg-red-50 transition-all peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 shadow-sm cursor-pointer hover:bg-red-100 peer-checked:hover:bg-red-600 pointer-events-none">
                                                        Tidak
                                                    </span>
                                                </label>
                                            </div>
                                            @error('agreements.' . $index)
                                                <p class="text-xs text-red-500">{{ $message }}</p>
                                            @enderror

                                            <div class="{{ $oldAgreement === 'reject' ? '' : 'hidden' }}" data-revision-wrapper>
                                                <label class="text-xs text-gray-600 block mb-1">Masukkan bobot baru (kg)</label>
                                                <input
                                                    type="number"
                                                    name="custom_feed[{{ $index }}]"
                                                    step="0.1"
                                                    min="0"
                                                    max="1000"
                                                    value="{{ $oldCustomFeed }}"
                                                    class="w-full sm:w-32 px-3 py-2 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-400 text-sm"
                                                >
                                                <p class="text-xs text-red-500 mt-1 hidden" data-revision-error></p>
                                                @error('custom_feed.' . $index)
                                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                        </div>
                    </div>
                </div>

                <div class="hidden mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm" data-form-error>
                    Mohon lengkapi bobot baru untuk setiap baris yang memilih "Tidak".
                </div>

                {{-- Submit Button --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t">
                    <a href="{{ route('monitoring') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold shadow-md hover:shadow-lg"
                    >
                        Simpan Data Bobot
                    </button>
                </div>
            </form>
        </div>

        {{-- History Data Bobot --}}
        @if($allWeights->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 lg:p-8 mb-8" id="history-container">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">History Data Bobot Sapi</h2>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                    <p class="text-sm text-gray-500 text-center sm:text-left">Total: <span id="history-total">{{ $allWeights->total() }}</span> record</p>
                    <form action="{{ route('weight.clear-all') }}" method="GET" onsubmit="return confirm('Yakin ingin menghapus semua history data bobot sapi? Tindakan ini tidak dapat dibatalkan!');" class="inline">
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors shadow-sm hover:shadow-md"
                        >
                            Hapus Semua History
                        </button>
                    </form>
                </div>
            </div>

            <div id="history-table-wrapper">
                @include('weight.partials.history-table', ['allWeights' => $allWeights])
            </div>
        </div>
        @endif

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Bagian approval dan revisi tetap
    const approvalGroups = document.querySelectorAll('[data-approval-group]');

    approvalGroups.forEach((group) => {
        const radios = group.querySelectorAll('input[type="radio"]');
        const labels = group.querySelectorAll('label');
        const revisionWrapper = group.querySelector('[data-revision-wrapper]');
        const revisionInput = revisionWrapper ? revisionWrapper.querySelector('input') : null;
        const revisionError = revisionWrapper ? revisionWrapper.querySelector('[data-revision-error]') : null;

        const toggleRevision = () => {
            const selected = group.querySelector('input[type="radio"]:checked');
            if (!selected) return;

            if (selected.value === 'reject') {
                revisionWrapper?.classList.remove('hidden');
                if (revisionInput) {
                    revisionInput.required = true;
                }
            } else {
                revisionWrapper?.classList.add('hidden');
                if (revisionInput) {
                    revisionInput.value = '';
                    revisionInput.required = false;
                }
                if (revisionError) {
                    revisionError.textContent = '';
                    revisionError.classList.add('hidden');
                }
            }
        };

        // Event listener untuk radio button
        radios.forEach((radio) => {
            // Gunakan multiple event untuk memastikan terdeteksi
            radio.addEventListener('change', toggleRevision);
            radio.addEventListener('click', function(e) {
                // Force update setelah click
                setTimeout(toggleRevision, 0);
            });
            radio.addEventListener('input', toggleRevision);
        });

        // Event listener untuk label dan span (untuk memastikan klik label juga bekerja)
        labels.forEach((label) => {
            const span = label.querySelector('span');
            
            // Klik pada label
            label.addEventListener('click', function(e) {
                // Pastikan radio ter-check
                const radio = label.querySelector('input[type="radio"]');
                if (radio && !radio.checked) {
                    radio.checked = true;
                    toggleRevision();
                }
            });
            
            // Klik pada span juga
            if (span) {
                span.addEventListener('click', function(e) {
                    e.preventDefault();
                    const radio = label.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        radio.dispatchEvent(new Event('change'));
                        toggleRevision();
                    }
                });
            }
        });

        // Inisialisasi awal
        toggleRevision();
    });

    const form = document.getElementById('weight-form');
    if (form) {
        const formError = form.querySelector('[data-form-error]');
        form.addEventListener('submit', (event) => {
            let hasError = false;

            approvalGroups.forEach((group) => {
                const selected = group.querySelector('input[type="radio"]:checked');
                if (!selected) return;

                if (selected.value === 'reject') {
                    const revisionWrapper = group.querySelector('[data-revision-wrapper]');
                    const revisionInput = revisionWrapper ? revisionWrapper.querySelector('input') : null;
                    const revisionError = revisionWrapper ? revisionWrapper.querySelector('[data-revision-error]') : null;

                    if (!revisionInput || revisionInput.value === '') {
                        hasError = true;
                        revisionWrapper?.classList.remove('hidden');
                        if (revisionError) {
                            revisionError.textContent = 'Masukkan bobot baru sebelum menyimpan.';
                            revisionError.classList.remove('hidden');
                        }
                    } else if (revisionError) {
                        revisionError.textContent = '';
                        revisionError.classList.add('hidden');
                    }
                }
            });

            if (hasError) {
                event.preventDefault();
                formError?.classList.remove('hidden');
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                formError?.classList.add('hidden');
            }
        });
    }

    // Bagian ML asli (jQuery - backup jika vanilla JS tidak bekerja)
    $('input[data-weight-input], input[name^="ages"]').on('input', function () {
        let index = $(this).data('index');
        if (!index) {
            const nameMatch = $(this).attr('name')?.match(/\[(\d+)\]/);
            if (nameMatch) index = nameMatch[1];
        }

        if (!index) return;

        let weight = $(`input[name="weights[${index}]"]`).val();
        let umur = $(`input[name="ages[${index}]"]`).val();

        // Jika belum lengkap, jangan prediksi
        if (!weight || !umur) {
            $(`input[name="feed_suggestions[${index}]"]`).val('');
            return;
        }

        // Validasi angka
        if (isNaN(weight) || isNaN(umur)) return;

        // Kirim ke ML asli via Laravel route /predict-bk
        $.ajax({
            url: "/predict-bk",
            method: "POST",
            data: {
                weight: parseFloat(weight),
                umur: parseFloat(umur),
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function (response) {
                if (response && response.bk !== undefined) {
                    const result = parseFloat(response.bk);
                    if (!isNaN(result)) {
                        $(`input[name="feed_suggestions[${index}]"]`).val(result.toFixed(1));
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('Error prediksi ML (jQuery):', error);
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    console.error('Error detail:', xhr.responseJSON.error);
                }
            }
        });
    });

});

document.addEventListener('DOMContentLoaded', function () {

    const weightInputs = document.querySelectorAll('input[data-weight-input]');
    const ageInputs = document.querySelectorAll('input[name^="ages"]');

    const inputs = [...weightInputs, ...ageInputs];

    inputs.forEach(input => {
        input.addEventListener('input', () => {
            // Ambil index dari data-index atau dari name attribute
            let rowIndex = input.dataset.index;
            if (!rowIndex && input.name) {
                const match = input.name.match(/\[(\d+)\]/);
                if (match) rowIndex = match[1];
            }

            if (!rowIndex) {
                console.error('Tidak bisa mendapatkan row index');
                return;
            }

            const weightInput = document.querySelector(`input[name="weights[${rowIndex}]"]`);
            const ageInput = document.querySelector(`input[name="ages[${rowIndex}]"]`);
            const suggestionInput = document.querySelector(`input[data-suggestion-input="${rowIndex}"]`);

            if (!weightInput || !ageInput || !suggestionInput) {
                console.error('Input field tidak ditemukan untuk row:', rowIndex);
                return;
            }

            const weight = weightInput.value.trim();
            const umur = ageInput.value.trim();

            if (!weight || !umur) {
                // Reset jika salah satu kosong
                suggestionInput.value = '';
                return;
            }

            // Validasi angka
            if (isNaN(weight) || isNaN(umur)) {
                console.error('Input bukan angka valid');
                return;
            }

            // Kirim ke ML via fetch
            fetch('/predict-bk', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    weight: parseFloat(weight), 
                    umur: parseFloat(umur) 
                })
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => Promise.reject(err));
                }
                return res.json();
            })
            .then(data => {
                if(data && data.bk !== undefined) {
                    const result = parseFloat(data.bk);
                    if (!isNaN(result)) {
                        suggestionInput.value = result.toFixed(1);
                        console.log('Prediksi berhasil:', result);
                    } else {
                        console.error('Hasil prediksi bukan angka:', data.bk);
                    }
                } else if (data && data.error) {
                    console.error('Error dari server:', data.error);
                } else {
                    console.error('Response tidak valid:', data);
                }
            })
            .catch(err => {
                console.error('Error prediksi ML:', err);
                if (err.error) {
                    console.error('Error detail:', err.error);
                }
            });
        });
    });

    // AJAX Pagination untuk History Table
    const historyTableWrapper = document.getElementById('history-table-wrapper');
    if (historyTableWrapper) {
        // Handle klik pada pagination links (delegasi event untuk link yang dinamis)
        document.addEventListener('click', function(e) {
            // Cek apakah klik berasal dari dalam history container
            const historyContainer = document.getElementById('history-container');
            if (!historyContainer || !historyContainer.contains(e.target)) return;
            
            const link = e.target.closest('a');
            if (!link) return;
            
            // Cek apakah ini link pagination (bukan link edit/hapus)
            const href = link.getAttribute('href');
            if (!href) return;
            
            // Skip jika bukan link pagination (edit, hapus, dll)
            if (href.includes('/weight/') && !href.includes('?page=') && !href.includes('&page=')) {
                return;
            }
            
            // Cek apakah ini link pagination
            if (!href.includes('page=')) {
                return;
            }
            
            e.preventDefault();
            
            // Tampilkan loading indicator
            historyTableWrapper.style.opacity = '0.5';
            historyTableWrapper.style.pointerEvents = 'none';
            
            // Buat URL untuk AJAX request
            let ajaxUrl = href;
            if (ajaxUrl.startsWith('/')) {
                ajaxUrl = window.location.origin + ajaxUrl;
            }
            
            // Tambahkan parameter ajax jika belum ada
            const urlObj = new URL(ajaxUrl);
            urlObj.searchParams.set('ajax', '1');
            ajaxUrl = urlObj.toString();
            
            // Fetch data via AJAX
            fetch(ajaxUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update tabel dan pagination
                historyTableWrapper.innerHTML = data.html;
                
                // Update total
                const totalElement = document.getElementById('history-total');
                if (totalElement) {
                    totalElement.textContent = data.total;
                }
                
                // Scroll ke atas tabel dengan smooth
                if (historyContainer) {
                    historyContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                
                // Restore opacity
                historyTableWrapper.style.opacity = '1';
                historyTableWrapper.style.pointerEvents = 'auto';
            })
            .catch(error => {
                console.error('Error loading history:', error);
                historyTableWrapper.style.opacity = '1';
                historyTableWrapper.style.pointerEvents = 'auto';
                alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
            });
        });
    }

});
</script>


@endsection

@section('footer')
{{-- Footer tidak ditampilkan di halaman ini --}}
@endsection

