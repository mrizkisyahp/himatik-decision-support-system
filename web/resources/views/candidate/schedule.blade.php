@extends('candidate.layout', ['title' => 'Pemilihan Departemen & Jadwal'])

@section('content')
    <div class="space-y-8">
        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @php
            $selectedBooking = $candidate->selectedInterviewSchedule;
            $selectedSlot = $selectedBooking?->schedule;
            $firstChoice = $candidate->first_choice_department;
            $secondChoice = $candidate->second_choice_department;
        @endphp

        <!-- Header Section with Combined Info -->
        <div class="relative overflow-hidden rounded-[2rem] bg-white p-8 border border-[#E2E8F0] shadow-sm flex flex-col lg:flex-row lg:justify-between gap-8">
            <!-- Subtle mesh gradient background -->
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 pointer-events-none"></div>
            <div class="absolute bottom-0 right-40 -mb-20 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl lg:w-1/2 flex flex-col justify-center">
                <p class="text-xs font-bold text-blue-600 mb-2 uppercase tracking-widest">Langkah Selanjutnya</p>
                <h1 class="text-3xl md:text-4xl font-black text-[#0F172A] tracking-tight">Pilih Departemen & Jadwal</h1>
                <p class="mt-3 text-[#64748B] text-sm md:text-base font-medium leading-relaxed">
                    Pilih slot jadwal wawancaramu segera sebelum kuota penuh. Pastikan memilih jadwal yang sesuai dengan ketersediaan waktumu.
                </p>
            </div>

            <!-- Informasi Pilihan -->
            <div class="relative z-10 lg:w-1/2 flex flex-col bg-[#F8FAFC]/80 backdrop-blur-sm rounded-2xl border border-[#E2E8F0] p-6 lg:ml-auto">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[0.65rem] font-bold text-[#64748B] uppercase tracking-widest mb-1.5">Pilihan 1</p>
                        <h2 class="text-sm font-black text-[#0F172A]">{{ $firstChoice?->name ?? 'Belum memilih' }}</h2>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-bold text-[#64748B] uppercase tracking-widest mb-1.5">Pilihan 2</p>
                        <h2 class="text-sm font-black text-[#0F172A]">{{ $secondChoice?->name ?? '-' }}</h2>
                    </div>
                </div>

                @if($openRecruitment?->interview_location || $firstChoice?->contact_person || $openRecruitment?->interview_requirements)
                    <div class="mt-4 pt-4 border-t border-[#E2E8F0] space-y-4">
                        <div class="flex flex-wrap gap-6">
                            @if($openRecruitment?->interview_location)
                                <div class="flex gap-3 items-start">
                                    <div class="mt-0.5 text-blue-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#0F172A] text-xs">Lokasi Wawancara</p>
                                        <p class="mt-0.5 text-xs font-medium text-[#64748B]">{{ $openRecruitment->interview_location }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($firstChoice?->contact_person)
                                <div class="flex gap-3 items-start">
                                    <div class="mt-0.5 text-indigo-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#0F172A] text-xs">Narahubung (CP)</p>
                                        <p class="mt-0.5 text-xs font-medium text-[#64748B]">{{ $firstChoice->contact_person }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($openRecruitment?->interview_requirements)
                            <div class="pt-3">
                                <div class="flex items-center gap-1.5 mb-1.5">
                                    <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p class="font-bold text-[#0F172A] text-xs">Persyaratan / Catatan</p>
                                </div>
                                <div class="text-xs font-medium text-[#64748B] leading-relaxed">
                                    {!! nl2br(e($openRecruitment->interview_requirements)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Matriks Penjadwalan -->
        <section class="space-y-4">
            <div class="flex items-center justify-between mb-2 px-2">
                <h2 class="text-lg font-black text-[#0F172A]">Slot Tersedia</h2>
            </div>

            @if(empty($dates))
                <div class="rounded-[2rem] border border-[#E2E8F0] bg-white p-12 text-center shadow-sm">
                    <div class="mx-auto h-16 w-16 bg-[#F8FAFC] rounded-2xl border border-[#E2E8F0] flex items-center justify-center mb-5">
                        <svg class="h-8 w-8 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-black text-[#0F172A]">Belum Ada Jadwal</h4>
                    <p class="text-sm font-medium text-[#64748B] mt-2">Belum ada slot wawancara yang tersedia untuk departemen yang kamu pilih. Silakan hubungi admin atau tunggu informasi lebih lanjut.</p>
                </div>
            @else
                <div class="mb-4 flex items-center justify-between rounded-[1rem] bg-white p-4 border border-[#D8E2F3] shadow-sm">
                    <div class="flex items-center gap-5 flex-wrap">
                        <div class="flex items-center gap-2"><div class="h-4 w-4 rounded bg-[#FFFFFF] border border-[#4A90E2]"></div> <span class="text-xs font-bold text-[#64748B]">Bisa Dipilih</span></div>
                        <div class="flex items-center gap-2"><div class="h-4 w-4 rounded bg-[#223872] border border-[#223872]"></div> <span class="text-xs font-bold text-[#64748B]">Jadwal Terpilih</span></div>
                        <div class="flex items-center gap-2"><div class="h-4 w-4 rounded bg-[#F8FAFC] border border-dashed border-[#E2E8F0]"></div> <span class="text-xs font-bold text-[#64748B]">Tidak Tersedia / Penuh</span></div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-[#D8E2F3] bg-white shadow-sm overflow-hidden">
                    <div class="overflow-x-auto max-h-[60vh]">
                        <table class="w-full text-left text-sm text-[#333333] border-collapse relative">
                            <thead class="bg-[#F4F7FF] sticky top-0 z-20">
                                <tr>
                                    <th class="border-b border-r border-[#D8E2F3] px-4 py-3 font-bold text-[#223872] whitespace-nowrap sticky left-0 bg-[#F4F7FF] z-30 w-24 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">WAKTU</th>
                                    @foreach($dates as $date)
                                        <th class="border-b border-r border-[#D8E2F3] px-4 py-3 font-bold text-[#223872] text-center min-w-[200px] bg-[#F4F7FF]">
                                            {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d M Y') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($timeSlots as $ts)
                                    @php
                                        $timeKey = $ts['start_time'] . '|' . $ts['end_time'];
                                        $displayTime = \Carbon\Carbon::parse($ts['start_time'])->format('H:i') . ' - ' . \Carbon\Carbon::parse($ts['end_time'])->format('H:i');
                                    @endphp
                                    <tr class="border-b border-[#D8E2F3] last:border-0 hover:bg-[#F4F7FF]/50 transition-colors">
                                        <td class="border-r border-[#D8E2F3] px-4 py-3 font-black text-[#64748B] whitespace-nowrap sticky left-0 bg-white z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                            {{ $displayTime }}
                                        </td>
                                        @foreach($dates as $date)
                                            @php
                                                $dateKey = \Carbon\Carbon::parse($date)->format('Y-m-d');
                                                $schedule = $schedules[$dateKey][$timeKey] ?? null;
                                            @endphp
                                            <td class="border-r border-[#D8E2F3] p-2 align-middle">
                                                @if($schedule)
                                                    @php
                                                        $isSelected = $currentBookedSlotId === $schedule->id;
                                                        $isBooked = $schedule->booking !== null;
                                                        $isBlocked = $schedule->is_blocked;
                                                        $canSelect = !$isBooked && !$isBlocked && !$isSelected;
                                                    @endphp

                                                    @if($isSelected)
                                                        <div class="h-full w-full min-h-[48px] rounded-xl bg-[#223872] border-2 border-[#223872] p-2 flex items-center justify-center shadow-md">
                                                            <div class="text-xs font-black text-white text-center flex items-center gap-1">
                                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                                                Terpilih
                                                            </div>
                                                        </div>
                                                    @elseif($canSelect)
                                                        <form action="{{ route('candidate.schedule.book') }}" method="POST" class="h-full w-full m-0">
                                                            @csrf
                                                            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                            <button type="submit" class="h-full w-full min-h-[48px] rounded-xl border-2 border-[#4A90E2] bg-white p-2 text-center transition-all hover:bg-[#F4F7FF] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[#4A90E2] focus:ring-offset-1 text-[#4A90E2] hover:shadow-sm font-bold text-xs group">
                                                                <span class="group-hover:hidden text-[#4A90E2]">Pilih Slot</span>
                                                                <span class="hidden group-hover:inline-flex items-center justify-center gap-1">
                                                                    Amankan
                                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                                                </span>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <div class="h-full w-full min-h-[48px] rounded-xl bg-[#F8FAFC] border border-dashed border-[#E2E8F0] p-2 flex items-center justify-center opacity-70">
                                                            <div class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-wider text-center">
                                                                @if($isBlocked) Blocked @else Penuh @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="h-full w-full min-h-[48px] rounded-xl bg-transparent"></div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection