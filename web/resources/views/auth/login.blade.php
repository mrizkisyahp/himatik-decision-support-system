<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun HIMATIK PNJ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4F7FF] font-sans text-[#333333] antialiased">
    <main class="flex min-h-screen items-center justify-center px-5 py-8">
        <div class="w-full max-w-[25rem]">
            <a href="{{ route('landing') }}" class="mb-6 inline-flex items-center gap-2 text-sm font-bold text-[#223872] transition hover:text-[#4A90E2]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Beranda
            </a>

            <section class="rounded-[1.75rem] border border-[#dce5f8] bg-white/78 px-5 py-8 shadow-[0_16px_40px_rgba(34,56,114,0.08)] sm:px-7">
                <div class="mb-7 text-center">
                    <img src="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" alt="Logo HIMATIK" class="mx-auto mb-5 h-28 w-28 object-contain">
                    <h1 class="text-3xl font-black leading-tight tracking-tight text-[#111827]">
                        Masuk Akun HIMATIK PNJ
                    </h1>
                </div>

                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-[#333333]">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan Email"
                            autocomplete="email"
                            class="h-[3.25rem] w-full rounded-2xl border border-[#aeb8cc] bg-transparent px-4 text-[0.98rem] text-[#333333] outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15"
                            required
                        >
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-[#333333]">Kata Sandi</label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Masukkan Kata Sandi"
                                autocomplete="current-password"
                                class="h-[3.25rem] w-full rounded-2xl border border-[#aeb8cc] bg-transparent px-4 pr-14 text-[0.98rem] text-[#333333] outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15"
                                required
                            >
                            <button
                                type="button"
                                data-password-toggle
                                data-target="password"
                                aria-label="Tampilkan kata sandi"
                                class="absolute inset-y-0 right-3 inline-flex items-center justify-center rounded-full px-2 text-[#64748b] transition hover:text-[#4A90E2]"
                            >
                                <svg data-eye-open xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2.06 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.88 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.88 0Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg data-eye-closed xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m3 3 18 18" />
                                    <path d="M10.58 10.58a2 2 0 1 0 2.83 2.83" />
                                    <path d="M9.88 5.09A10.94 10.94 0 0 1 12 4.88c5.05 0 9.27 3.11 10 7.12a11.06 11.06 0 0 1-2.17 4.18" />
                                    <path d="M6.61 6.61A11.06 11.06 0 0 0 2 12c.73 4.01 4.95 7.12 10 7.12a10.94 10.94 0 0 0 5.39-1.39" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <label class="inline-flex items-center gap-2 text-sm font-medium text-[#64748b]">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="h-4 w-4 rounded border-[#aeb8cc] text-[#223872] focus:ring-[#4A90E2]/20"
                        >
                        <span>Tetap masuk</span>
                    </label>

                    <button
                        type="submit"
                        class="inline-flex h-[3.25rem] w-full items-center justify-center rounded-2xl bg-[#223872] px-5 text-[1rem] font-semibold text-white transition hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20"
                    >
                        Masuk Portal
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-[#333333]">
                    <p class="mb-1">Ingin mendaftar menjadi anggota?</p>
                    <a href="{{ route('user.register.view') }}" class="font-bold text-[#223872] transition hover:text-[#4A90E2] hover:underline">
                        Daftar Sekarang
                    </a>
                </div>
            </section>
        </div>
    </main>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const openIcon = button.querySelector('[data-eye-open]');
                const closedIcon = button.querySelector('[data-eye-closed]');

                if (!input) {
                    return;
                }

                const nextType = input.type === 'password' ? 'text' : 'password';
                input.type = nextType;
                openIcon?.classList.toggle('hidden', nextType === 'text');
                closedIcon?.classList.toggle('hidden', nextType !== 'text');
            });
        });
    </script>
</body>

</html>
