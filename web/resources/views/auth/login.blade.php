<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun HIMATIK PNJ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#eef2ff] font-sans text-[#0f172a] antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[minmax(320px,1.15fr)_minmax(480px,0.85fr)]">
        <aside class="relative hidden overflow-hidden bg-[#203a78] lg:flex">
            <img
                src="{{ asset('images/1776510571221-foto_bersama.png') }}"
                alt="HIMATIK PNJ"
                class="absolute inset-0 h-full w-full object-cover opacity-20"
            >
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(21,37,84,0.76)_0%,rgba(34,56,114,0.96)_100%)]"></div>

            <div class="relative flex w-full flex-col justify-between px-12 py-10 xl:px-16 xl:py-14">
                <div class="flex items-center justify-between">
                    <div class="inline-flex items-center gap-3 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-medium text-white backdrop-blur">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo HIMATIK" class="h-10 w-10 object-contain">
                        <span>Recruitment DSS</span>
                    </div>

                    <a
                        href="{{ route('docs.blade') }}#login"
                        class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-medium text-white transition hover:bg-white/20"
                    >
                        Blade Docs
                    </a>
                </div>

                <div class="max-w-xl space-y-6">
                    <span class="inline-flex rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-medium text-white/85 backdrop-blur">
                        Sistem Rekrutmen HIMATIK PNJ
                    </span>
                    <div class="space-y-4">
                        <h1 class="max-w-lg text-5xl font-extrabold tracking-tight text-white xl:text-6xl">
                            Masuk ke aplikasi rekrutmen yang sama, dengan layout yang rapi untuk desktop.
                        </h1>
                        <p class="max-w-lg text-base leading-7 text-white/75 xl:text-lg">
                            Kandidat, interviewer, dan admin tetap menggunakan alur autentikasi yang sama.
                            Tampilan desktop memberi ruang baca yang lebih nyaman tanpa mengubah perilaku halaman mobile.
                        </p>
                    </div>
                </div>

                <div class="grid max-w-xl gap-4 xl:grid-cols-3">
                    <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                        <p class="text-sm text-white/70">Role</p>
                        <p class="mt-1 text-lg font-semibold text-white">Candidate</p>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                        <p class="text-sm text-white/70">Role</p>
                        <p class="mt-1 text-lg font-semibold text-white">Interviewer</p>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                        <p class="text-sm text-white/70">Role</p>
                        <p class="mt-1 text-lg font-semibold text-white">Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex min-h-screen items-center justify-center px-5 py-8 sm:px-8 lg:px-10 xl:px-16">
            <div class="w-full max-w-[26rem] lg:max-w-[29rem]">
                <div class="mb-6 flex items-center justify-end lg:hidden">
                    <a
                        href="{{ route('docs.blade') }}#login"
                        class="rounded-full border border-[#cfd8f2] bg-white/80 px-4 py-2 text-xs font-semibold text-[#223872] shadow-sm"
                    >
                        Blade Docs
                    </a>
                </div>

                <section class="rounded-[28px] border border-white/70 bg-white/[0.84] px-5 py-7 shadow-[0_18px_50px_rgba(34,56,114,0.10)] backdrop-blur sm:px-7 sm:py-8 lg:rounded-[32px] lg:px-8 lg:py-9">
                    <div class="mx-auto mb-8 flex max-w-sm flex-col items-center text-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo HIMATIK" class="mb-6 h-28 w-28 object-contain sm:h-32 sm:w-32">
                        <h1 class="text-left text-[2.1rem] font-extrabold leading-tight tracking-tight text-[#111827] sm:text-[2.35rem] lg:text-center">
                            Masuk Akun HIMATIK PNJ
                        </h1>
                    </div>

                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="space-y-2">
                            <label for="email" class="block text-[0.97rem] font-medium text-[#1f2937]">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="Masukkan Email"
                                autocomplete="email"
                                class="h-14 w-full rounded-2xl border border-[#b9c3da] bg-transparent px-4 text-[0.98rem] text-[#111827] outline-none transition placeholder:text-[#9aa3b4] focus:border-[#223872] focus:ring-4 focus:ring-[#223872]/10"
                                required
                            >
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="block text-[0.97rem] font-medium text-[#1f2937]">Kata Sandi</label>
                            <div class="relative">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="Masukkan Kata Sandi"
                                    autocomplete="current-password"
                                    class="h-14 w-full rounded-2xl border border-[#b9c3da] bg-transparent px-4 pr-14 text-[0.98rem] text-[#111827] outline-none transition placeholder:text-[#9aa3b4] focus:border-[#223872] focus:ring-4 focus:ring-[#223872]/10"
                                    required
                                >
                                <button
                                    type="button"
                                    data-password-toggle
                                    data-target="password"
                                    aria-label="Tampilkan kata sandi"
                                    class="absolute inset-y-0 right-3 inline-flex items-center justify-center rounded-full px-2 text-[#64748b] transition hover:text-[#223872]"
                                >
                                    <svg data-eye-open xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2.06 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.88 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.88 0Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg data-eye-closed xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m3 3 18 18"/>
                                        <path d="M10.58 10.58a2 2 0 1 0 2.83 2.83"/>
                                        <path d="M9.88 5.09A10.94 10.94 0 0 1 12 4.88c5.05 0 9.27 3.11 10 7.12a11.06 11.06 0 0 1-2.17 4.18"/>
                                        <path d="M6.61 6.61A11.06 11.06 0 0 0 2 12c.73 4.01 4.95 7.12 10 7.12a10.94 10.94 0 0 0 5.39-1.39"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-1">
                            <label class="inline-flex items-center gap-2 text-sm text-[#475569]">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    class="h-4 w-4 rounded border-[#b9c3da] text-[#223872] focus:ring-[#223872]/20"
                                >
                                <span>Tetap masuk</span>
                            </label>
                        </div>

                        <button
                            type="submit"
                            class="mt-2 inline-flex h-14 w-full items-center justify-center rounded-2xl bg-[#223872] px-5 text-[1rem] font-semibold text-white transition hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#223872]/20"
                        >
                            Masuk ke Aplikasi
                        </button>
                    </form>

                    <div class="mt-6 text-center text-sm text-[#475569]">
                        <p class="mb-1">Ingin mendaftar menjadi anggota?</p>
                        <a href="{{ route('user.register.view') }}" class="font-semibold text-[#223872] hover:text-[#1b2f60] hover:underline">
                            Daftar Sekarang
                        </a>
                    </div>
                </section>
            </div>
        </main>
    </div>

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
