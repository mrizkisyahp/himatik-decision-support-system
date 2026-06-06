@extends('admin.layout', ['title' => 'Pendaftaran', 'hideStubBadge' => true])

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

        $statusColor = [
            'registered' => 'bg-[#EEF3FF] text-[#223872]',
            'scheduled'  => 'bg-blue-100 text-blue-700',
            'evaluated'  => 'bg-purple-100 text-purple-700',
            'completed'  => 'bg-emerald-100 text-emerald-700',
            'accepted'   => 'bg-emerald-100 text-emerald-700',
            'rejected'   => 'bg-red-100 text-red-700',
        ];
    @endphp

    <div class="mx-auto max-w-7xl space-y-4">

        {{-- ════════════════════════════════════════════════════════════════
             ROW 1 — KPI STAT BAR (single card with dividers)
        ════════════════════════════════════════════════════════════════ --}}
        <section aria-label="Ringkasan Pendaftaran"
                 class="flex divide-x divide-[#edf2ff] overflow-hidden rounded-2xl border border-[#dce5f8] bg-white shadow-[0_4px_16px_rgba(34,56,114,0.06)]">
            @foreach ([
                ['label' => 'Total Pendaftar',  'value' => $registrationSummary['total'],                'accent' => true],
                ['label' => 'Staff',            'value' => $registrationSummary['staff'],                'accent' => false],
                ['label' => 'BPH',              'value' => $registrationSummary['bph'],                  'accent' => false],
                ['label' => 'Sudah Jadwal',     'value' => $registrationSummary['scheduled'],            'accent' => false],
                ['label' => 'Belum Jadwal',     'value' => $registrationSummary['unscheduled'],          'accent' => false],
                ['label' => 'Berkas Lengkap',   'value' => $registrationSummary['documents_complete'],   'accent' => false],
                ['label' => 'Berkas Belum',     'value' => $registrationSummary['documents_incomplete'], 'accent' => false],
            ] as $stat)
                <div class="flex min-w-0 flex-1 flex-col px-4 py-3 {{ $stat['accent'] ? 'bg-[#223872]' : '' }}">
                    <span class="truncate text-[0.6rem] font-black uppercase tracking-[0.12em] {{ $stat['accent'] ? 'text-[#7fb6ec]' : 'text-[#94a3b8]' }}">{{ $stat['label'] }}</span>
                    <span class="mt-1 text-2xl font-black leading-none {{ $stat['accent'] ? 'text-white' : 'text-[#223872]' }}">{{ number_format($stat['value']) }}</span>
                </div>
            @endforeach
        </section>

        {{-- ════════════════════════════════════════════════════════════════
             ROW 2 — FILTER TOOLBAR
             Row A: search + 4 quick filters + actions
             Row B: Pilihan 1 & 2 (dept selects, secondary)
        ════════════════════════════════════════════════════════════════ --}}
        <section aria-label="Filter Kandidat">
            <form method="GET" action="{{ route('admin.registrations') }}"
                  class="rounded-2xl border border-[#dce5f8] bg-white px-4 py-3 shadow-[0_4px_16px_rgba(34,56,114,0.06)]">

                {{-- Row A: search + quick filters --}}
                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative min-w-[180px] flex-[2]">
                        <svg class="absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-[#94a3b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input id="search" name="search" value="{{ request('search') }}" type="search"
                               placeholder="Nama, email, NIM, prodi…"
                               class="w-full rounded-xl border border-[#dce5f8] bg-[#F4F7FF] py-2 pl-9 pr-3 text-sm font-semibold text-[#333333] outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>

                    <select name="candidate_type" class="flex-1 rounded-xl border border-[#dce5f8] bg-[#F4F7FF] px-2.5 py-2 text-sm font-semibold text-[#333333] outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                        <option value="">Tipe</option>
                        <option value="staff" @selected(request('candidate_type') === 'staff')>Staff</option>
                        <option value="bph"   @selected(request('candidate_type') === 'bph')>BPH</option>
                    </select>

                    <select name="status" class="flex-1 rounded-xl border border-[#dce5f8] bg-[#F4F7FF] px-2.5 py-2 text-sm font-semibold text-[#333333] outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                        <option value="">Status</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $statusLabel[$s] ?? ucfirst($s) }}</option>
                        @endforeach
                    </select>

                    <select name="schedule_status" class="flex-1 rounded-xl border border-[#dce5f8] bg-[#F4F7FF] px-2.5 py-2 text-sm font-semibold text-[#333333] outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                        <option value="">Jadwal</option>
                        <option value="scheduled"   @selected(request('schedule_status') === 'scheduled')>Sudah pilih</option>
                        <option value="unscheduled" @selected(request('schedule_status') === 'unscheduled')>Belum pilih</option>
                    </select>

                    <select name="document_status" class="flex-1 rounded-xl border border-[#dce5f8] bg-[#F4F7FF] px-2.5 py-2 text-sm font-semibold text-[#333333] outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                        <option value="">Berkas</option>
                        <option value="complete"   @selected(request('document_status') === 'complete')>Lengkap</option>
                        <option value="incomplete" @selected(request('document_status') === 'incomplete')>Belum lengkap</option>
                    </select>

                    <button type="submit" class="shrink-0 rounded-xl bg-[#223872] px-4 py-2 text-sm font-black text-white transition hover:bg-[#1b2f60]">Filter</button>
                    <a href="{{ route('admin.registrations') }}" class="shrink-0 rounded-xl border border-[#dce5f8] px-4 py-2 text-sm font-black text-[#223872] transition hover:bg-[#F4F7FF]">Reset</a>
                </div>

                {{-- Row B: Department choice filters (secondary) --}}
                <div class="mt-2 flex gap-2 border-t border-[#edf2ff] pt-2">
                    <select name="first_choice_id" class="flex-1 rounded-xl border border-[#dce5f8] bg-[#F4F7FF] px-2.5 py-2 text-sm font-semibold text-[#333333] outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                        <option value="">Pilihan 1 — Semua Departemen/Biro</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) request('first_choice_id') === (string) $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                    <select name="second_choice_id" class="flex-1 rounded-xl border border-[#dce5f8] bg-[#F4F7FF] px-2.5 py-2 text-sm font-semibold text-[#333333] outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                        <option value="">Pilihan 2 — Semua Departemen/Biro</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) request('second_choice_id') === (string) $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </section>

        {{-- ════════════════════════════════════════════════════════════════
             ROW 3 — CANDIDATES TABLE
        ════════════════════════════════════════════════════════════════ --}}
        <section class="rounded-2xl border border-[#dce5f8] bg-white shadow-[0_4px_16px_rgba(34,56,114,0.06)]" aria-label="Daftar Kandidat">

            {{-- Table header bar --}}
            <div class="flex items-center justify-between gap-4 border-b border-[#edf2ff] px-5 py-3.5">
                <h3 class="text-sm font-black text-[#111827]">Daftar Kandidat</h3>
                <span class="rounded-full bg-[#EEF3FF] px-3 py-1 text-xs font-black text-[#223872]">
                    {{ number_format($candidates->total()) }} data
                </span>
            </div>

            <div class="overflow-x-auto">
                @if ($candidates->isEmpty())
                    <p class="px-5 py-10 text-center text-sm text-[#64748b]">
                        Tidak ada kandidat yang sesuai dengan filter.
                    </p>
                @else
                    <table class="min-w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-[#edf2ff] text-[0.6rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">
                                <th class="px-5 py-3">Kandidat</th>
                                <th class="px-4 py-3">NIM</th>
                                <th class="px-4 py-3">Prodi / Kelas</th>
                                <th class="px-4 py-3">Tipe</th>
                                <th class="px-4 py-3">Pilihan 1</th>
                                <th class="px-4 py-3">Pilihan 2</th>
                                <th class="px-4 py-3">Jadwal</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($candidates as $candidate)
                                @php
                                    $firstChoice      = $candidate->first_choice_department;
                                    $secondChoice     = $candidate->second_choice_department;
                                    $schedule         = $candidate->selectedInterviewSchedule?->schedule;
                                    $missingDocuments = collect($documentFields)->filter(fn ($label, $field) => blank($candidate->{$field}));
                                    $docsComplete     = $missingDocuments->isEmpty();
                                @endphp
                                <tr class="border-b border-[#edf2ff] transition last:border-0 hover:bg-[#F4F7FF]">

                                    {{-- Kandidat --}}
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#EEF3FF] text-xs font-black text-[#223872]">
                                                {{ strtoupper(substr($candidate->user?->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate font-black text-[#111827]">{{ $candidate->user?->name ?? '-' }}</p>
                                                <p class="truncate text-[0.65rem] text-[#64748b]">{{ $candidate->user?->email ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- NIM --}}
                                    <td class="px-4 py-3 font-mono text-[0.68rem] text-[#64748b]">{{ $candidate->nim }}</td>

                                    {{-- Prodi/Kelas --}}
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-[#333333]">{{ $candidate->prodi }}</p>
                                        <p class="text-[0.65rem] text-[#64748b]">{{ $candidate->kelas }}</p>
                                    </td>

                                    {{-- Tipe --}}
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-[#EEF3FF] px-2.5 py-0.5 text-[0.65rem] font-black text-[#223872]">
                                            {{ strtoupper($candidate->candidate_type) }}
                                        </span>
                                    </td>

                                    {{-- Pilihan 1 --}}
                                    <td class="px-4 py-3 text-[#64748b]">{{ $firstChoice?->name ?? '-' }}</td>

                                    {{-- Pilihan 2 --}}
                                    <td class="px-4 py-3 text-[#64748b]">{{ $secondChoice?->name ?? '-' }}</td>

                                    {{-- Jadwal --}}
                                    <td class="px-4 py-3">
                                        @if ($schedule)
                                            <p class="font-bold text-[#333333]">{{ $schedule->session_name }}</p>
                                            <p class="text-[0.65rem] text-[#64748b]">{{ $schedule->scheduled_at?->locale('id')?->translatedFormat('d M Y, H:i') }}</p>
                                        @else
                                            <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[0.65rem] font-black text-amber-700">Belum pilih</span>
                                        @endif
                                    </td>

                                    {{-- Status + Berkas --}}
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-0.5 text-[0.65rem] font-black {{ $statusColor[$candidate->status] ?? 'bg-[#F4F7FF] text-[#64748b]' }}">
                                            {{ $statusLabel[$candidate->status] ?? ucfirst($candidate->status) }}
                                        </span>
                                        <span class="mt-1 block w-fit rounded-full px-2 py-0.5 text-[0.65rem] font-black {{ $docsComplete ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ $docsComplete ? 'Berkas OK' : 'Berkas kurang' }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 py-3">
                                        <button type="button"
                                                data-open-registration-modal="candidate-{{ $candidate->id }}"
                                                class="rounded-xl bg-[#223872] px-3 py-1.5 text-[0.68rem] font-black text-white transition hover:bg-[#1b2f60]">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            @if ($candidates->hasPages())
                <div class="border-t border-[#edf2ff] px-5 py-4">
                    {{ $candidates->links() }}
                </div>
            @endif
        </section>
    </div>

    {{-- ════════════════════════════════════════════════════════════════
         MODALS — Detail Kandidat
    ════════════════════════════════════════════════════════════════ --}}
    @foreach ($candidates as $candidate)
        @php
            $firstChoice      = $candidate->first_choice_department;
            $secondChoice     = $candidate->second_choice_department;
            $schedule         = $candidate->selectedInterviewSchedule?->schedule;
            $missingDocuments = collect($documentFields)->filter(fn ($label, $field) => blank($candidate->{$field}));
        @endphp

        <div id="candidate-{{ $candidate->id }}"
             class="fixed inset-0 z-50 hidden overflow-y-auto bg-[#06122d]/55 p-4 backdrop-blur-sm"
             data-registration-modal>
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
                    <button type="button" data-close-registration-modal
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
                                'Nama Panggilan'  => $candidate->nickname,
                                'NIM'             => $candidate->nim,
                                'Program Studi'   => $candidate->prodi,
                                'Kelas'           => $candidate->kelas,
                                'Nomor Telepon'   => $candidate->phone,
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
                            <p class="mt-1 leading-5 text-[#333333]">{{ $candidate->address ?: '-' }}</p>
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
                                <p class="mt-1 font-black text-[#223872]">{{ $schedule->session_name }}</p>
                                <p class="mt-0.5 text-[#64748b]">{{ $schedule->department?->name ?? '-' }} · {{ $schedule->scheduled_at?->locale('id')?->translatedFormat('d F Y, H:i') }} · {{ $schedule->location }}</p>
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
                                        <a href="{{ asset('storage/' . $candidate->{$field}) }}" target="_blank"
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
    @endforeach

    <script>
        document.querySelectorAll('[data-open-registration-modal]').forEach((button) => {
            button.addEventListener('click', () => {
                document.getElementById(button.dataset.openRegistrationModal)?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        });

        document.querySelectorAll('[data-close-registration-modal]').forEach((button) => {
            button.addEventListener('click', () => {
                button.closest('[data-registration-modal]')?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        });

        document.querySelectorAll('[data-registration-modal]').forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });
    </script>
@endsection
