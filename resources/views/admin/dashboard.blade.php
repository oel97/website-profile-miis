@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-600">Dashboard</p>
                    <h1 class="mt-2 text-3xl font-bold text-slate-900">Selamat datang, {{ auth()->user()->name ?? 'Administrator' }}</h1>
                </div>
                <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-right">
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-700">Status</p>
                    <p class="mt-1 text-lg font-bold text-emerald-700">Online</p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Profil Sekolah</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">Siap</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Berita</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">Siap</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Prestasi</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">Siap</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">PPDB</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">Siap</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Menu CMS</h2>
                <ul class="mt-4 space-y-3 text-sm text-slate-600">
                    <li><span class="font-semibold text-emerald-700">Master Sekolah:</span> Profil Sekolah, Sambutan Kepala, Guru, Program Unggulan</li>
                    <li><span class="font-semibold text-emerald-700">Konten:</span> Berita, Prestasi, Agenda</li>
                    <li><span class="font-semibold text-emerald-700">Media:</span> Galeri, Sarpras</li>
                    <li><span class="font-semibold text-emerald-700">PPDB:</span> Periode, persyaratan, dan tahapan</li>
                    <li><span class="font-semibold text-emerald-700">Pengaturan:</span> Kontak dan sosial media</li>
                </ul>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Ringkasan</h2>
                <p class="mt-4 text-sm leading-6 text-slate-600">
                    Dashboard ini merupakan starter layout untuk pengembangan CMS selanjutnya. Fitur CRUD dan pengelolaan konten akan ditambahkan pada tahap berikutnya.
                </p>
            </div>
        </div>
    </div>
@endsection
