<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PrincipalMessageRequest;
use App\Models\PrincipalMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PrincipalMessageController extends Controller
{
    public function index(): View
    {
        $principalMessages = PrincipalMessage::latest()->paginate(10);

        return view('admin.principal-message.index', compact('principalMessages'));
    }

    public function create(): View
    {
        return view('admin.principal-message.create');
    }

    public function store(PrincipalMessageRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('principal', 'public');
        }

        unset($data['photo']);

        PrincipalMessage::create($data);

        return redirect()->route('admin.principal-message.index')->with('success', 'Sambutan kepala madrasah berhasil ditambahkan.');
    }

    public function edit(PrincipalMessage $principal_message): View
    {
        return view('admin.principal-message.edit', compact('principal_message'));
    }

    public function update(PrincipalMessageRequest $request, PrincipalMessage $principal_message): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($principal_message->photo_path) {
                Storage::disk('public')->delete($principal_message->photo_path);
            }

            $data['photo_path'] = $request->file('photo')->store('principal', 'public');
        }

        unset($data['photo']);

        $principal_message->update($data);

        return redirect()->route('admin.principal-message.index')->with('success', 'Sambutan kepala madrasah berhasil diperbarui.');
    }

    public function destroy(PrincipalMessage $principal_message): RedirectResponse
    {
        if ($principal_message->photo_path) {
            Storage::disk('public')->delete($principal_message->photo_path);
        }

        $principal_message->delete();

        return redirect()->route('admin.principal-message.index')->with('success', 'Sambutan kepala madrasah berhasil dihapus.');
    }
}
