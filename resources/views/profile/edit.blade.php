<x-app-layout>
    <div class="min-h-screen bg-slate-100 py-10">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">
                    Pengaturan Profil
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Kelola informasi akun dan foto profil InvenTrack kamu.
                </p>
            </div>

            <!-- Foto Profil -->
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="bg-gradient-to-r from-blue-950 to-blue-800 px-6 py-5">
                    <h2 class="text-lg font-bold text-white">
                        Foto Profil
                    </h2>
                    <p class="mt-1 text-sm text-blue-100">
                        Foto akan ditampilkan di bagian kanan atas dashboard.
                    </p>
                </div>

                <div class="p-6 sm:p-8">

                    @if (session('status') === 'profile-photo-updated')
                        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                            Foto profil berhasil diperbarui!
                        </div>
                    @endif

                    @if ($errors->has('profile_photo'))
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            {{ $errors->first('profile_photo') }}
                        </div>
                    @endif

                    <div class="flex flex-col gap-6 sm:flex-row sm:items-start">

                        <!-- Foto saat ini -->
                        <div class="shrink-0">
                            @if (auth()->user()->profile_photo_path)
                                <img
                                    src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                    alt="Foto profil pengguna"
                                    class="h-28 w-28 rounded-full border-4 border-blue-100 object-cover shadow"
                                >
                            @else
                                <div class="flex h-28 w-28 items-center justify-center rounded-full border-4 border-blue-100 bg-blue-50 text-4xl font-bold text-blue-900 shadow">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Form upload -->
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ auth()->user()->name }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ auth()->user()->email }}
                            </p>

                            <span class="mt-3 inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold capitalize text-blue-800">
                                {{ auth()->user()->role ?? 'pegawai' }}
                            </span>

                            <form
                                method="POST"
                                action="{{ route('profile.photo.update') }}"
                                enctype="multipart/form-data"
                                class="mt-6 space-y-4"
                            >
                                @csrf

                                <div>
                                    <label
                                        for="profile_photo"
                                        class="mb-2 block text-sm font-semibold text-slate-700"
                                    >
                                        Pilih foto profil baru
                                    </label>

                                    <input
                                        type="file"
                                        id="profile_photo"
                                        name="profile_photo"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        required
                                        class="block w-full rounded-xl border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-3 file:font-semibold file:text-blue-900 hover:file:bg-blue-100"
                                    >

                                    <p class="mt-2 text-xs text-slate-500">
                                        Format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 2 MB.
                                    </p>
                                </div>

                                <button
                                    type="submit"
                                    class="rounded-xl bg-blue-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                >
                                    Simpan Foto Profil
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Informasi akun: nama dan email -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-lg font-bold text-slate-900">
                    Informasi Akun
                </h2>

                <p class="mt-1 mb-6 text-sm text-slate-500">
                    Perbarui nama dan alamat email akun kamu.
                </p>

                @include('profile.partials.update-profile-information-form')
            </section>

            <!-- Ganti password -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-lg font-bold text-slate-900">
                    Keamanan Akun
                </h2>

                <p class="mt-1 mb-6 text-sm text-slate-500">
                    Ganti kata sandi untuk menjaga keamanan akun.
                </p>

                @include('profile.partials.update-password-form')
            </section>

            <!-- Hapus akun -->
            <section class="rounded-2xl border border-red-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-lg font-bold text-red-700">
                    Hapus Akun
                </h2>

                <p class="mt-1 mb-6 text-sm text-slate-500">
                    Tindakan ini dapat menghapus akun secara permanen.
                    Pastikan kamu memahami konsekuensinya.
                </p>

                @include('profile.partials.delete-user-form')
            </section>

            <!-- Tombol kembali -->
            <div class="pb-6">
                <a
                    href="{{ route('dashboard') }}"
                    class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    &larr; Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>
</x-app-layout>