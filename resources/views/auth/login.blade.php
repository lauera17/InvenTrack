<x-guest-layout>
    <div>
        <!-- LOGO -->
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:32px;">
            <div style="display:grid;width:48px;height:48px;place-items:center;border-radius:15px;background:linear-gradient(135deg,#1677ff,#1455d9);color:white;font-size:25px;box-shadow:0 8px 20px rgba(22,119,255,.25);">
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

        <!-- JUDUL LOGIN -->
        <h1 style="margin:0;font-size:28px;font-weight:800;letter-spacing:-.8px;color:#132b50;">
            Selamat Datang!
        </h1>

        <p style="margin-top:10px;margin-bottom:28px;font-size:13px;line-height:1.8;color:#8290a8;">
            Masuk ke akun Anda untuk melanjutkan ke sistem InvenTrack.
        </p>

        <!-- STATUS -->
        @if (session('status'))
            <div style="margin-bottom:18px;padding:12px 14px;border-radius:10px;background:#ecfdf5;color:#047857;font-size:13px;">
                {{ session('status') }}
            </div>
        @endif

        <!-- FORM LOGIN -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
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
                        autofocus
                        autocomplete="username"
                        placeholder="nama@email.com"
                        style="width:100%;height:51px;padding:0 15px 0 43px;border:1px solid #dce4f0;border-radius:11px;background:#fbfcff;font-size:13px;color:#172f54;outline:none;transition:.2s;"
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

            <!-- PASSWORD -->
            <div style="margin-bottom:18px;">
                <label for="password" style="display:block;margin-bottom:9px;font-size:13px;font-weight:600;color:#233b60;">
                    Password
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
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        style="width:100%;height:51px;padding:0 48px 0 43px;border:1px solid #dce4f0;border-radius:11px;background:#fbfcff;font-size:13px;color:#172f54;outline:none;transition:.2s;"
                        onfocus="this.style.borderColor='#3984f8';this.style.boxShadow='0 0 0 3px rgba(57,132,248,.12)'"
                        onblur="this.style.borderColor='#dce4f0';this.style.boxShadow='none'"
                    >

                    <button
                        type="button"
                        id="togglePassword"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);border:0;background:transparent;color:#7185a5;cursor:pointer;font-size:12px;padding:8px;"
                    >
                        Lihat
                    </button>
                </div>

                @error('password')
                    <p style="margin-top:7px;color:#dc2626;font-size:12px;">
                        {{ $message }}
                    </p>
                @enderror

                @if ($errors->has('email') && ! $errors->has('password'))
                    <p style="margin-top:7px;color:#dc2626;font-size:12px;">
                        Email atau password tidak sesuai.
                    </p>
                @endif
            </div>

            <!-- INGAT SAYA DAN LUPA PASSWORD -->
            <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-bottom:26px;">
                <label for="remember_me" style="display:flex;align-items:center;gap:8px;font-size:12px;color:#506582;cursor:pointer;">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        style="width:15px;height:15px;accent-color:#2478f3;"
                    >
                    Ingat saya
                </label>

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        style="font-size:12px;font-weight:600;color:#2478f3;text-decoration:none;"
                    >
                        Lupa password?
                    </a>
                @endif
            </div>

            <!-- TOMBOL LOGIN -->
            <button
                type="submit"
                style="display:flex;width:100%;height:52px;align-items:center;justify-content:center;gap:10px;border:0;border-radius:11px;background:linear-gradient(100deg,#267bfa,#1557dc);box-shadow:0 9px 20px rgba(37,104,226,.22);color:white;font-size:14px;font-weight:700;cursor:pointer;transition:transform .2s,box-shadow .2s;"
                onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 13px 25px rgba(37,104,226,.3)'"
                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 9px 20px rgba(37,104,226,.22)'"
            >
                Masuk ke InvenTrack
                <span style="font-size:18px;">→</span>
            </button>
        </form>
    </div>

    <!-- INTERAKSI TAMPILKAN PASSWORD -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const password = document.getElementById('password');
            const toggle = document.getElementById('togglePassword');

            if (password && toggle) {
                toggle.addEventListener('click', function () {
                    const isHidden = password.type === 'password';

                    password.type = isHidden ? 'text' : 'password';
                    toggle.textContent = isHidden ? 'Sembunyikan' : 'Lihat';
                    toggle.setAttribute('aria-pressed', String(isHidden));
                    toggle.setAttribute(
                        'aria-label',
                        isHidden ? 'Sembunyikan password' : 'Tampilkan password'
                    );
                });
            }
        });
    </script>
</x-guest-layout>