@extends('layouts.app')

@section('title', 'Riwayat - ' . $document->title)

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Riwayat: {{ $document->title }}</h1>
            <div class="text-sm text-gray-400 mt-1">Versi yang tersimpan untuk dokumen ini</div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('documents.show', $document->uuid) }}" class="btn-ghost">Buka Dokumen</a>
            <a href="{{ route('dashboard') }}" class="btn-ghost">Kembali</a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">{{ session('success') }}</div>
    @endif

    @if($versions->isEmpty())
    <div class="py-16 text-center text-gray-400 border border-white/5 rounded-lg bg-white/[0.01]">
        <div class="text-lg font-semibold text-white mb-2">Belum ada versi dokumen</div>
        <p class="text-sm">Silakan buka dokumen dan klik tombol "Simpan Versi" untuk mulai menyimpan riwayat perubahan.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($versions as $v)
        <div class="p-4 rounded-lg bg-white/[0.02] border border-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-semibold">Versi {{ $v->version_number }}</div>
                    <div class="text-xs text-gray-400">{{ $v->savedByUser->name ?? 'Tidak diketahui' }} · {{ $v->created_at_formatted ?? ($v->created_at ? $v->created_at->format('d M Y H:i') : '') }}</div>
                </div>

                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('documents.versions.restore.page', ['uuid' => $document->uuid, 'version' => $v->id]) }}" onsubmit="return confirm('Pulihkan versi ini? Konten saat ini akan disimpan sebagai versi baru.');">
                        @csrf
                        <button type="submit" class="btn-ghost">Pulihkan</button>
                    </form>
                    <button type="button" class="btn-ghost" onclick="document.getElementById('preview-{{ $v->id }}').classList.toggle('hidden')">Preview</button>
                </div>
            </div>

            <div id="preview-{{ $v->id }}" class="mt-3 text-sm text-gray-300 hidden max-h-48 overflow-auto bg-[#0f1117] p-3 rounded">
                {!! $v->content !!}
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
