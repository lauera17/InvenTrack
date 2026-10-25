<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvenTrack - Inventory Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 antialiased">

    <div class="min-h-screen lg:grid lg:grid-cols-2">

        <!-- Panel kiri: Branding -->
        <section class="relative hidden overflow-hidden bg-blue-950 lg:flex lg:items-center lg:justify-center">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900"></div>

            <div class="relative z-10 max-w-lg px-12 py-16 text-white">
                <div class="mb-8 flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-2xl font-bold text-blue-900 shadow-lg">
                        IT
                    </div>

                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight">
                            InvenTrack
                        </h1>
                        <p class="mt-1 text-sm text-blue-200">
                            Inventory Management
                        </p>
                    </div>
                </div>

                <h2 class="text-4xl font-bold leading-tight">
                    Kelola Inventaris dengan Lebih Mudah.
                </h2>

                <p class="mt-6 text-lg leading-8 text-blue-100">
                    Sistem pengelolaan barang dan peminjaman inventaris
                    yang modern, rapi, dan efisien.
                </p>

                <div class="mt-10 space-y-4 text-sm text-blue-100">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-800">✓</span>
                        Data inventaris terorganisasi
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-800">✓</span>
                        Peminjaman dan pengembalian tercatat
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-800">✓</span>
                        Informasi stok lebih mudah dipantau
                    </div>
                </div>
            </div>
        </section>

        <!-- Panel kanan: Form login atau registrasi -->
        <main class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-10">
            <div class="w-full max-w-md">

                <!-- Branding untuk layar HP -->
                <div class="mb-8 text-center lg:hidden">
                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-900 text-xl font-bold text-white">
                        IT
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-900">InvenTrack</h1>
                    <p class="mt-1 text-sm text-slate-500">Inventory Management</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/60 sm:p-9">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center text-xs text-slate-500">
                    &copy; {{ date('Y') }} InvenTrack. Inventory Management System.
                </p>
            </div>
        </main>

    </div>

</body>
</html>