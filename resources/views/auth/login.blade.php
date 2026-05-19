<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke Gdox">
    <title>Masuk - Gdox</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gdox-bg font-inter min-h-screen flex items-center justify-center p-4 overflow-hidden">
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <div class="w-full max-w-md relative z-10 animate-fade-in-up">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-2xl shadow-indigo-500/30 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Selamat datang lagi</h1>
            <p class="text-gray-400">Masuk untuk lanjut ke Gdox</p>
        </div>

        <div class="glass-card p-8 rounded-2xl">
            @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20">
                <div class="flex items-center gap-2 text-red-400 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="input-field"
                        placeholder="nama@email.com"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Kata sandi</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="input-field"
                        placeholder="********"
                    >
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/10 bg-white/5 text-indigo-500 focus:ring-indigo-500/50">
                        <span class="text-sm text-gray-400">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" id="login-button" class="btn-primary w-full">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-400">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors">
                        Daftar
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
