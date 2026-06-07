@extends('candidate.layout', ['title' => 'Detail Wawancara'])

@section('content')
<div class="mx-auto max-w-3xl space-y-8">
    <div class="mb-6">
        <a href="{{ route('candidate.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#223872] hover:text-[#1b2f60] transition group">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <div>
        <h1 class="text-2xl md:text-3xl font-black tracking-tight text-[#0F172A] mb-8">Wawancara {{ ucfirst($candidate->candidate_type) }} HIMATIK PNJ</h1>

        <!-- Detail Pelaksanaan -->
        <section class="mb-8">
            <h2 class="text-lg font-black text-[#0F172A] mb-4">Detail Pelaksanaan</h2>
            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <svg class="h-5 w-5 mt-0.5 text-[#64748B] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <p class="text-sm font-medium text-[#333333]">{{ \Carbon\Carbon::parse($schedule->date)->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="flex items-start gap-4">
                    <svg class="h-5 w-5 mt-0.5 text-[#64748B] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-sm font-medium text-[#333333]">Sesi Wawancara ({{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} s.d. {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }})</p>
                </div>
                <div class="flex items-start gap-4">
                    <svg class="h-5 w-5 mt-0.5 text-[#64748B] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <p class="text-sm font-medium text-[#333333] break-all">{{ $candidate->openRecruitment?->interview_location ?? 'Lokasi belum ditentukan' }}</p>
                </div>
            </div>
        </section>

        <!-- Persyaratan -->
        <section class="mb-8">
            <h2 class="text-lg font-black text-[#0F172A] mb-4">Persyaratan</h2>
            <div class="text-sm font-medium text-[#333333] prose prose-sm max-w-none">
                {!! nl2br(e($candidate->openRecruitment?->interview_requirements ?? 'Tidak ada persyaratan khusus.')) !!}
            </div>
        </section>

        <!-- Kontak -->
        <section class="mb-12">
            <h2 class="text-lg font-black text-[#0F172A] mb-4">Kontak</h2>
            <div class="text-sm font-medium text-[#333333] prose prose-sm max-w-none">
                {!! nl2br(e($candidate->first_choice_department?->contact_person ?? 'Silakan hubungi panitia terkait.')) !!}
            </div>
        </section>

        <!-- Button -->
        <div class="pb-8">
            <a href="{{ route('candidate.registration.form') }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#223872] px-4 py-4 text-sm font-bold text-white transition hover:bg-[#1b2f60] shadow-md hover:shadow-lg hover:-translate-y-0.5">
                Lihat Formulir Pendaftaran
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </a>
        </div>
    </div>
</div>
@endsection
