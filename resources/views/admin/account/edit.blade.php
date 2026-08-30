@extends('admin.layouts.app')

@section('title', 'Akun Saya')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Pengaturan Akun</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Akun Saya</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Kelola nama, email untuk masuk, dan kata sandi akun administrator Anda.</p>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-semibold">Perubahan belum dapat disimpan.</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.account.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-1 border-b border-slate-100 pb-5">
                    <h2 class="text-lg font-bold text-slate-900">Informasi akun</h2>
                    <p class="text-sm text-slate-500">Nama dan email ini akan tampil pada panel admin serta digunakan saat login.</p>
                </div>

                <div class="mt-6 grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama akun <span class="text-red-600">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" maxlength="255" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email login <span class="text-red-600">*</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="email" maxlength="255" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-1 border-b border-slate-100 pb-5">
                    <h2 class="text-lg font-bold text-slate-900">Ubah kata sandi</h2>
                    <p class="text-sm text-slate-500">Kosongkan semua kolom di bawah jika Anda tidak ingin mengubah kata sandi.</p>
                </div>

                <div class="mt-6 grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="current_password" class="mb-2 block text-sm font-semibold text-slate-700">Kata sandi saat ini</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <p class="mt-1 text-xs text-slate-500">Wajib diisi hanya ketika Anda membuat kata sandi baru.</p>
                        @error('current_password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Kata sandi baru</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <p class="mt-1 text-xs text-slate-500">Minimal 8 karakter.</p>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi kata sandi baru</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    </div>
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Perubahan Akun</button>
            </div>
        </form>
    </div>
@endsection
