<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kandidat - HIMATIK PNJ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4F7FF] font-sans text-[#333333] antialiased">
    @php
        $selectedSlot = $candidate->selectedInterviewSchedule?->schedule;
    @endphp

    <main class="mx-auto min-h-screen w-full max-w-5xl bg-[#F4F7FF]">
        <header class="sticky top-0 z-20 border-b border-[#b7c7ff] bg-white/86 px-5 py-4 backdrop-blur sm:px-8">
            <div class="flex items-center justify-between gap-4">
                <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-[#333333] sm:hidden" aria-label="Menu">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>

                <div class="hidden text-sm font-black text-[#223872] sm:block">Dashboard Kandidat</div>

                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" alt="Logo HIMATIK" class="h-9 w-9 object-contain">
                    <form action="{{ route('logout') }}" method="POST" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="text-xs font-extrabold text-red-500 transition hover:text-red-600">Keluar</button>
                    </form>
                </div>
            </div>
        </header>

        <div class="px-5 py-6 sm:px-8 sm:py-8">
            @if ($errors->any())
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <p class="text-xs font-medium text-[#333333]">
                {{ now()->locale('id')->translatedFormat('l, d F Y') }}
            </p>
            <h1 class="mt-3 text-2xl font-black tracking-tight text-[#111827] sm:text-3xl">
                Halo, {{ $candidate->nickname ?: auth()->user()->name }}!
            </h1>

            @if($announcement && $announcement->is_published)
                <div class="mt-6">
                    @if($announcement->status === 'accepted')
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center justify-between rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                            <div>
                                <h2 class="text-xl font-black text-emerald-800">Selamat! Anda Dinyatakan Lulus</h2>
                                <p class="mt-1 text-sm text-emerald-700">
                                    Anda diterima sebagai <strong>{{ ucfirst($candidate->candidate_type) }}</strong> di <strong>{{ $announcement->assignedDepartment?->name ?? 'Staff Umum' }}</strong>.
                                </p>
                            </div>
                            <div class="shrink-0 text-5xl">🎉</div>
                        </div>
                    @elseif($announcement->status === 'rejected')
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center justify-between rounded-2xl border border-red-200 bg-red-50 p-6 shadow-sm">
                            <div>
                                <h2 class="text-xl font-black text-red-800">Mohon Maaf, Anda Belum Lulus</h2>
                                <p class="mt-1 text-sm text-red-700">
                                    Tetap semangat dan jangan menyerah. Terima kasih telah mengikuti proses seleksi HIMATIK PNJ.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="mt-6 grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
                <section>
                    <h2 class="text-lg font-black text-[#111827]">Jadwal Wawancara</h2>

                    @if ($selectedSlot)
                        <div class="mt-3 rounded-2xl border border-[#dce5f8] bg-white p-5 shadow-[0_12px_30px_rgba(34,56,114,0.08)]">
                            <h3 class="text-xl font-black text-[#111827]">{{ $selectedSlot->session_name }}</h3>
                            <p class="mt-2 text-sm leading-6 text-[#64748b]">
                                {{ $selectedSlot->scheduled_at?->locale('id')?->translatedFormat('d F Y, H:i') }}
                                @if($selectedSlot->location)
                                    <br>{{ $selectedSlot->location }}
                                @endif
                            </p>
                        </div>
                    @else
                        <div class="mt-3 rounded-2xl border border-[#aeb8cc] bg-transparent px-5 py-9 text-center text-sm text-[#929aaa]">
                            <div class="text-lg font-black tracking-[0.24em]">...</div>
                            <p class="mt-2">Belum Ada</p>
                        </div>
                    @endif
                </section>

                <section>
                    <h2 class="text-lg font-black text-[#111827]">Daftar Rekrutmen</h2>

                    <div class="mt-3 rounded-2xl bg-white p-5 shadow-[0_14px_34px_rgba(34,56,114,0.14)]">
                        <h3 class="text-lg font-black leading-tight text-[#111827]">
                            Open Recruitment {{ ucfirst($candidate->candidate_type) }} HIMATIK PNJ
                        </h3>

                        <div class="mt-4 flex items-start gap-3 text-xs leading-5 text-[#333333]">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#223872]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v12A1.5 1.5 0 0 1 18.75 20.25H5.25A1.5 1.5 0 0 1 3.75 18.75v-12A1.5 1.5 0 0 1 5.25 5.25Z" />
                            </svg>
                            <p>
                                OO September OOOO s.d.<br>
                                OO September OOOO
                            </p>
                        </div>

                        <a href="{{ route('candidate.schedule.view') }}" class="mt-5 inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-[#223872] px-4 text-sm font-semibold text-white transition hover:bg-[#1b2f60]">
                            Daftar
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M8.25 13.5h3M8.25 16.5h6.75" />
                            </svg>
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>

</html>
