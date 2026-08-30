<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Display the authenticated administrator's account form.
     */
    public function edit(Request $request): View
    {
        return view('admin.account.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the authenticated administrator's own account.
     */
    public function update(AccountUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $attributes = $request->safe()->only(['name', 'email']);

        if ($request->filled('password')) {
            $attributes['password'] = $request->input('password');
        }

        $user->update($attributes);

        return redirect()
            ->route('admin.account.edit')
            ->with('success', 'Akun admin berhasil diperbarui.');
    }
}
