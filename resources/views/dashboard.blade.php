@extends('layouts.app')

@section('title', 'Beranda - Gdox')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Dokumen Saya</h1>
            <p class="text-gray-400 mt-1">Buat, edit, dan kerja bareng secara realtime</p>
        </div>
        <form method="POST" action="{{ route('documents.create') }}">
            @csrf
            <button type="submit" id="create-document-btn" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Dokumen Baru
            </button>
        </form>
    </div>

    <div class="mb-8">
        <form method="GET" action="{{ route('dashboard') }}" class="relative max-w-md">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                type="text"
                name="search"
                id="search-documents"
                value="{{ request('search') }}"
                placeholder="Cari dokumen..."
                class="input-field pl-12"
            >
        </form>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm animate-fade-in-up">
        {{ session('success') }}
    </div>
    @endif

    @if($documents->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($documents as $document)
        <a href="{{ route('documents.show', $document->uuid) }}"
           class="document-card group"
           id="doc-{{ $document->uuid }}">
            <div class="h-40 rounded-t-xl bg-white/[0.02] border-b border-white/5 p-4 overflow-hidden relative">
                <div class="text-xs text-gray-500 leading-relaxed line-clamp-6">
                    {!! Str::limit(strip_tags($document->content), 300, '...') !!}
                    @if(empty($document->content))
                    <div class="flex flex-col items-center justify-center h-full text-gray-600">
                        <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Dokumen masih kosong</span>
                    </div>
                    @endif
                </div>
                <div class="absolute inset-x-0 bottom-0 h-12 bg-gradient-to-t from-[#1a1b2e] to-transparent"></div>
            </div>

            <div class="p-4">
                <h3 class="font-semibold text-white truncate group-hover:text-indigo-400 transition-colors">
                    {{ $document->title }}
                </h3>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-xs text-gray-500">
                        {{ $document->updated_at->diffForHumans() }}
                    </span>
                    @if($document->owner_id === Auth::id())
                    <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                        Pemilik
                    </span>
                    @else
                    <span class="text-xs px-2 py-0.5 rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/20">
                        Dibagikan
                    </span>
                    @endif
                </div>
            </div>

            @if($document->owner_id === Auth::id())
            <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <form method="POST" action="{{ route('documents.destroy', $document->uuid) }}"
                      onclick="event.preventDefault(); event.stopPropagation(); if(confirm('Hapus dokumen ini?')) this.submit();">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 flex items-center justify-center text-red-400 transition-colors" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endif
        </a>
        @endforeach
    </div>
    @else
    <div class="text-center py-20 animate-fade-in-up">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 border border-indigo-500/10 mb-6">
            <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">Belum ada dokumen</h3>
        <p class="text-gray-400 mb-8 max-w-sm mx-auto">Buat dokumen pertama dan mulai kerja bareng secara realtime.</p>
        <form method="POST" action="{{ route('documents.create') }}">
            @csrf
            <button type="submit" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Dokumen Pertama
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
