@extends('layouts.app')

@section('title', 'MooKnowledge - Artikel Peternakan')

@section('content')
@php
    $isAdmin = auth()->check() && in_array(auth()->user()->role, ['superadmin', 'pegawai']);
    $formContext = old('form_context');
    $editingArticleId = $formContext === 'knowledgeUpdate' ? old('article_id') : null;
@endphp

<div x-data="{ openArticle: null }" class="min-h-screen bg-gray-50">

    {{-- ✅ Hero Section --}}
    <section class="bg-gradient-to-br from-primary to-primary-dark text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">MooKnowledge</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">
                Panduan lengkap dan artikel edukatif tentang peternakan sapi perah modern
            </p>
        </div>
    </section>

    @if($isAdmin)
        {{-- ✅ Admin Panel --}}
        <section class="py-12 border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                        <div>
                            <p class="text-sm uppercase tracking-widest text-primary font-semibold">Admin</p>
                            <h2 class="text-2xl font-bold text-gray-900 mt-1">Tambah Artikel MooKnowledge</h2>
                            <p class="text-gray-600">Kelola artikel langsung dari halaman publik tanpa berpindah halaman.</p>
                        </div>
                    </div>

                    @if ($errors->knowledgeStore->any())
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-600 px-4 py-3 text-sm">
                            Terdapat {{ $errors->knowledgeStore->count() }} error pada formulir tambah artikel.
                        </div>
                    @endif

                    <form action="{{ route('knowledge.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        <input type="hidden" name="form_context" value="knowledgeStore">

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Artikel</label>
                            <input type="text" name="title" value="{{ $formContext === 'knowledgeStore' ? old('title') : '' }}" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" placeholder="Contoh: Strategi Pakan Modern">
                            @error('title', 'knowledgeStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <input list="knowledge-categories" type="text" name="category" value="{{ $formContext === 'knowledgeStore' ? old('category') : '' }}" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" placeholder="Contoh: Nutrisi">
                            <datalist id="knowledge-categories">
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}"></option>
                                @endforeach
                            </datalist>
                            @error('category', 'knowledgeStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Rilis</label>
                            <input type="date" name="date" value="{{ $formContext === 'knowledgeStore' ? old('date') : '' }}" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                            @error('date', 'knowledgeStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan</label>
                            <textarea name="excerpt" rows="3" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none" placeholder="Tulis ringkasan singkat artikel">{{ $formContext === 'knowledgeStore' ? old('excerpt') : '' }}</textarea>
                            @error('excerpt', 'knowledgeStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konten Lengkap</label>
                            <textarea name="content" rows="5" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none" placeholder="Masukkan isi artikel lengkap">{{ $formContext === 'knowledgeStore' ? old('content') : '' }}</textarea>
                            @error('content', 'knowledgeStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar dari Komputer</label>
                            <input type="file" name="image_file" accept="image/*" class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark file:cursor-pointer">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (Maks. 2MB)</p>
                            @error('image_file', 'knowledgeStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">atau Masukkan URL Gambar</label>
                            <input type="url" name="image_url" value="{{ $formContext === 'knowledgeStore' ? old('image_url') : '' }}" placeholder="https://..." class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                            <p class="text-xs text-gray-500 mt-1">Pilih salah satu: Upload file ATAU masukkan URL</p>
                            @error('image_url', 'knowledgeStore')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-white font-semibold hover:bg-primary-dark transition-all">
                                Simpan Artikel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    @endif

    {{-- ✅ Articles Section --}}
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($articles as $article)
                    @php
                        $isEditingThis = $isAdmin && $editingArticleId == $article->id;
                        $updateContextActive = $isEditingThis && $formContext === 'knowledgeUpdate';
                    @endphp
                    <article class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        <div class="aspect-video bg-gray-100 overflow-hidden">
                            <img 
                                src="{{ $article->image_url }}" 
                                alt="{{ $article->title }}" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                            >
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="px-3 py-1 bg-secondary text-primary-dark rounded-full">
                                    {{ $article->category }}
                                </span>
                                <span class="text-gray-500">
                                    {{ \Carbon\Carbon::parse($article->date)->format('d M Y') }}
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-900 group-hover:text-primary transition-colors">
                                {{ $article->title }}
                            </h3>
                            
                            <p class="text-gray-600 line-clamp-3">
                                {{ $article->excerpt }}
                            </p>

                            <button 
                                @click="openArticle = {{ $article->id }}" 
                                class="inline-flex items-center text-primary hover:text-primary-dark font-medium transition-colors group">
                                Baca Selengkapnya
                                <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>

                            @if($isAdmin)
                                <div x-data="{ editing: {{ $isEditingThis ? 'true' : 'false' }} }" class="mt-6 border-t border-gray-100 pt-4">
                                    <div class="flex items-center justify-between">
                                        <button type="button" @click="editing = !editing" class="px-4 py-2 rounded-lg border border-primary text-primary hover:bg-primary hover:text-white transition">
                                            <span x-text="editing ? 'Tutup Editor' : 'Edit Artikel'"></span>
                                        </button>
                                        <form action="{{ route('knowledge.destroy', $article) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')" >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                    <div x-show="editing" x-transition class="mt-4 bg-gray-50 rounded-xl p-4 space-y-4">
                                        <form action="{{ route('knowledge.update', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="form_context" value="knowledgeUpdate">
                                            <input type="hidden" name="article_id" value="{{ $article->id }}">

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">Judul</label>
                                                <input type="text" name="title" value="{{ $updateContextActive ? old('title') : $article->title }}" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                                @if ($updateContextActive && $errors->knowledgeUpdate->has('title'))
                                                    <p class="text-sm text-red-500">{{ $errors->knowledgeUpdate->first('title') }}</p>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                                    <input list="knowledge-categories" type="text" name="category" value="{{ $updateContextActive ? old('category') : $article->category }}" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                                    @if ($updateContextActive && $errors->knowledgeUpdate->has('category'))
                                                        <p class="text-sm text-red-500">{{ $errors->knowledgeUpdate->first('category') }}</p>
                                                    @endif
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                                    <input type="date" name="date" value="{{ $updateContextActive ? old('date') : $article->date }}" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                                    @if ($updateContextActive && $errors->knowledgeUpdate->has('date'))
                                                        <p class="text-sm text-red-500">{{ $errors->knowledgeUpdate->first('date') }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">Ringkasan</label>
                                                <textarea name="excerpt" rows="2" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none">{{ $updateContextActive ? old('excerpt') : $article->excerpt }}</textarea>
                                                @if ($updateContextActive && $errors->knowledgeUpdate->has('excerpt'))
                                                    <p class="text-sm text-red-500">{{ $errors->knowledgeUpdate->first('excerpt') }}</p>
                                                @endif
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">Konten</label>
                                                <textarea name="content" rows="4" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none">{{ $updateContextActive ? old('content') : $article->content }}</textarea>
                                                @if ($updateContextActive && $errors->knowledgeUpdate->has('content'))
                                                    <p class="text-sm text-red-500">{{ $errors->knowledgeUpdate->first('content') }}</p>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">Upload Gambar Baru dari Komputer</label>
                                                    <input type="file" name="image_file" accept="image/*" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark file:cursor-pointer">
                                                    <p class="text-xs text-gray-500">Format: JPG, PNG, GIF (Maks. 2MB)</p>
                                                    @if ($updateContextActive && $errors->knowledgeUpdate->has('image_file'))
                                                        <p class="text-sm text-red-500">{{ $errors->knowledgeUpdate->first('image_file') }}</p>
                                                    @endif
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">atau Masukkan URL Gambar</label>
                                                    <input type="url" name="image_url" value="{{ $updateContextActive ? old('image_url') : '' }}" placeholder="https://..." class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                                    <p class="text-xs text-gray-500">Pilih salah satu: Upload file ATAU masukkan URL</p>
                                                    @if ($updateContextActive && $errors->knowledgeUpdate->has('image_url'))
                                                        <p class="text-sm text-red-500">{{ $errors->knowledgeUpdate->first('image_url') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($article->image && !\Illuminate\Support\Str::startsWith($article->image, ['http://', 'https://']))
                                                <div class="text-xs text-gray-500 bg-gray-50 p-2 rounded">
                                                    <strong>Gambar saat ini:</strong> {{ $article->image }}
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
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ✅ Popup Modal dengan animasi fade + slide --}}
    @foreach ($articles as $article)
        <div 
            x-show="openArticle === {{ $article->id }}" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            x-cloak
        >
            <div class="bg-white rounded-2xl shadow-xl max-w-3xl w-full relative overflow-y-auto max-h-[90vh]">
                <button 
                    @click="openArticle = null"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold"
                >&times;</button>

                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-64 object-cover rounded-t-2xl">

                <div class="p-8">
                    <h2 class="text-2xl font-bold mb-3">{{ $article->title }}</h2>

                    <div class="flex items-center justify-between text-sm text-gray-500 mb-6">
                        <span class="bg-secondary px-3 py-1 rounded-full text-primary-dark">{{ $article->category }}</span>
                        <span>{{ \Carbon\Carbon::parse($article->date)->format('d M Y') }}</span>
                    </div>

                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $article->content }}
                    </p>

                    <div class="mt-8 text-right">
                        <button 
                            @click="openArticle = null" 
                            class="px-5 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-all">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Alpine.js untuk interaksi --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
