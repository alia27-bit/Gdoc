<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $document->title }} - Gdox</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gdox-bg font-inter min-h-screen flex flex-col">
    <nav class="editor-nav" id="editor-navbar">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <a href="{{ route('dashboard') }}" class="flex-shrink-0 w-9 h-9 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <input type="text" id="document-title" value="{{ $document->title }}" class="bg-transparent border-none outline-none text-white font-semibold text-lg truncate flex-1 min-w-0 px-2 py-1 rounded-lg hover:bg-white/5 focus:bg-white/10 transition-colors" placeholder="Dokumen Baru">
            <div class="flex-shrink-0 flex items-center gap-2 text-xs text-gray-500" id="save-status">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" id="save-indicator"></div>
                <span id="save-text">Tersimpan</span>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <button id="share-btn" class="btn-ghost text-sm flex items-center gap-2" title="Salin tautan berbagi">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                <span class="hidden sm:inline">Bagikan</span>
            </button>
            <a href="{{ route('documents.history', $document->uuid) }}" class="btn-ghost text-sm flex items-center gap-2" title="Buka halaman riwayat dokumen">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="hidden sm:inline">Riwayat</span>
            </a>
            <button id="save-version-btn" class="btn-ghost text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <span class="hidden sm:inline">Simpan Versi</span>
            </button>
            
            <div class="flex items-center -space-x-2 ml-2" id="collaborators-avatars">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 border-[#0f1019] z-10 shadow-lg" style="background: linear-gradient(135deg, #1e40af, #2563eb)" title="{{ $user->name }} (saya)">{{ $user->initials }}</div>
            </div>
        </div>
    </nav>

    <div class="editor-toolbar" id="toolbar">
        <div class="toolbar-group">
            <select class="ql-header toolbar-select"><option value="">Biasa</option><option value="1">Judul 1</option><option value="2">Judul 2</option><option value="3">Judul 3</option></select>
        </div>
        <div class="toolbar-divider"></div>
        <div class="toolbar-group">
            <button class="ql-bold toolbar-btn"></button>
            <button class="ql-italic toolbar-btn"></button>
            <button class="ql-underline toolbar-btn"></button>
            <button class="ql-strike toolbar-btn"></button>
            
        </div>
        <div class="toolbar-divider"></div>
        <div class="toolbar-group">
            <select class="ql-color toolbar-btn"></select>
            <select class="ql-background toolbar-btn"></select>
        </div>
        <div class="toolbar-divider"></div>
        <div class="toolbar-group">
            <button class="ql-list toolbar-btn" value="ordered"></button>
            <button class="ql-list toolbar-btn" value="bullet"></button>
        </div>
        <div class="toolbar-divider"></div>
        <div class="toolbar-group">
            <select class="ql-align toolbar-btn"></select>
        </div>
        <div class="toolbar-divider"></div>
        <div class="toolbar-group">
            <button class="ql-blockquote toolbar-btn"></button>
            <button class="ql-code-block toolbar-btn"></button>
            <button class="ql-link toolbar-btn"></button>
        </div>
        <div class="toolbar-divider"></div>
        <div class="toolbar-group">
            <button class="ql-clean toolbar-btn"></button>
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        <div class="flex-1 flex flex-col overflow-hidden" id="editor-container">
            <div class="flex-1 overflow-auto bg-gdox-editor">
                <div class="max-w-4xl mx-auto py-8 px-8 min-h-full">
                    <div id="editor" class="editor-content"></div>
                </div>
            </div>
        </div>
        <div class="version-sidebar hidden" id="version-sidebar">
            <div class="p-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="font-semibold text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Riwayat Versi
                </h3>
                <button id="close-versions-btn" class="w-7 h-7 rounded-lg hover:bg-white/5 flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                
            </div>
            <div class="overflow-y-auto flex-1 p-4" id="versions-list">
                <div class="text-center text-gray-500 text-sm py-8">Memuat...</div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 px-4 py-2.5 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm opacity-0 transition-all duration-300 pointer-events-none z-50" id="share-toast">Tautan disalin!</div>
    <div class="fixed bottom-6 right-6 z-50" id="connection-status">
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full text-xs bg-yellow-500/10 border border-yellow-500/20 text-yellow-400" id="connection-badge">
            <div class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></div>Menghubungkan...
        </div>
    </div>

    <script>
window.editorConfig = {
    documentUuid: "{{ $document->uuid }}",
    userId: "{{ auth()->id() }}",
    userName: @json(auth()->user()->name),
    userColor: @json(auth()->user()->cursor_color),
    content: @json($document->content),
}
</script>

@vite(['resources/js/editor.js'])
</body>
</html>
