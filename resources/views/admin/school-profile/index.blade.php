@extends('admin.layouts.app')

@section('title', 'Profil Sekolah')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Master Sekolah</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Profil Sekolah</h1>
            </div>
            <div class="flex flex-wrap gap-3">
                @if ($contentProfile)
                    <a href="{{ route('admin.school-profile.edit', ['school_profile' => $contentProfile, 'section' => 'history']) }}" class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-800 shadow-sm transition hover:bg-amber-100">
                        + Tambah Sejarah
                    </a>
                    <a href="{{ route('admin.school-profile.edit', ['school_profile' => $contentProfile, 'section' => 'vision-mission']) }}" class="inline-flex items-center justify-center rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-800 shadow-sm transition hover:bg-emerald-100">
                        + Tambah Visi &amp; Misi
                    </a>
                @endif
                <a href="{{ route('admin.school-profile.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    + Tambah Profil
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Nama</th>
                            <th class="px-4 py-3 font-semibold">NPSN</th>
                            <th class="px-4 py-3 font-semibold">Akr.</th>
                            <th class="px-4 py-3 font-semibold">Telepon</th>
                            <th class="px-4 py-3 font-semibold">Email</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schoolProfiles as $profile)
                            <tr class="border-t border-slate-200">
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $profile->name }}</td>
                                <td class="px-4 py-3">{{ $profile->npsn ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $profile->accreditation ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $profile->phone ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $profile->email ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.school-profile.edit', $profile) }}" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-100">Edit</a>
                                        <form action="{{ route('admin.school-profile.destroy', $profile) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus profil sekolah ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada data profil sekolah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($schoolProfiles->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $schoolProfiles->links() }}
            </div>
        @endif
    </div>
@endsection
