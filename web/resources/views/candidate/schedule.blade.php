<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jadwal Wawancara - HIMATIK PNJ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4F7FF] font-sans text-[#333333] antialiased">
    @php
        $selectedBooking = $candidate->selectedInterviewSchedule;
        $selectedSlot = $selectedBooking?->schedule;
        $firstChoice = $candidate->first_choice_department;
    @endphp

    <main class="mx-auto min-h-screen w-full max-w-4xl bg-[#F4F7FF]">
        <header class="sticky top-0 z-20 border-b border-[#b7c7ff] bg-white/86 px-5 py-4 backdrop-blur sm:px-8">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('candidate.dashboard') }}" class="inline-flex items-center gap-3 text-sm font-extrabold text-[#223872] transition hover:text-[#4A90E2]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>

                <img src="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" alt="Logo HIMATIK" class="h-9 w-9 object-contain">
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

            <div class="max-w-2xl">
                <h1 class="text-2xl font-black leading-tight tracking-tight text-[#111827] sm:text-3xl">
                    Pilih Jadwal Wawancara
                </h1>
                <p class="mt-2 text-sm leading-6 text-[#929aaa]">
                    Pilih salah satu slot wawancara untuk proses rekrutmen HIMATIK PNJ.
                </p>
            </div>

            <section class="mt-6 rounded-2xl border border-[#dce5f8] bg-white p-5 shadow-[0_12px_30px_rgba(34,56,114,0.08)]">
                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-[#4A90E2]">Departemen/Biro</p>
                <h2 class="mt-1 text-xl font-black text-[#111827]">{{ $firstChoice?->name ?? 'Belum memilih departemen' }}</h2>

                @if($openRecruitment?->interview_location || $openRecruitment?->interview_requirements || $firstChoice?->contact_person)
                    <div class="mt-5 grid gap-4 rounded-xl border border-[#D8E2F3] bg-[#F4F7FF] p-4 text-sm sm:grid-cols-2">
                        @if($openRecruitment?->interview_location)
                            <div>
                                <p class="font-bold text-[#223872]">Lokasi Pelaksanaan</p>
                                <p class="mt-1 text-[#64748b]">{{ $openRecruitment->interview_location }}</p>
                            </div>
                        @endif
                        @if($firstChoice?->contact_person)
                            <div>
                                <p class="font-bold text-[#223872]">Narahubung (CP)</p>
                                <p class="mt-1 text-[#64748b]">{{ $firstChoice->contact_person }}</p>
                            </div>
                        @endif
                        @if($openRecruitment?->interview_requirements)
                            <div class="sm:col-span-2 mt-2">
                                <p class="font-bold text-[#223872]">Persyaratan / Hal yang perlu disiapkan</p>
                                <div class="mt-1 text-[#64748b] leading-relaxed">
                                    {!! nl2br(e($openRecruitment->interview_requirements)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($selectedSlot)
                    <div class="mt-5 rounded-2xl border border-[#b7c7ff] bg-[#F4F7FF] p-4">
                        <p class="text-sm font-extrabold text-[#223872]">Jadwal Terpilih</p>
                        <p class="mt-2 text-sm leading-6 text-[#64748b]">
                            {{ $selectedSlot->session_name }}<br>
                            {{ $selectedSlot->scheduled_at?->locale('id')?->translatedFormat('d F Y, H:i') }}
                            @if($selectedSlot->location)
                                <br>{{ $selectedSlot->location }}
                            @endif
                        </p>
                    </div>
                @endif
            </section>

            <section class="mt-6">
                <h2 class="text-lg font-black text-[#111827]">Slot Tersedia</h2>

                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    @forelse($availableSlots as $slot)
                        <form action="{{ route('candidate.schedule.book') }}" method="POST" class="rounded-2xl border border-[#dce5f8] bg-white p-4 shadow-[0_10px_24px_rgba(34,56,114,0.07)]">
                            @csrf
                            <input type="hidden" name="schedule_id" value="{{ $slot->id }}">

                            <div class="flex h-full flex-col">
                                <p class="text-sm font-black text-[#223872]">{{ $slot->session_name }}</p>
                                <p class="mt-2 text-xs leading-5 text-[#64748b]">
                                    {{ $slot->scheduled_at?->locale('id')?->translatedFormat('d F Y, H:i') }}
                                    @if($slot->location)
                                        <br>{{ $slot->location }}
                                    @endif
                                </p>

                                <button type="submit" class="mt-4 inline-flex h-10 w-full items-center justify-center rounded-xl bg-[#223872] px-4 text-sm font-semibold text-white transition hover:bg-[#1b2f60] disabled:cursor-not-allowed disabled:bg-[#aeb8cc]" @disabled($currentBookedSlotId === $slot->id)>
                                    {{ $currentBookedSlotId === $slot->id ? 'Sudah Dipilih' : 'Pilih Jadwal' }}
                                </button>
                            </div>
                        </form>
                    @empty
                        <div class="rounded-2xl border border-[#aeb8cc] bg-transparent px-5 py-9 text-center text-sm text-[#929aaa] md:col-span-2">
                            <div class="text-lg font-black tracking-[0.24em]">...</div>
                            <p class="mt-2">Belum ada slot wawancara yang tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</body>

</html>
