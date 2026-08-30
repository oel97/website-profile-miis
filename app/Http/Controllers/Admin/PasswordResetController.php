<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ForgotPasswordRequest;
use App\Http\Requests\Admin\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Display the form used to request a password reset link.
     */
    public function create(): View
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Send a reset link only to an active administrator account.
     */
    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        $user = User::query()
            ->where('email', $request->string('email')->toString())
            ->first();

        if (! $this->isResettableAdmin($user)) {
            return back()->with('status', 'Jika email tersebut terdaftar sebagai akun admin aktif, tautan pengaturan ulang kata sandi telah dikirimkan.');
        }

        $status = Password::sendResetLink($request->only('email'));

        return back()->with('status', $status === Password::RESET_LINK_SENT
            ? 'Tautan pengaturan ulang kata sandi telah dikirimkan ke email Anda.'
            : 'Permintaan belum dapat diproses. Silakan tunggu sebentar lalu coba kembali.');
    }

    /**
     * Display the password reset form from a valid emailed link.
     */
    public function edit(Request $request, string $token): View
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Save the new password after the token has been verified.
     */
    public function update(ResetPasswordRequest $request): RedirectResponse
    {
        $user = User::query()
            ->where('email', $request->string('email')->toString())
            ->first();

        if (! $this->isResettableAdmin($user)) {
            return back()->withErrors([
                'email' => 'Tautan pengaturan ulang tidak valid atau akun admin tidak dapat digunakan.',
            ]);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            },
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('admin.login')
                ->with('status', 'Kata sandi berhasil diperbarui. Silakan masuk dengan kata sandi baru Anda.');
        }

        return back()->withErrors([
            'email' => 'Tautan pengaturan ulang tidak valid atau sudah kedaluwarsa. Silakan minta tautan baru.',
        ]);
    }

    private function isResettableAdmin(?User $user): bool
    {
        return $user !== null
            && $user->is_active
            && in_array($user->role, ['super_admin', 'admin'], true);
    }
}
