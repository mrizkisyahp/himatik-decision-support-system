<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun HIMATIK PNJ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4F7FF] font-sans text-[#333333] antialiased">
    <main class="flex min-h-screen justify-center px-0 py-0 sm:items-center sm:px-5 sm:py-8">
        <section
            class="flex min-h-screen w-full max-w-[25.5rem] flex-col bg-[#F4F7FF] sm:min-h-0 sm:overflow-hidden sm:rounded-[1.4rem] sm:border sm:border-[#dce5f8] sm:shadow-[0_16px_40px_rgba(34,56,114,0.08)]">
            <div class="border-b border-[#b7c7ff] bg-white/40 px-6 pb-5 pt-16 sm:px-7 sm:pt-8">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-3 text-sm font-extrabold text-[#223872] transition hover:text-[#4A90E2]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="flex flex-1 flex-col px-6 py-7 sm:px-7">
                <div>
                    <h1 class="text-[1.85rem] font-black leading-tight tracking-tight text-[#111827]">
                        Daftar Akun HIMATIK PNJ
                    </h1>
                    <p class="mt-3 text-[0.95rem] leading-6 text-[#929aaa]">
                        Buat akun HIMATIK PNJ untuk melanjutkan mendaftarkan diri sebagai calon anggota HIMATIK PNJ.
                    </p>
                </div>

                @if ($errors->any())
                    <div
                        class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div
                        class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('user.register.post') }}" method="POST" class="mt-6 flex flex-1 flex-col">
                    @csrf
                    <input type="hidden" name="candidate_type" value="{{ old('candidate_type', $candidateType ?? 'staff') }}">

                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-[#333333]">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}"
                                placeholder="Masukkan Email" autocomplete="email"
                                class="h-[3.25rem] w-full rounded-2xl border border-[#aeb8cc] bg-transparent px-4 text-[0.98rem] text-[#333333] outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15"
                                required>
                            @error('email')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-semibold text-[#333333]">Kata Sandi</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" placeholder="Masukkan Kata Sandi"
                                    autocomplete="new-password"
                                    class="h-[3.25rem] w-full rounded-2xl border border-[#aeb8cc] bg-transparent px-4 pr-14 text-[0.98rem] text-[#333333] outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15"
                                    required>
                                <button type="button" data-password-toggle data-target="password"
                                    aria-label="Tampilkan kata sandi"
                                    class="absolute inset-y-0 right-3 inline-flex items-center justify-center rounded-full px-2 text-[#111827] transition hover:text-[#4A90E2]">
                                    <svg data-eye-open xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M2.06 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.88 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.88 0Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg data-eye-closed xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m3 3 18 18" />
                                        <path d="M10.58 10.58a2 2 0 1 0 2.83 2.83" />
                                        <path
                                            d="M9.88 5.09A10.94 10.94 0 0 1 12 4.88c5.05 0 9.27 3.11 10 7.12a11.06 11.06 0 0 1-2.17 4.18" />
                                        <path
                                            d="M6.61 6.61A11.06 11.06 0 0 0 2 12c.73 4.01 4.95 7.12 10 7.12a10.94 10.94 0 0 0 5.39-1.39" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation"
                                class="block text-sm font-semibold text-[#333333]">Konfirmasi Kata Sandi</label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    placeholder="Masukkan Kata Sandi" autocomplete="new-password"
                                    class="h-[3.25rem] w-full rounded-2xl border border-[#aeb8cc] bg-transparent px-4 pr-14 text-[0.98rem] text-[#333333] outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15"
                                    required>
                                <button type="button" data-password-toggle data-target="password_confirmation"
                                    aria-label="Tampilkan konfirmasi kata sandi"
                                    class="absolute inset-y-0 right-3 inline-flex items-center justify-center rounded-full px-2 text-[#111827] transition hover:text-[#4A90E2]">
                                    <svg data-eye-open xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M2.06 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.88 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.88 0Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg data-eye-closed xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m3 3 18 18" />
                                        <path d="M10.58 10.58a2 2 0 1 0 2.83 2.83" />
                                        <path
                                            d="M9.88 5.09A10.94 10.94 0 0 1 12 4.88c5.05 0 9.27 3.11 10 7.12a11.06 11.06 0 0 1-2.17 4.18" />
                                        <path
                                            d="M6.61 6.61A11.06 11.06 0 0 0 2 12c.73 4.01 4.95 7.12 10 7.12a10.94 10.94 0 0 0 5.39-1.39" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-4 sm:mt-8">
                        <button type="submit"
                            class="inline-flex h-[3.25rem] w-full items-center justify-center gap-2 rounded-2xl bg-[#223872] px-5 text-[1rem] font-semibold text-white transition hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20">
                            Berikutnya
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </section>
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
