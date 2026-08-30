<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kata Sandi Baru | MI Islamiyah Syafi'iyah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-emerald-100 text-slate-800">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <section class="w-full max-w-lg rounded-[32px] border border-emerald-100 bg-white p-7 shadow-[0_20px_45px_rgba(6,95,70,0.15)] sm:p-10">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Pemulihan Akun</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Buat kata sandi baru</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">Gunakan kata sandi baru minimal 8 karakter untuk akun admin Anda.</p>

            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.update') }}" class="mt-7 space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email akun admin</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Kata sandi baru</label>
                    <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi kata sandi baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100">
                </div>

                <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-500 px-5 py-3.5 text-base font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:from-emerald-500 hover:to-emerald-600 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                    Simpan Kata Sandi Baru
                </button>
            </form>
        </section>
    </main>
</body>
</html>
