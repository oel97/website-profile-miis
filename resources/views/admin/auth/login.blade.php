<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | MI Islamiyah Syafi'iyah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-emerald-100 text-slate-800">
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-6xl overflow-hidden rounded-[32px] border border-emerald-100 bg-white/80 shadow-[0_20px_45px_rgba(6,95,70,0.15)] backdrop-blur-sm">
            <div class="grid lg:grid-cols-2">
                <div class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-emerald-700 via-emerald-600 to-emerald-500 p-10 text-white">
                    <div>
                        <div class="inline-flex items-center gap-3 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur-sm">
                            <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-200"></span>
                            Sistem Informasi Sekolah
                        </div>
                    </div>

                    <div>
                        <h1 class="text-4xl font-bold leading-tight">MI Islamiyah<br>Syafi'iyah</h1>
                        <p class="mt-5 max-w-md text-sm leading-6 text-emerald-50/90">
                            Portal administrasi untuk mengelola profil sekolah, konten, media, dan kegiatan PPDB secara profesional dan aman.
                        </p>
                    </div>

                    <div class="flex items-center gap-4 text-sm text-emerald-50/90">
                        <div class="rounded-full bg-white/10 px-3 py-1.5">Modern</div>
                        <div class="rounded-full bg-white/10 px-3 py-1.5">Aman</div>
                        <div class="rounded-full bg-white/10 px-3 py-1.5">Terintegrasi</div>
                    </div>
                </div>

                <div class="p-6 sm:p-10 lg:p-12">
                    <div class="mb-8">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Admin CMS</p>
                        <h2 class="mt-3 text-3xl font-bold text-slate-900">Masuk ke Dashboard</h2>
                        <p class="mt-2 text-sm text-slate-600">Silakan masuk dengan akun administrator untuk melanjutkan.</p>
                    </div>

                    @if (session('status'))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                placeholder="admin@profilemiis.test"
                            >
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                placeholder="Masukkan password"
                            >
                        </div>

                        <div class="flex items-center justify-between gap-3 text-sm">
                            <label class="inline-flex items-center gap-2 text-slate-600">
                                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                Ingat sesi saya
                            </label>
                            <a href="{{ route('admin.password.request') }}" class="text-right font-medium text-emerald-700 transition hover:text-emerald-600">Lupa kata sandi?</a>
                        </div>

                        <p class="-mt-3 text-xs leading-5 text-slate-500">Login menggunakan email. Jika lupa email login, hubungi super admin sekolah.</p>

                        <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-500 px-5 py-3.5 text-base font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:from-emerald-500 hover:to-emerald-600 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                            Masuk ke Admin
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
