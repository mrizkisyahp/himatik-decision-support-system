<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - HIMATIK PNJ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4F7FF] font-sans text-[#333333] antialiased">
    <main class="flex min-h-screen justify-center px-0 py-0 sm:items-center sm:px-5 sm:py-8">
        <section class="flex min-h-screen w-full max-w-[25.5rem] flex-col bg-[#F4F7FF] sm:min-h-0 sm:overflow-hidden sm:rounded-[1.4rem] sm:border sm:border-[#dce5f8] sm:shadow-[0_16px_40px_rgba(34,56,114,0.08)]">
            <div class="border-b border-[#b7c7ff] bg-white/40 px-6 pb-5 pt-16 sm:px-7 sm:pt-8">
                <a href="{{ route('user.register.view') }}" class="inline-flex items-center gap-3 text-sm font-extrabold text-[#223872] transition hover:text-[#4A90E2]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="flex flex-1 flex-col px-6 py-7 sm:px-7">
                <div>
                    <h1 class="text-[1.85rem] font-black leading-tight tracking-tight text-[#111827]">
                        Verifikasi Email
                    </h1>
                    <p class="mt-3 text-[0.95rem] leading-6 text-[#929aaa]">
                        Masukkan kode OTP yang telah diberikan ke email yang telah kamu masukkan.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <form id="otp-verify-form" action="{{ route('candidate.otp.verify') }}" method="POST" class="mt-6 flex flex-1 flex-col">
                    @csrf

                    <div class="space-y-2">
                        <label for="otp" class="block text-sm font-semibold text-[#333333]">Kode OTP</label>
                        <input
                            id="otp"
                            name="otp"
                            type="text"
                            value="{{ old('otp') }}"
                            inputmode="numeric"
                            pattern="[0-9]{6}"
                            maxlength="6"
                            placeholder="Masukkan Kode OTP"
                            autocomplete="one-time-code"
                            class="h-[3.25rem] w-full rounded-2xl border border-[#aeb8cc] bg-transparent px-4 text-[0.98rem] tracking-[0.18em] text-[#333333] outline-none transition placeholder:tracking-normal placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15"
                            required
                        >
                    </div>
                </form>

                <div class="mt-3 text-center text-xs text-[#333333]">
                    <span>Tidak terkirim?</span>
                    <form action="{{ route('candidate.otp.resend') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="font-extrabold text-[#223872] underline underline-offset-2 transition hover:text-[#4A90E2]">
                            Kirim lagi
                        </button>
                    </form>
                </div>

                <div class="mt-auto pt-10">
                    <button
                        type="submit"
                        form="otp-verify-form"
                        class="inline-flex h-[3.25rem] w-full items-center justify-center gap-2 rounded-2xl bg-[#223872] px-5 text-[1rem] font-semibold text-white transition hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20"
                    >
                        Verifikasi
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12.75 11.25 15 15 9.75" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </section>
    </main>

</body>

</html>
