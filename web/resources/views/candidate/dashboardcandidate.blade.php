@extends('candidate.layout', ['title' => 'Dashboard Kandidat'])

@section('content')
@php
    $documentFields = [
        'photo_path'               => 'Pas Foto',
        'instagram_proof_path'     => 'Bukti Instagram',
        'youtube_proof_path'       => 'Bukti Youtube',
        'political_statement_path' => 'Surat Pernyataan',
        'candidate_signature_path' => 'Tanda Tangan Kandidat',
        'parent_signature_path'    => 'Tanda Tangan Orang Tua',
    ];

    $statusLabel = [
        'registered' => 'Registered',
        'scheduled'  => 'Scheduled',
        'evaluated'  => 'Evaluated',
        'completed'  => 'Completed',
        'accepted'   => 'Accepted',
        'rejected'   => 'Rejected',
    ];
@endphp
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

    <!-- Header Section -->
    <div class="relative overflow-hidden rounded-[2rem] bg-white p-8 border border-[#E2E8F0] shadow-sm">
        <!-- Subtle mesh gradient background -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60"></div>
        
        <div class="relative z-10 flex flex-col justify-between gap-4">
            <div>
                <p class="text-xs font-bold text-blue-600 mb-2 uppercase tracking-widest">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <h1 class="text-3xl md:text-4xl font-black text-[#0F172A] tracking-tight">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                <p class="mt-3 text-[#64748B] max-w-xl text-sm md:text-base font-medium leading-relaxed">Selamat datang di portal kandidat HIMATIK PNJ. Lihat jadwal wawancara kamu dan info rekrutmen yang tersedia.</p>
            </div>
        </div>
    </div>

    @if($announcement && $announcement->is_published)
        <div class="mt-6">
            @if($announcement->status === 'accepted')
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center justify-between rounded-[2rem] border border-emerald-200 bg-emerald-50 p-8 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -mt-16 -mr-16"></div>
                    <div class="relative z-10">
                        <h2 class="text-2xl font-black text-emerald-800">Selamat! Anda Dinyatakan Lulus 🎉</h2>
                        <p class="mt-2 text-base font-medium text-emerald-700 max-w-2xl">
                            Anda diterima sebagai <strong class="font-bold bg-emerald-200 px-2 py-0.5 rounded">{{ ucfirst($candidate->candidate_type) }}</strong> di <strong class="font-bold bg-emerald-200 px-2 py-0.5 rounded">{{ $announcement->assignedDepartment?->name ?? 'Staff Umum' }}</strong>. Silakan tunggu informasi selanjutnya dari pengurus.
                        </p>
                    </div>
                </div>
            @elseif($announcement->status === 'rejected')
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center justify-between rounded-[2rem] border border-red-200 bg-red-50 p-8 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -mt-16 -mr-16"></div>
                    <div class="relative z-10">
                        <h2 class="text-2xl font-black text-red-800">Mohon Maaf, Anda Belum Lulus</h2>
                        <p class="mt-2 text-base font-medium text-red-700 max-w-2xl">
                            Tetap semangat dan jangan menyerah! Terima kasih telah mengikuti seluruh rangkaian proses seleksi HIMATIK PNJ.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    @endif

    @php
        $isFullyRegistered = $candidate->exists && $candidate->candidate_signature_path && $candidate->parent_signature_path;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Main Content Left (Jadwal Wawancara) -->
        <section class="space-y-4">
            <h2 class="text-lg font-black text-[#0F172A] px-2">Jadwal Wawancara</h2>
            @php
                $selectedSlot = $candidate->selectedInterviewSchedule?->schedule;
            @endphp
            
            <div class="rounded-[2rem] bg-white border border-[#E2E8F0] shadow-sm h-full overflow-hidden flex flex-col">
                @if ($selectedSlot)
                    <div class="flex-1 p-6 flex flex-col justify-center relative overflow-hidden group">
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-[#0F172A] tracking-tight mb-5 leading-tight">Wawancara {{ ucfirst($candidate->candidate_type) }} HIMATIK PNJ</h3>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-3 text-sm font-medium text-[#475569]">
                                    <svg class="h-5 w-5 text-[#64748B] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p>{{ \Carbon\Carbon::parse($selectedSlot->date)->locale('id')->translatedFormat('l, d F Y') }}</p>
                                </div>
                                <div class="flex items-center gap-3 text-sm font-medium text-[#475569]">
                                    <svg class="h-5 w-5 text-[#64748B] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <p>{{ \Carbon\Carbon::parse($selectedSlot->start_time)->format('H:i') }} s.d. {{ \Carbon\Carbon::parse($selectedSlot->end_time)->format('H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-3 text-sm font-medium text-[#475569]">
                                    <svg class="h-5 w-5 text-[#64748B] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    <p class="break-all">{{ $candidate->openRecruitment?->interview_location ?? 'Lokasi belum ditentukan' }}</p>
                                </div>
                            </div>

                            <a href="{{ route('candidate.interview.detail') }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#223872] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#1b2f60] shadow-sm">
                                Selengkapnya
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="flex-1 p-12 flex flex-col items-center justify-center text-center">
                        <div class="h-20 w-20 bg-[#F8FAFC] rounded-full border border-[#E2E8F0] flex items-center justify-center mb-6 shadow-sm">
                            <svg class="h-8 w-8 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <h4 class="text-lg font-black text-[#0F172A]">Belum Ada Jadwal</h4>
                        @if($isFullyRegistered)
                            <p class="text-sm font-medium text-[#64748B] mt-2 mb-6 max-w-sm">Anda belum memilih jadwal wawancara. Silakan klik tombol di bawah untuk memilih sesi.</p>
                            <a href="{{ route('candidate.schedule.view') }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-sm font-bold text-white transition hover:bg-blue-700 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                Pilih Jadwal Wawancara
                            </a>
                        @else
                            <p class="text-sm font-medium text-[#64748B] mt-2 max-w-sm">Anda belum memilih jadwal wawancara. Silakan daftar dan selesaikan profil terlebih dahulu.</p>
                        @endif
                    </div>
                @endif
            </div>
        </section>

        <!-- Right Content (Daftar Rekrutmen) -->
        <section class="space-y-4">
            <h2 class="text-lg font-black text-[#0F172A] px-2">Daftar Rekrutmen</h2>
            <div class="space-y-4">
                @forelse($openRecruitments as $oprec)
                    <div class="rounded-[2rem] bg-white border border-[#E2E8F0] p-6 sm:p-8 shadow-sm transition hover:shadow-md hover:border-blue-300">
                        <div class="flex items-start justify-between gap-4 mb-5">
                            <div>
                                <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 uppercase tracking-wider mb-3">
                                    <span class="mr-1.5 flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                    Sedang Dibuka
                                </span>
                                <h3 class="text-xl font-black leading-tight text-[#0F172A]">
                                    Open Recruitment {{ ucfirst($oprec->candidate_type) }} HIMATIK PNJ
                                </h3>
                            </div>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-start gap-3 text-sm font-medium text-[#64748B]">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p>{{ $oprec->starts_at->locale('id')->translatedFormat('d F Y H:i') }} s.d.</p>
                                    <p>{{ $oprec->ends_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                                </div>
                            </div>
                            
                            @if($oprec->interview_location)
                                <div class="flex items-center gap-3 text-sm font-medium text-[#64748B]">
                                    <svg class="h-5 w-5 shrink-0 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p>{{ $oprec->interview_location }}</p>
                                </div>
                            @endif
                        </div>

                        @if($candidate->exists && $candidate->candidate_type === $oprec->candidate_type)
                            @if($isFullyRegistered)
                                <div class="flex gap-3">
                                    <div class="inline-flex h-12 flex-1 items-center justify-center gap-2 rounded-xl border-2 border-emerald-500 bg-emerald-50 px-4 text-sm font-bold text-emerald-700 shadow-sm">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Terdaftar
                                    </div>
                                    <button type="button" data-open-candidate-modal
                                            class="inline-flex h-12 items-center justify-center rounded-xl bg-[#0F172A] px-6 text-sm font-bold text-white transition hover:bg-blue-600 shadow-sm">
                                        Detail
                                    </button>
                                </div>
                            @else
                                <a href="{{ route('candidate.apply.start', $oprec->id) }}" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-orange-500 px-6 text-sm font-bold text-white transition hover:bg-orange-600 shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                                    Lanjutkan Pendaftaran
                                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('candidate.apply.start', $oprec->id) }}" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#0F172A] px-6 text-sm font-bold text-white transition hover:bg-blue-600 shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                                Daftar Sekarang
                                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="rounded-[2rem] bg-white border border-[#E2E8F0] p-12 text-center shadow-sm">
                        <div class="mx-auto h-16 w-16 bg-[#F8FAFC] rounded-2xl border border-[#E2E8F0] flex items-center justify-center mb-5">
                            <svg class="h-8 w-8 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h4 class="text-lg font-black text-[#0F172A]">Tidak Ada Rekrutmen</h4>
                        <p class="text-sm font-medium text-[#64748B] mt-2">Saat ini tidak ada open recruitment yang sedang berlangsung. Pantau terus informasi selanjutnya!</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>

@if($isFullyRegistered)
    @php
        $firstChoice      = $candidate->first_choice_department;
        $secondChoice     = $candidate->second_choice_department;
        $schedule         = $candidate->selectedInterviewSchedule?->schedule;
        $missingDocuments = collect($documentFields)->filter(fn ($label, $field) => blank($candidate->{$field}));
    @endphp

    <div id="candidate-detail-modal"
         class="fixed inset-0 z-50 hidden overflow-y-auto bg-[#06122d]/55 p-4 backdrop-blur-sm">
        <div class="mx-auto my-6 max-w-5xl rounded-3xl bg-white shadow-2xl shadow-[#06122d]/25">

            {{-- Modal header --}}
            <div class="sticky top-0 z-10 flex items-center justify-between gap-4 rounded-t-3xl border-b border-[#dce5f8] bg-white px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#EEF3FF] text-sm font-black text-[#223872]">
                        {{ strtoupper(substr($candidate->user?->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#4A90E2]">Detail Kandidat</p>
                        <h3 class="text-lg font-black text-[#111827]">{{ $candidate->user?->name ?? 'Kandidat' }}</h3>
                        <p class="text-xs text-[#64748b]">{{ $candidate->user?->email ?? '-' }}</p>
                    </div>
                </div>
                <button type="button" data-close-candidate-modal
                        class="rounded-xl bg-[#F4F7FF] px-3 py-2 text-sm font-black text-[#223872] transition hover:bg-[#dce5f8]">
                    Tutup
                </button>
            </div>

            {{-- Modal body --}}
            <div class="grid gap-4 p-5 lg:grid-cols-12">

                {{-- Identitas --}}
                <section class="rounded-2xl border border-[#dce5f8] bg-[#F4F7FF] p-4 lg:col-span-5">
                    <h4 class="text-sm font-black text-[#111827]">Informasi Identitas</h4>
                    <dl class="mt-3 grid gap-1.5 text-xs">
                        @foreach ([
                            'Nama Lengkap'    => $candidate->user->name,
                            'Nama Panggilan'  => $candidate->user->nickname,
                            'NIM'             => $candidate->user->nim,
                            'Program Studi'   => $candidate->user->prodi,
                            'Kelas'           => $candidate->user->kelas,
                            'Nomor Telepon'   => $candidate->user->phone,
                            'Candidate Type'  => strtoupper($candidate->candidate_type),
                            'Status'          => $statusLabel[$candidate->status] ?? ucfirst($candidate->status),
                        ] as $label => $value)
                            <div class="flex justify-between gap-3 rounded-xl bg-white px-3 py-2">
                                <dt class="font-bold text-[#64748b]">{{ $label }}</dt>
                                <dd class="text-right font-black text-[#223872]">{{ $value ?: '-' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                    <div class="mt-1.5 rounded-xl bg-white px-3 py-2 text-xs">
                        <p class="font-bold text-[#64748b]">Alamat Lengkap</p>
                        <p class="mt-1 leading-5 text-[#333333]">{{ $candidate->user->address ?: '-' }}</p>
                    </div>
                </section>

                {{-- Pilihan & Interview --}}
                <section class="rounded-2xl border border-[#dce5f8] bg-white p-4 lg:col-span-7">
                    <h4 class="text-sm font-black text-[#111827]">Pilihan &amp; Interview</h4>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-xl bg-[#F4F7FF] px-3 py-3">
                            <p class="text-[0.65rem] font-black uppercase tracking-[0.12em] text-[#4A90E2]">Pilihan 1</p>
                            <p class="mt-1 text-sm font-black text-[#223872]">{{ $firstChoice?->name ?? '-' }}</p>
                        </div>
                        <div class="rounded-xl bg-[#F4F7FF] px-3 py-3">
                            <p class="text-[0.65rem] font-black uppercase tracking-[0.12em] text-[#4A90E2]">Pilihan 2</p>
                            <p class="mt-1 text-sm font-black text-[#223872]">{{ $secondChoice?->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="mt-3 rounded-xl bg-[#F4F7FF] px-3 py-3 text-xs">
                        <p class="font-bold text-[#64748b]">Jadwal Interview</p>
                        @if ($schedule)
                            <p class="mt-1 font-black text-[#223872]">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                            <p class="mt-0.5 text-[#64748b]">{{ $schedule->department?->name ?? '-' }} · {{ \Carbon\Carbon::parse($schedule->date)->locale('id')->translatedFormat('d F Y') }}</p>
                        @else
                            <p class="mt-1 text-[#64748b]">Belum memilih jadwal interview.</p>
                        @endif
                    </div>
                    <div class="mt-3 grid gap-2 sm:grid-cols-3">
                        <div class="rounded-xl bg-[#F4F7FF] px-3 py-3 text-xs sm:col-span-3">
                            <p class="font-bold text-[#64748b]">Alasan Memilih Departemen/Biro</p>
                            <p class="mt-1 leading-5 text-[#333333]">{{ $candidate->department_choice_reason ?: '-' }}</p>
                        </div>
                        <div class="rounded-xl bg-[#F4F7FF] px-3 py-3 text-xs">
                            <p class="font-bold text-[#64748b]">Kekurangan</p>
                            <p class="mt-1 leading-5 text-[#333333]">{{ $candidate->weakness_description ?: '-' }}</p>
                        </div>
                        <div class="rounded-xl bg-[#F4F7FF] px-3 py-3 text-xs sm:col-span-2">
                            <p class="font-bold text-[#64748b]">Langkah Konkret Jika Terpilih</p>
                            <p class="mt-1 leading-5 text-[#333333]">{{ $candidate->contribution_plan ?: '-' }}</p>
                        </div>
                    </div>
                </section>

                {{-- Berkas Administratif --}}
                <section class="rounded-2xl border border-[#dce5f8] bg-white p-4 lg:col-span-12">
                    <div class="flex items-center justify-between gap-3">
                        <h4 class="text-sm font-black text-[#111827]">Berkas Administratif</h4>
                        <span class="rounded-full px-2.5 py-0.5 text-[0.65rem] font-black {{ $missingDocuments->isEmpty() ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $missingDocuments->isEmpty() ? 'Semua berkas lengkap' : $missingDocuments->count() . ' berkas kurang' }}
                        </span>
                    </div>
                    <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($documentFields as $field => $label)
                            <div class="flex items-center justify-between gap-3 rounded-xl bg-[#F4F7FF] px-3 py-2 text-xs">
                                <span class="font-bold text-[#333333]">{{ $label }}</span>
                                @if (filled($candidate->{$field}))
                                    <a href="{{ route('documents.download', [$candidate->id, $field]) }}" target="_blank"
                                       class="rounded-full bg-emerald-50 px-2 py-0.5 font-black text-emerald-700 hover:underline">Ada</a>
                                @else
                                    <span class="rounded-full bg-amber-50 px-2 py-0.5 font-black text-amber-700">Kosong</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Riwayat Pendidikan --}}
                <section class="rounded-2xl border border-[#dce5f8] bg-white p-4 lg:col-span-6">
                    <h4 class="text-sm font-black text-[#111827]">Riwayat Pendidikan</h4>
                    <div class="mt-3 space-y-2">
                        @forelse ($candidate->educations as $education)
                            <div class="rounded-xl bg-[#F4F7FF] px-3 py-2 text-xs">
                                <p class="font-black text-[#223872]">{{ $education->school_name }}</p>
                                <p class="mt-0.5 text-[#64748b]">{{ ucfirst($education->education_type) }} · {{ $education->start_year }}{{ $education->end_year ? ' – ' . $education->end_year : '' }} · {{ $education->city ?: '-' }} · {{ $education->major ?: '-' }}</p>
                            </div>
                        @empty
                            <p class="rounded-xl bg-[#F4F7FF] px-3 py-3 text-xs text-[#64748b]">Belum ada riwayat pendidikan.</p>
                        @endforelse
                    </div>
                </section>

                {{-- Pengalaman Organisasi --}}
                <section class="rounded-2xl border border-[#dce5f8] bg-white p-4 lg:col-span-6">
                    <h4 class="text-sm font-black text-[#111827]">Pengalaman Organisasi</h4>
                    <div class="mt-3 space-y-2">
                        @forelse ($candidate->organizations as $organization)
                            <div class="rounded-xl bg-[#F4F7FF] px-3 py-2 text-xs">
                                <p class="font-black text-[#223872]">{{ $organization->organization_name }}</p>
                                <p class="mt-0.5 text-[#64748b]">{{ $organization->position ?: '-' }} · {{ $organization->start_year }}{{ $organization->end_year ? ' – ' . $organization->end_year : '' }} · {{ $organization->place_or_institution ?: '-' }}</p>
                            </div>
                        @empty
                            <p class="rounded-xl bg-[#F4F7FF] px-3 py-3 text-xs text-[#64748b]">Belum ada pengalaman organisasi.</p>
                        @endforelse
                    </div>
                </section>

                {{-- Pengalaman Kepanitiaan --}}
                <section class="rounded-2xl border border-[#dce5f8] bg-white p-4 lg:col-span-6">
                    <h4 class="text-sm font-black text-[#111827]">Pengalaman Kepanitiaan</h4>
                    <div class="mt-3 space-y-2">
                        @forelse ($candidate->committees as $committee)
                            <div class="rounded-xl bg-[#F4F7FF] px-3 py-2 text-xs">
                                <p class="font-black text-[#223872]">{{ $committee->committee_name }}</p>
                                <p class="mt-0.5 text-[#64748b]">{{ $committee->position ?: '-' }} · {{ $committee->start_year }}{{ $committee->end_year ? ' – ' . $committee->end_year : '' }} · {{ $committee->organizer ?: '-' }}</p>
                            </div>
                        @empty
                            <p class="rounded-xl bg-[#F4F7FF] px-3 py-3 text-xs text-[#64748b]">Belum ada pengalaman kepanitiaan.</p>
                        @endforelse
                    </div>
                </section>

                {{-- Kemampuan & Fasilitas --}}
                <section class="rounded-2xl border border-[#dce5f8] bg-white p-4 lg:col-span-6">
                    <h4 class="text-sm font-black text-[#111827]">Kemampuan &amp; Fasilitas</h4>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <div class="space-y-2">
                            @forelse ($candidate->skills as $skill)
                                <div class="rounded-xl bg-[#F4F7FF] px-3 py-2 text-xs">
                                    <p class="font-black text-[#223872]">{{ $skill->skill_name }}</p>
                                    <p class="mt-0.5 text-[#64748b]">{{ ucfirst($skill->skill_type) }} · {{ ucfirst($skill->proficiency) }}</p>
                                </div>
                            @empty
                                <p class="rounded-xl bg-[#F4F7FF] px-3 py-3 text-xs text-[#64748b]">Belum ada kemampuan.</p>
                            @endforelse
                        </div>
                        <div class="space-y-2">
                            @forelse ($candidate->facilities as $facility)
                                <div class="rounded-xl bg-[#F4F7FF] px-3 py-2 text-xs font-bold text-[#333333]">{{ $facility->facility_name }}</div>
                            @empty
                                <p class="rounded-xl bg-[#F4F7FF] px-3 py-3 text-xs text-[#64748b]">Belum ada fasilitas.</p>
                            @endforelse
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

    <script>
        document.querySelector('[data-open-candidate-modal]')?.addEventListener('click', () => {
            document.getElementById('candidate-detail-modal')?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });

        document.querySelector('[data-close-candidate-modal]')?.addEventListener('click', () => {
            document.getElementById('candidate-detail-modal')?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

        const modal = document.getElementById('candidate-detail-modal');
        modal?.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    </script>
@endif

@endsection
