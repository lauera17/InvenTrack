<x-guest-layout>
    <div>
        {{-- LOGO --}}
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:32px;">
            <div style="display:grid;width:48px;height:48px;flex-shrink:0;place-items:center;border-radius:15px;background:linear-gradient(135deg,#1677ff,#1455d9);color:white;font-size:25px;box-shadow:0 8px 20px rgba(22,119,255,.25);">
                ▣
            </div>

            <div>
                <div style="font-size:24px;font-weight:800;letter-spacing:-.8px;color:#122b52;">
                    Inven<span style="color:#2478f3;">Track</span>
                </div>
                <div style="margin-top:3px;font-size:11px;color:#8392ac;">
                    Inventory Management System
                </div>
            </div>
        </div>

        {{-- JUDUL REGISTER --}}
        <h1 style="margin:0;font-size:28px;font-weight:800;letter-spacing:-.8px;color:#132b50;">
            Buat Akun Pegawai
        </h1>

        <p style="margin-top:10px;margin-bottom:28px;font-size:13px;line-height:1.8;color:#8290a8;">
            Daftarkan akun Anda untuk mulai menggunakan sistem InvenTrack.
        </p>

        {{-- PESAN VALIDASI --}}
        @if ($errors->any())
            <div style="margin-bottom:18px;padding:12px 14px;border-radius:10px;background:#fef2f2;color:#b91c1c;font-size:12px;line-height:1.7;">
                Mohon periksa kembali data yang kamu masukkan.
            </div>
        @endif

        {{-- FORM REGISTER --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- NAMA --}}
            <div style="margin-bottom:20px;">
                <label for="name" style="display:block;margin-bottom:9px;font-size:13px;font-weight:600;color:#233b60;">
                    Nama Lengkap
                </label>

                <div style="position:relative;">
                    <span style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#8194b3;">
                        ♙
                    </span>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Masukkan nama lengkap"
                        style="box-sizing:border-box;width:100%;height:51px;padding:0 15px 0 43px;border:1px solid #dce4f0;border-radius:11px;background:#fbfcff;font-size:13px;color:#172f54;outline:none;transition:.2s;"
                        onfocus="this.style.borderColor='#3984f8';this.style.boxShadow='0 0 0 3px rgba(57,132,248,.12)'"
                        onblur="this.style.borderColor='#dce4f0';this.style.boxShadow='none'"
                    >
                </div>

                @error('name')
                    <p style="margin-top:7px;color:#dc2626;font-size:12px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div style="margin-bottom:20px;">
                <label for="email" style="display:block;margin-bottom:9px;font-size:13px;font-weight:600;color:#233b60;">
                    Alamat Email
                </label>

                <div style="position:relative;">
                    <span style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#8194b3;">
                        ✉
                    </span>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        placeholder="nama@email.com"
                        style="box-sizing:border-box;width:100%;height:51px;padding:0 15px 0 43px;border:1px solid #dce4f0;border-radius:11px;background:#fbfcff;font-size:13px;color:#172f54;outline:none;transition:.2s;"
                        onfocus="this.style.borderColor='#3984f8';this.style.boxShadow='0 0 0 3px rgba(57,132,248,.12)'"
                        onblur="this.style.borderColor='#dce4f0';this.style.boxShadow='none'"
                    >
                </div>

                @error('email')
                    <p style="margin-top:7px;color:#dc2626;font-size:12px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div style="margin-bottom:20px;">
                <label for="password" style="display:block;margin-bottom:9px;font-size:13px;font-weight:600;color:#233b60;">
                    Kata Sandi
                </label>

                <div style="position:relative;">
                    <span style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#8194b3;">
                        ◈
                    </span>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Buat kata sandi"
                        style="box-sizing:border-box;width:100%;height:51px;padding:0 100px 0 43px;border:1px solid #dce4f0;border-radius:11px;background:#fbfcff;font-size:13px;color:#172f54;outline:none;transition:.2s;"
                        onfocus="this.style.borderColor='#3984f8';this.style.boxShadow='0 0 0 3px rgba(57,132,248,.12)'"
                        onblur="this.style.borderColor='#dce4f0';this.style.boxShadow='none'"
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        data-target="password"
                        aria-label="Tampilkan kata sandi"
                        aria-pressed="false"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);border:0;background:transparent;color:#2478f3;cursor:pointer;font-size:11px;padding:8px;"
                    >
                        Lihat
                    </button>
                </div>

                @error('password')
                    <p style="margin-top:7px;color:#dc2626;font-size:12px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- KONFIRMASI PASSWORD --}}
            <div style="margin-bottom:22px;">
                <label for="password_confirmation" style="display:block;margin-bottom:9px;font-size:13px;font-weight:600;color:#233b60;">
                    Konfirmasi Kata Sandi
                </label>

                <div style="position:relative;">
                    <span style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#8194b3;">
                        ◈
                    </span>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi kata sandi"
                        style="box-sizing:border-box;width:100%;height:51px;padding:0 100px 0 43px;border:1px solid #dce4f0;border-radius:11px;background:#fbfcff;font-size:13px;color:#172f54;outline:none;transition:.2s;"
                        onfocus="this.style.borderColor='#3984f8';this.style.boxShadow='0 0 0 3px rgba(57,132,248,.12)'"
                        onblur="this.style.borderColor='#dce4f0';this.style.boxShadow='none'"
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        data-target="password_confirmation"
                        aria-label="Tampilkan konfirmasi kata sandi"
                        aria-pressed="false"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);border:0;background:transparent;color:#2478f3;cursor:pointer;font-size:11px;padding:8px;"
                    >
                        Lihat
                    </button>
                </div>

                @error('password_confirmation')
                    <p style="margin-top:7px;color:#dc2626;font-size:12px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- TOMBOL REGISTER --}}
            <button
                type="submit"
                style="display:flex;box-sizing:border-box;width:100%;min-height:52px;align-items:center;justify-content:center;gap:10px;border:0;border-radius:11px;background:linear-gradient(100deg,#267bfa,#1557dc);box-shadow:0 9px 20px rgba(37,104,226,.22);color:white;font-size:14px;font-weight:700;cursor:pointer;transition:transform .2s,box-shadow .2s;"
                onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 13px 25px rgba(37,104,226,.3)'"
                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 9px 20px rgba(37,104,226,.22)'"
            >
                Daftar sebagai Pegawai
                <span style="font-size:18px;">→</span>
            </button>
        </form>

        {{-- LINK LOGIN --}}
        <div style="margin-top:26px;padding-top:20px;border-top:1px solid #edf1f7;text-align:center;">
            <p style="margin:0;font-size:12px;line-height:1.8;color:#8290a8;">
                Sudah memiliki akun?
                <a
                    href="{{ route('login') }}"
                    style="font-weight:700;color:#2478f3;text-decoration:none;"
                >
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>

    {{-- INTERAKSI TAMPILKAN PASSWORD --}}
    <script>
        document.querySelectorAll('.toggle-password').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.target);
                if (!input) return;

                const show = input.type === 'password';

                input.type = show ? 'text' : 'password';
                this.textContent = show ? 'Sembunyikan' : 'Lihat';
                this.setAttribute('aria-pressed', String(show));
                this.setAttribute(
                    'aria-label',
                    show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'
                );
            });
        });
    </script>
</x-guest-layout>