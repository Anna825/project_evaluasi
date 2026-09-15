<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daftar Akun Alumni - Evaluasi PBM LAM Teknik</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --background: #f0f2f7;
            --foreground: #0f1c2e;
            --card: #ffffff;
            --primary: #1a3a5c;
            --primary-dark: #0f1c2e;
            --secondary: #e8edf5;
            --muted: #dde3ee;
            --muted-foreground: #5a6e8a;
            --accent: #b8952a;
            --border: #c8d3e6;
        }

        * {
            font-family: 'Outfit', sans-serif;
        }

        h1,
        h2,
        .font-serif-display {
            font-family: 'Fraunces', serif;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        input:focus,
        select:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--primary);
            border-color: var(--primary);
        }

        .btn-primary {
            background-color: var(--primary);
            transition: background-color .2s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
    </style>
</head>

<body
    class="min-h-screen"
    style="background-color: var(--background); color: var(--foreground);"
>

<div class="h-screen grid grid-cols-1 lg:grid-cols-2 overflow-hidden">

    {{-- ========================================================= --}}
    {{-- PANEL KIRI : BRANDING --}}
    {{-- ========================================================= --}}

    <div
        class="relative hidden lg:flex flex-col justify-between p-12 overflow-hidden h-screen"
        style="background: linear-gradient(160deg, var(--primary-dark), var(--primary));"
    >

        {{-- Dekorasi --}}
        <div
            class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full"
            style="background: rgba(255,255,255,0.05);"
        ></div>

        <div
            class="absolute -bottom-10 -right-10 w-64 h-64 rounded-full"
            style="background: rgba(255,255,255,0.05);"
        ></div>

        <div class="relative z-10">

            {{-- Logo / Institusi --}}
            <div class="flex items-center gap-3 mb-14">

                <div
                    class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                    style="background: var(--accent);"
                >
                    <svg
                        width="26"
                        height="26"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="white"
                        stroke-width="1.8"
                    >
                        <path
                            d="M3 21h18M4 21V8l8-5 8 5v13M9 21v-6h6v6M9 12h.01M15 12h.01M9 9h.01M15 9h.01"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>

                <div class="text-white leading-tight">

                    <p
                        class="text-xs uppercase tracking-wider"
                        style="color: rgba(255,255,255,0.65);"
                    >
                        Politeknik Manufaktur Bandung
                    </p>

                    <p class="font-semibold font-serif-display">
                        Automation Engineering
                    </p>

                </div>
            </div>


            {{-- Branding --}}
            <div
                class="border-l-4 pl-5 mb-8"
                style="border-color: var(--accent);"
            >

                <h1 class="text-4xl font-bold text-white font-serif-display leading-tight">
                    Sistem Informasi
                </h1>

                <h1
                    class="text-4xl font-bold font-serif-display leading-tight"
                    style="color: var(--accent);"
                >
                    Akreditasi
                </h1>

                <p
                    class="mt-4 text-sm max-w-sm"
                    style="color: rgba(255,255,255,0.7);"
                >
                    Platform terintegrasi untuk pengelolaan data akreditasi
                    program studi Teknik Informatika
                </p>

            </div>


            {{-- Info Registrasi --}}
            <div
                class="rounded-xl p-5 max-w-md"
                style="background: rgba(255,255,255,0.08);"
            >

                <p
                    class="text-xs uppercase tracking-wider mb-2"
                    style="color: rgba(255,255,255,0.55);"
                >
                    Registrasi Alumni
                </p>

                <p class="text-sm text-white leading-relaxed">
                    Buat akun Alumni untuk mengakses Tracer Study.
                    Setelah mendaftar, akun akan menunggu verifikasi dan
                    aktivasi dari Admin.
                </p>

            </div>

        </div>


        {{-- Footer --}}
        <div
            class="relative z-10 text-xs"
            style="color: rgba(255,255,255,0.55);"
        >

            <p class="flex items-center gap-2">

                <span
                    class="w-2 h-2 rounded-full"
                    style="background:#4ade80;"
                ></span>

                Sistem aktif —
                Tahun Akademik
                {{ now()->month >= 8 ? now()->year . '/' . (now()->year + 1) : (now()->year - 1) . '/' . now()->year }}

            </p>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PANEL KANAN : FORM REGISTRASI ALUMNI --}}
    {{-- ========================================================= --}}

    <div
        class="h-screen overflow-y-auto flex items-start justify-center p-6 sm:p-10 lg:p-12"
    >

        <div class="w-full max-w-md">

            {{-- Logo mobile --}}
            <div class="flex items-center gap-3 mb-8 lg:hidden">

                <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                    style="background: var(--accent);"
                >
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="white"
                        stroke-width="1.8"
                    >
                        <path
                            d="M3 21h18M4 21V8l8-5 8 5v13M9 21v-6h6v6M9 12h.01M15 12h.01M9 9h.01M15 9h.01"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>

                <div class="leading-tight">

                    <p
                        class="text-xs uppercase tracking-wider"
                        style="color: var(--muted-foreground);"
                    >
                        Politeknik Manufaktur Bandung
                    </p>

                    <p class="font-semibold font-serif-display">
                        Automation Engineering
                    </p>

                </div>

            </div>


            {{-- Judul --}}
            <h2 class="text-3xl font-bold font-serif-display mb-2">
                Buat Akun Alumni
            </h2>

            <p
                class="text-sm mb-7"
                style="color: var(--muted-foreground);"
            >
                Daftarkan akun Alumni untuk mengakses Tracer Study.
            </p>


            {{-- Informasi --}}
            <div
                class="text-sm rounded-lg px-4 py-3 mb-6"
                style="background: var(--secondary); color: var(--muted-foreground);"
            >
                Gunakan NIM yang sudah berstatus lulus.
                Setelah pendaftaran berhasil, akun Anda akan menunggu
                aktivasi dari Admin.
            </div>


            {{-- Error --}}
            @if ($errors->any())

                <div
                    class="text-sm rounded-lg px-4 py-3 mb-6"
                    style="background:#fdecea; color:#c62828;"
                >

                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach

                </div>

            @endif


            {{-- Form --}}
            <form
                method="POST"
                action="{{ route('alumni.register.store') }}"
            >

                @csrf


                {{-- NIM --}}
                <div class="mb-5">

                    <label
                        for="nim"
                        class="block text-sm font-medium mb-2"
                    >
                        NIM
                    </label>

                    <div class="flex gap-2">

                        <input
                            type="text"
                            id="nim"
                            name="nim"
                            value="{{ old('nim') }}"
                            placeholder="Masukkan NIM"
                            class="flex-1 rounded-lg px-4 py-3 text-sm border"
                            style="background: var(--card); border-color: var(--border);"
                            required
                            autofocus
                        >

                        <button
                            type="button"
                            id="btnCheckNim"
                            class="btn-primary text-white font-medium px-4 py-3 rounded-lg text-sm whitespace-nowrap"
                        >
                            Cek NIM
                        </button>

                    </div>

                    <p
                        class="text-xs mt-2"
                        style="color: var(--muted-foreground);"
                    >
                        Gunakan NIM yang tercatat sebagai mahasiswa dan
                        sudah berstatus lulus.
                    </p>


                    {{-- Hasil pengecekan NIM --}}
                    <div
                        id="nimResult"
                        style="
                            display: none;
                            margin-top: 12px;
                            padding: 12px;
                            border-radius: 8px;
                        "
                    ></div>

                </div>


                {{-- Email --}}
                <div class="mb-5">

                    <label
                        for="email"
                        class="block text-sm font-medium mb-2"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Cek NIM terlebih dahulu"
                        class="w-full rounded-lg px-4 py-3 text-sm border"
                        style="background: var(--card); border-color: var(--border);"
                        required
                        disabled
                    >

                </div>


                {{-- Password --}}
                <div class="mb-5">

                    <label
                        for="password"
                        class="block text-sm font-medium mb-2"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Cek NIM terlebih dahulu"
                        class="w-full rounded-lg px-4 py-3 text-sm border"
                        style="background: var(--card); border-color: var(--border);"
                        required
                        disabled
                    >

                </div>


                {{-- Konfirmasi Password --}}
                <div class="mb-6">

                    <label
                        for="password_confirmation"
                        class="block text-sm font-medium mb-2"
                    >
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Cek NIM terlebih dahulu"
                        class="w-full rounded-lg px-4 py-3 text-sm border"
                        style="background: var(--card); border-color: var(--border);"
                        required
                        disabled
                    >

                </div>


                {{-- Tombol --}}
                <button
                    type="submit"
                    id="btnRegister"
                    disabled
                    class="w-full text-white font-medium py-3 rounded-lg text-sm"
                    style="
                        background-color: #9ca3af;
                        cursor: not-allowed;
                    "
                >
                    Daftar Akun Alumni
                </button>


                {{-- Login --}}
                <p
                    class="text-center text-sm mt-6"
                    style="color: var(--muted-foreground);"
                >
                    Sudah punya akun Alumni?

                    <a
                        href="{{ route('alumni.login') }}"
                        class="font-medium hover:underline"
                        style="color: var(--primary);"
                    >
                        Login di sini
                    </a>
                </p>

            </form>


            {{-- Footer --}}
            <p
                class="text-center text-xs mt-10"
                style="color: var(--muted-foreground);"
            >
                © {{ now()->year }} Politeknik Manufaktur Bandung —
                Sistem Informasi Akreditasi
            </p>

        </div>

    </div>

</div>


<script>
    const btnCheckNim = document.getElementById('btnCheckNim');
    const nimInput = document.getElementById('nim');
    const nimResult = document.getElementById('nimResult');

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput =
        document.getElementById('password_confirmation');

    const btnRegister = document.getElementById('btnRegister');

    btnCheckNim.addEventListener('click', async function () {

        const nim = nimInput.value.trim();

        if (!nim) {

            nimResult.style.display = 'block';
            nimResult.style.backgroundColor = '#fef2f2';
            nimResult.style.color = '#dc2626';

            nimResult.innerHTML =
                'Silakan masukkan NIM terlebih dahulu.';

            return;
        }

        btnCheckNim.disabled = true;
        btnCheckNim.textContent = 'Mengecek...';

        nimResult.style.display = 'none';
        nimResult.innerHTML = '';

        try {

            const response = await fetch(
                '{{ route('alumni.check-nim') }}',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                    body: JSON.stringify({
                        nim: nim
                    })
                }
            );

            const result = await response.json();

            nimResult.style.display = 'block';

            if (!response.ok || !result.success) {

                nimResult.style.backgroundColor = '#fef2f2';
                nimResult.style.color = '#dc2626';

                nimResult.innerHTML =
                    result.message ??
                    'NIM tidak dapat diverifikasi.';

                return;
            }

            nimResult.style.backgroundColor = '#f0fdf4';
            nimResult.style.color = '#166534';

            nimResult.innerHTML = `
                <div style="font-weight: 600; margin-bottom: 4px;">
                    ✓ NIM berhasil diverifikasi
                </div>

                <div style="font-size: 14px;">
                    <strong>Nama:</strong> ${result.data.nama}
                </div>

                <div style="font-size: 14px;">
                    <strong>Program Studi:</strong> ${result.data.prodi}
                </div>
            `;

            emailInput.disabled = false;
            passwordInput.disabled = false;
            passwordConfirmationInput.disabled = false;

            emailInput.placeholder = 'Masukkan email';
            passwordInput.placeholder = 'Minimal 8 karakter';
            passwordConfirmationInput.placeholder =
                'Ulangi password';

            btnRegister.disabled = false;
            btnRegister.style.backgroundColor = 'var(--primary)';
            btnRegister.style.cursor = 'pointer';

        } catch (error) {

            nimResult.style.display = 'block';
            nimResult.style.backgroundColor = '#fef2f2';
            nimResult.style.color = '#dc2626';

            nimResult.innerHTML =
                'Terjadi kesalahan saat mengecek NIM. Silakan coba lagi.';

        } finally {

            btnCheckNim.disabled = false;
            btnCheckNim.textContent = 'Cek NIM';

        }

    });
</script>

</body>
</html>