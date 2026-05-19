<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gdox</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gdox-bg text-white font-inter min-h-screen flex items-center justify-center p-6">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 mb-5">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold mb-3">Gdox</h1>
        <p class="text-gray-400 mb-6">Editor dokumen realtime</p>
        <a href="{{ route('dashboard') }}" class="btn-primary inline-flex">Buka Dokumen</a>
    </div>
</body>
</html>
