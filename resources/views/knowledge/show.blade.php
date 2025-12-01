@extends('layouts.app')

@section('title', $article->title . ' - MooKnowledge')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-8">
        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full rounded-lg mb-6">

        <h1 class="text-3xl font-bold mb-4">{{ $article->title }}</h1>

        <div class="flex items-center justify-between text-sm text-gray-500 mb-6">
            <span class="bg-secondary px-3 py-1 rounded-full text-primary-dark">{{ $article->category }}</span>
            <span>{{ \Carbon\Carbon::parse($article->date)->format('d M Y') }}</span>
        </div>

        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
            {{ $article->content }}
        </p>

        <div class="mt-8">
            <a href="{{ route('knowledge') }}" 
               class="text-primary hover:text-primary-dark font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Artikel
            </a>
        </div>
    </div>
</div>
@endsection
