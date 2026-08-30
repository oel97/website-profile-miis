<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin | MI Islamiyah Syafi\'iyah')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="min-h-screen">
        <div class="flex min-h-screen">
            @include('admin.layouts.sidebar')

            <div class="flex min-h-screen flex-1 flex-col">
                @include('admin.layouts.navbar')

                <main class="flex-1 p-6 md:p-8">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    @stack('scripts')

    <script>
        document.querySelectorAll('[data-file-input]').forEach((input) => {
            const fileName = document.getElementById(input.dataset.fileNameTarget);

            input.addEventListener('change', () => {
                if (! fileName) {
                    return;
                }

                fileName.textContent = input.files.length ? input.files[0].name : 'Belum ada file dipilih';
            });
        });
    </script>
</body>
</html>
