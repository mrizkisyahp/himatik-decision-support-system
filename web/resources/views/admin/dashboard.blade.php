@extends('admin.layout', ['title' => 'Dashboard', 'hideStubBadge' => true])

@section('content')
    @php
        $totalStatus = max((int) $candidateSummary['total'], 0);
        $registeredPct = $totalStatus > 0 ? round(($candidateSummary['registered'] / $totalStatus) * 100) : 0;
        $scheduledPct  = $totalStatus > 0 ? round(($candidateSummary['scheduled']  / $totalStatus) * 100) : 0;
        $evaluatedPct  = $totalStatus > 0 ? round(($candidateSummary['evaluated']  / $totalStatus) * 100) : 0;
        $completedPct  = $totalStatus > 0 ? round(($candidateSummary['completed']  / $totalStatus) * 100) : 0;

        $unscheduledCandidates = max($candidateSummary['total'] - $interviewProgress['scheduled_candidates'], 0);
        $attentionItems = collect();

        if ($unscheduledCandidates > 0) {
            $attentionItems->push("{$unscheduledCandidates} kandidat belum dijadwalkan interview");
        }
        if ($interviewProgress['pending_interviews'] > 0) {
            $attentionItems->push("{$interviewProgress['pending_interviews']} interview masih pending");
        }
        if (!($openRecruitment['available'] ?? false)) {
            $attentionItems->push('Periode open recruitment belum diatur');
        }
        if ($announcementStatus['unpublished'] > 0) {
            $attentionItems->push('Pengumuman belum dipublikasikan');
        }

        $staffCount = $candidateSummary['staff'] ?? 0;
        $bphCount   = $candidateSummary['bph']   ?? 0;

        $sessionProgress = $interviewProgress['scheduled_candidates'] > 0
            ? round(($interviewProgress['completed_interviews'] / max($interviewProgress['scheduled_candidates'], 1)) * 100)
            : 0;
    @endphp

    <div class="mx-auto max-w-7xl">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
            
            <div class="space-y-4 lg:col-span-3">

        {{-- ── HEADER ── --}}
        <div class="flex items-center justify-end">
            <p class="text-xs text-[#94a3b8]">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
        </div>

        {{-- ════════════════════════════════════════════════════════════════
             ROW 1 — KPI STRIP
        ════════════════════════════════════════════════════════════════ --}}
        <section class="grid grid-cols-2 gap-3 sm:grid-cols-4" aria-label="KPI Utama">
            @foreach ([
                ['label' => 'Total Kandidat',    'value' => $stats['total_candidates'],      'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0'],
                ['label' => 'Total Account',     'value' => $stats['total_users'],            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['label' => 'Departemen / Biro', 'value' => $stats['total_departments'],      'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['label' => 'Default Criteria',  'value' => $stats['total_default_criteria'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ] as $stat)
                <article class="flex items-center gap-3 rounded-2xl border border-[#dce5f8] bg-white px-4 py-3 shadow-[0_4px_16px_rgba(34,56,114,0.06)]">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#EEF3FF]">
                        <svg class="h-5 w-5 text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-[0.6rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-black leading-tight text-[#223872]">{{ number_format($stat['value']) }}</p>
                    </div>
                </article>
            @endforeach
        </section>

        {{-- ════════════════════════════════════════════════════════════════
             ROW 2 — ATTENTION ALERTS
        ════════════════════════════════════════════════════════════════ --}}
        @if ($attentionItems->isNotEmpty())
            <section aria-label="Perlu Tindakan">
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                    <div class="mb-3 flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-400 text-[0.7rem] font-black text-white">!</span>
                        <h3 class="text-sm font-black text-amber-800">Perlu Tindakan</h3>
                        <span class="ml-auto rounded-full bg-amber-200 px-2 py-0.5 text-[0.65rem] font-black text-amber-700">{{ $attentionItems->count() }} item</span>
                    </div>
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($attentionItems as $item)
                            <div class="flex items-start gap-2 rounded-xl bg-white px-3 py-2 text-xs font-semibold text-amber-800 shadow-sm">
                                <span class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-amber-400"></span>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
             ROW 3 — RECRUITMENT PIPELINE
        ════════════════════════════════════════════════════════════════ --}}
        <article class="rounded-2xl border border-[#dce5f8] bg-white p-4 shadow-[0_4px_16px_rgba(34,56,114,0.06)]" aria-label="Pipeline Rekrutmen">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-sm font-black text-[#111827]">Pipeline Rekrutmen</h3>
                <span class="rounded-full bg-[#EEF3FF] px-3 py-1 text-[0.65rem] font-black text-[#4A90E2]">
                    {{ ($openRecruitment['available'] ?? false) ? '● Aktif' : '○ Belum Diatur' }}
                </span>
            </div>
            <div class="grid grid-cols-4 gap-2">
                @foreach ([
                    ['label' => 'Terdaftar', 'value' => $candidateSummary['registered'], 'pct' => $registeredPct, 'color' => '#223872'],
                    ['label' => 'Terjadwal', 'value' => $candidateSummary['scheduled'],  'pct' => $scheduledPct,  'color' => '#4A90E2'],
                    ['label' => 'Dinilai',   'value' => $candidateSummary['evaluated'],  'pct' => $evaluatedPct,  'color' => '#7fb6ec'],
                    ['label' => 'Selesai',   'value' => $candidateSummary['completed'],  'pct' => $completedPct,  'color' => '#bdd9f7'],
                ] as $step)
                    <div class="flex flex-col items-center rounded-xl bg-[#F4F7FF] px-2 py-3 text-center">
                        <span class="text-[0.6rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">{{ $step['label'] }}</span>
                        <span class="mt-1 text-2xl font-black leading-none text-[#223872]">{{ number_format($step['value']) }}</span>
                        <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-[#dce5f8]">
                            <div class="h-full rounded-full transition-all duration-500"
                                 style="width: {{ $step['pct'] }}%; background: {{ $step['color'] }};"></div>
                        </div>
                        <span class="mt-1 text-[0.6rem] font-bold text-[#94a3b8]">{{ $step['pct'] }}%</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 flex items-center gap-3 border-t border-[#edf2ff] pt-3">
                <span class="rounded-full bg-[#EEF3FF] px-2.5 py-1 text-[0.65rem] font-black text-[#223872]">Staff: {{ number_format($staffCount) }}</span>
                <span class="rounded-full bg-[#EEF3FF] px-2.5 py-1 text-[0.65rem] font-black text-[#223872]">BPH: {{ number_format($bphCount) }}</span>
            </div>
        </article>

        {{-- ════════════════════════════════════════════════════════════════
             ROW 4 — INTERVIEW PROGRESS
        ════════════════════════════════════════════════════════════════ --}}
        <article class="rounded-2xl border border-[#dce5f8] bg-white p-4 shadow-[0_4px_16px_rgba(34,56,114,0.06)]" aria-label="Interview Progress">
            <h3 class="mb-3 text-sm font-black text-[#111827]">Interview Progress</h3>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                @foreach ([
                    ['label' => 'Kandidat Terjadwal', 'value' => $interviewProgress['scheduled_candidates'],  'accent' => false],
                    ['label' => 'Interview Selesai',  'value' => $interviewProgress['completed_interviews'],  'accent' => false],
                    ['label' => 'Masih Pending',      'value' => $interviewProgress['pending_interviews'],    'accent' => true],
                ] as $item)
                    <div class="rounded-xl {{ $item['accent'] && $item['value'] > 0 ? 'border border-amber-100 bg-amber-50' : 'bg-[#F4F7FF]' }} px-3 py-3">
                        <p class="text-[0.6rem] font-black uppercase tracking-[0.14em] {{ $item['accent'] && $item['value'] > 0 ? 'text-amber-600' : 'text-[#94a3b8]' }}">{{ $item['label'] }}</p>
                        <p class="mt-0.5 text-xl font-black {{ $item['accent'] && $item['value'] > 0 ? 'text-amber-700' : 'text-[#223872]' }}">{{ number_format($item['value']) }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 border-t border-[#edf2ff] pt-3">
                <div class="flex items-center justify-between text-[0.65rem]">
                    <span class="font-bold text-[#94a3b8]">Progress keseluruhan</span>
                    <span class="font-black text-[#223872]">{{ $sessionProgress }}%</span>
                </div>
                <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-[#dce5f8]">
                    <div class="h-full rounded-full bg-gradient-to-r from-[#223872] to-[#4A90E2] transition-all duration-500"
                         style="width: {{ $sessionProgress }}%;"></div>
                </div>
            </div>
        </article>

        {{-- ════════════════════════════════════════════════════════════════
             ROW 5 — DEPARTMENT INTEREST
        ════════════════════════════════════════════════════════════════ --}}
        <section class="grid gap-4 xl:grid-cols-2" aria-label="Minat Departemen">

            <article class="rounded-2xl border border-[#dce5f8] bg-white p-4 shadow-[0_4px_16px_rgba(34,56,114,0.06)]">
                <h3 class="mb-3 text-sm font-black text-[#111827]">Minat Pilihan 1</h3>
                @php $maxFirst = $firstChoiceInterest->max('total') ?: 1; @endphp
                <div class="space-y-2.5">
                    @forelse ($firstChoiceInterest->take(6) as $i => $choice)
                        @php $barPct = round(($choice->total / $maxFirst) * 100); @endphp
                        <div>
                            <div class="mb-1 flex items-center justify-between gap-2">
                                <span class="flex items-center gap-2 truncate text-xs font-bold text-[#333333]">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#EEF3FF] text-[0.6rem] font-black text-[#4A90E2]">{{ $i + 1 }}</span>
                                    {{ $choice->department?->name ?? 'Departemen tidak tersedia' }}
                                </span>
                                <span class="shrink-0 text-xs font-black text-[#223872]">{{ $choice->total }}</span>
                            </div>
                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-[#dce5f8]">
                                <div class="h-full rounded-full bg-[#223872] transition-all duration-500" style="width: {{ $barPct }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-[#dce5f8] bg-[#F4F7FF] px-3 py-4 text-center text-xs text-[#94a3b8]">
                            Belum ada data pilihan kandidat.
                        </p>
                    @endforelse
                </div>
            </article>

            <article class="rounded-2xl border border-[#dce5f8] bg-white p-4 shadow-[0_4px_16px_rgba(34,56,114,0.06)]">
                <h3 class="mb-3 text-sm font-black text-[#111827]">Minat Pilihan 2</h3>
                @php $maxSecond = $secondChoiceInterest->max('total') ?: 1; @endphp
                <div class="space-y-2.5">
                    @forelse ($secondChoiceInterest->take(6) as $i => $choice)
                        @php $barPct = round(($choice->total / $maxSecond) * 100); @endphp
                        <div>
                            <div class="mb-1 flex items-center justify-between gap-2">
                                <span class="flex items-center gap-2 truncate text-xs font-bold text-[#333333]">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#EEF3FF] text-[0.6rem] font-black text-[#4A90E2]">{{ $i + 1 }}</span>
                                    {{ $choice->department?->name ?? 'Departemen tidak tersedia' }}
                                </span>
                                <span class="shrink-0 text-xs font-black text-[#223872]">{{ $choice->total }}</span>
                            </div>
                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-[#dce5f8]">
                                <div class="h-full rounded-full bg-[#4A90E2] transition-all duration-500" style="width: {{ $barPct }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-[#dce5f8] bg-[#F4F7FF] px-3 py-4 text-center text-xs text-[#94a3b8]">
                            Belum ada data pilihan kandidat.
                        </p>
                    @endforelse
                </div>
            </article>
        </section>

        {{-- ════════════════════════════════════════════════════════════════
             ROW 6 — RECENT CANDIDATES TABLE
        ════════════════════════════════════════════════════════════════ --}}
        <section class="rounded-2xl border border-[#dce5f8] bg-white shadow-[0_4px_16px_rgba(34,56,114,0.06)]" aria-label="Pendaftaran Terbaru">
            <div class="flex items-center justify-between gap-4 border-b border-[#edf2ff] px-5 py-3.5">
                <h3 class="text-sm font-black text-[#111827]">Pendaftaran Terbaru</h3>
                <div class="flex items-center gap-3">
                    <span class="rounded-full bg-[#EEF3FF] px-3 py-1 text-xs font-black text-[#223872]">5 kandidat</span>
                    @if(Route::has('admin.registrations'))
                        <a href="{{ route('admin.registrations') }}"
                           class="shrink-0 rounded-xl bg-[#223872] px-3 py-1.5 text-xs font-black text-white transition hover:bg-[#1b2f60]">
                            Lihat Semua
                        </a>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                @if ($recentCandidates->isEmpty())
                    <p class="px-5 py-8 text-center text-sm text-[#94a3b8]">Belum ada kandidat terdaftar.</p>
                @else
                    <table class="min-w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-[#edf2ff] text-[0.6rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">
                                <th class="px-5 py-3">Nama</th>
                                <th class="hidden px-4 py-3 sm:table-cell">NIM</th>
                                <th class="hidden px-4 py-3 md:table-cell">Pilihan 1</th>
                                <th class="hidden px-4 py-3 lg:table-cell">Pilihan 2</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="hidden px-4 py-3 sm:table-cell">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentCandidates as $candidate)
                                <tr class="border-b border-[#edf2ff] transition last:border-0 hover:bg-[#F4F7FF]">
                                    <td class="px-5 py-3">
                                        <p class="font-bold text-[#111827]">{{ $candidate->user?->name ?? '-' }}</p>
                                        <p class="text-[0.65rem] text-[#64748b]">{{ $candidate->user?->email ?? '' }}</p>
                                    </td>
                                    <td class="hidden px-4 py-3 font-mono text-[0.68rem] text-[#64748b] sm:table-cell">{{ $candidate->user->nim }}</td>
                                    <td class="hidden px-4 py-3 text-[#64748b] md:table-cell">{{ $candidate->first_choice_department?->name ?? '-' }}</td>
                                    <td class="hidden px-4 py-3 text-[#64748b] lg:table-cell">{{ $candidate->second_choice_department?->name ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusColor = match($candidate->status) {
                                                'registered' => 'bg-[#EEF3FF] text-[#223872]',
                                                'scheduled'  => 'bg-blue-100 text-blue-700',
                                                'evaluated'  => 'bg-purple-100 text-purple-700',
                                                'completed'  => 'bg-emerald-100 text-emerald-700',
                                                default      => 'bg-[#F4F7FF] text-[#64748b]',
                                            };
                                        @endphp
                                        <span class="rounded-full px-2.5 py-0.5 text-[0.65rem] font-black {{ $statusColor }}">
                                            {{ ucfirst($candidate->status) }}
                                        </span>
                                    </td>
                                    <td class="hidden px-4 py-3 text-[#94a3b8] sm:table-cell">
                                        {{ $candidate->created_at?->locale('id')?->translatedFormat('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </section>

        </div>

            <div class="lg:col-span-1">
                <div class="rounded-[2rem] bg-white border border-[#E2E8F0] shadow-sm h-full flex flex-col">
                    <div class="px-7 py-6 border-b border-[#F1F5F9]">
                        <div class="flex items-center justify-between">
                            <h3 class="font-black text-[#0F172A] text-lg">Timeline Hari Ini</h3>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[0.65rem] font-bold bg-[#F1F5F9] text-[#475569] uppercase tracking-wider">
                                {{ $totalTodaySchedules }} Sesi
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex-1 p-7">
                        @if($todaySchedules->count() > 0)
                            <div class="relative border-l-2 border-[#F1F5F9] ml-3 space-y-8 py-2">
                                @foreach($todaySchedules as $index => $sch)
                                    <div class="relative pl-6">
                                        <!-- Timeline dot -->
                                        <div class="absolute left-[-5px] top-1.5 h-2.5 w-2.5 rounded-full bg-blue-500 ring-4 ring-white"></div>
                                        
                                        <div class="group">
                                            <p class="text-[0.65rem] font-bold text-blue-600 mb-1.5 uppercase tracking-widest">{{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}</p>
                                            <div class="bg-white border border-[#E2E8F0] rounded-2xl p-4 shadow-sm hover:shadow-md transition-all group-hover:border-blue-300 hover:-translate-y-0.5">
                                                <h4 class="font-bold text-[#0F172A] mb-1.5 line-clamp-1" title="{{ $sch->booking->candidate->user->name ?? 'Kandidat' }}">{{ $sch->booking->candidate->user->name ?? 'Kandidat' }}</h4>
                                                <div class="flex items-center gap-2 text-xs font-medium text-[#64748B] mb-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                                    {{ $sch->department->name ?? '-' }}
                                                </div>
                                                <div class="mt-4 pt-4 border-t border-[#F8FAFC] flex items-center justify-between">
                                                    <span class="text-[0.65rem] font-bold text-[#94A3B8] uppercase">Sesi {{ ($todaySchedules->firstItem() ?? 1) + $index }}</span>
                                                    <a href="{{ route('admin.schedules') }}" class="inline-flex items-center gap-1 text-[0.65rem] font-bold text-[#475569] bg-[#F1F5F9] px-3 py-1.5 rounded-lg hover:bg-[#E2E8F0] transition-colors uppercase tracking-wider">
                                                        Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($todaySchedules->hasPages())
                                <div class="mt-6 flex flex-col gap-3 border-t border-[#F1F5F9] pt-4">
                                    <p class="text-[0.7rem] font-medium text-[#64748B]">
                                        Menampilkan {{ $todaySchedules->firstItem() }}-{{ $todaySchedules->lastItem() }} dari {{ $todaySchedules->total() }} sesi
                                    </p>
                                    <div class="flex items-center justify-center gap-1">
                                        @if($todaySchedules->onFirstPage())
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-[#E2E8F0] bg-[#F8FAFC] text-[#CBD5E1]">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </span>
                                        @else
                                            <a href="{{ $todaySchedules->previousPageUrl() }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-[#D8E2F3] bg-white text-[#475569] transition hover:border-[#4A90E2] hover:text-[#223872]">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </a>
                                        @endif

                                        @foreach($todaySchedules->getUrlRange(1, $todaySchedules->lastPage()) as $page => $url)
                                            @if($page === $todaySchedules->currentPage())
                                                <span class="inline-flex h-8 min-w-[2rem] items-center justify-center rounded-lg bg-[#223872] px-2 text-xs font-black text-white">
                                                    {{ $page }}
                                                </span>
                                            @else
                                                <a href="{{ $url }}" class="inline-flex h-8 min-w-[2rem] items-center justify-center rounded-lg border border-[#D8E2F3] bg-white px-2 text-xs font-black text-[#475569] transition hover:border-[#4A90E2] hover:text-[#223872]">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endforeach

                                        @if($todaySchedules->hasMorePages())
                                            <a href="{{ $todaySchedules->nextPageUrl() }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-[#D8E2F3] bg-white text-[#475569] transition hover:border-[#4A90E2] hover:text-[#223872]">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        @else
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-[#E2E8F0] bg-[#F8FAFC] text-[#CBD5E1]">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="py-12 flex flex-col items-center text-center">
                                <div class="bg-[#F8FAFC] border border-[#E2E8F0] p-4 rounded-2xl mb-4 shadow-sm">
                                    <svg class="h-6 w-6 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <p class="text-sm font-black text-[#0F172A]">Jadwal Kosong</p>
                                <p class="text-xs font-medium text-[#64748B] mt-1.5 max-w-[200px]">Belum ada kandidat yang dijadwalkan wawancara hari ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
