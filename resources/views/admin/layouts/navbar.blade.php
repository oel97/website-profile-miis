<header class="border-b border-slate-200 bg-white px-4 py-3 shadow-sm sm:px-6 sm:py-4">
    <div class="flex items-center justify-between gap-3 sm:gap-4">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Profile MIIS</p>
            <h1 class="truncate text-lg font-bold text-slate-900">Control Panel</h1>
        </div>

        <div class="flex flex-shrink-0 items-center gap-2 sm:gap-4">
            <details class="relative md:hidden">
                <summary class="cursor-pointer list-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                    Menu
                </summary>
                <div class="absolute right-0 z-20 w-72">
                    @include('admin.layouts.sidebar', ['mobile' => true])
                </div>
            </details>

            <div class="hidden rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700 md:block">
                {{ auth()->user()->role ?? 'admin' }}
            </div>

            <a href="{{ route('admin.account.edit') }}" class="flex items-center gap-3 rounded-full border border-slate-200 bg-slate-50 px-3 py-2 transition hover:border-emerald-300 hover:bg-emerald-50" aria-label="Kelola akun saya">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="hidden text-left sm:block">
                    <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-xs text-slate-500">{{ auth()->user()->email ?? 'admin@profilemiis.test' }}</p>
                </div>
            </a>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
