<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $contacts = Contact::query()
            ->orderByDesc('is_active')
            ->orderBy('sort_order')
            ->orderBy('label')
            ->paginate(15);

        return view('admin.contacts.index', compact('contacts'));
    }

    public function create(): View
    {
        return view('admin.contacts.create');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        Contact::create($request->validated());

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Kontak sekolah berhasil ditambahkan.');
    }

    public function edit(Contact $contact): View
    {
        return view('admin.contacts.edit', compact('contact'));
    }

    public function update(ContactRequest $request, Contact $contact): RedirectResponse
    {
        $contact->update($request->validated());

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Kontak sekolah berhasil diperbarui.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Kontak sekolah berhasil dihapus.');
    }
}
