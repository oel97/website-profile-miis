@extends('admin.layouts.app')

@section('title', 'Kontak Sekolah')

@section('content')
    @php($typeLabels = [
        'address' => 'Alamat',
        'phone' => 'Telepon',
        'whatsapp' => 'WhatsApp',
        'email' => 'Email',
        'website' => 'Website',
        'maps' => 'Google Maps',
        'other' => 'Lainnya',
    ])

    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Pengaturan</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Kontak Sekolah</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola kanal komunikasi resmi MI Islamiyah Syafi'iyah.</p>
            </div>
            <a href="{{ route('admin.contacts.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                + Tambah Kontak
            </a>
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
                            <th class="px-4 py-3 font-semibold">Nama Kontak</th>
                            <th class="px-4 py-3 font-semibold">Jenis</th>
                            <th class="px-4 py-3 font-semibold">Informasi</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Urutan</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                            <tr class="border-t border-slate-200 align-top">
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $contact->label }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $typeLabels[$contact->type] ?? $contact->type }}</span>
                                </td>
                                <td class="max-w-md px-4 py-3">
                                    <p>{{ $contact->value }}</p>
                                    @if ($contact->url)
                                        <a href="{{ $contact->url }}" target="_blank" rel="noopener" class="mt-1 inline-flex text-xs font-semibold text-emerald-700 transition hover:text-emerald-800">Buka tautan</a>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($contact->is_active)
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                                    @else
                                        <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">Tidak aktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $contact->sort_order }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.contacts.edit', $contact) }}" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">Edit</a>
                                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kontak ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada kontak sekolah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($contacts->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
@endsection
