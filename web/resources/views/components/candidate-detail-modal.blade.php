@props(['candidate'])

@php
    $statusLabel = [
        'pending'   => 'Menunggu Seleksi',
        'evaluated' => 'Sudah Dinilai',
        'accepted'  => 'Lulus',
        'rejected'  => 'Ditolak',
    ];
    $documentFields = [
        'photo_path'               => 'Pas Foto',
        'instagram_proof_path'     => 'Bukti Instagram',
        'youtube_proof_path'       => 'Bukti Youtube',
        'political_statement_path' => 'Surat Pernyataan',
        'candidate_signature_path' => 'Tanda Tangan Calon',
        'parent_signature_path'    => 'Tanda Tangan Ortu',
    ];

    $firstChoice = $candidate->first_choice_department;
    $secondChoice = $candidate->second_choice_department;
    $schedule = $candidate->selectedInterviewSchedule?->schedule;
    $missingDocuments = collect($documentFields)->filter(fn($label, $field) => blank($candidate->{$field}));
@endphp

<dialog id="detailModal-{{ $candidate->id }}" class="m-auto w-11/12 max-w-5xl rounded-3xl bg-white p-0 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm open:animate-in open:fade-in-0 open:zoom-in-95">
    <div class="flex max-h-[90vh] flex-col">
        {{-- Header --}}
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
            <button type="button" onclick="document.getElementById('detailModal-{{ $candidate->id }}').close()"
                    class="rounded-xl bg-[#F4F7FF] px-3 py-2 text-sm font-black text-[#223872] transition hover:bg-[#dce5f8]">
                Tutup
            </button>
        </div>

        {{-- Modal body --}}
        <div class="overflow-y-auto grid gap-4 p-5 lg:grid-cols-12">

            {{-- Identitas --}}
            <section class="rounded-2xl border border-[#dce5f8] bg-[#F4F7FF] p-4 lg:col-span-5">
                <h4 class="text-sm font-black text-[#111827]">Informasi Identitas</h4>
                <dl class="mt-3 grid gap-1.5 text-xs">
                    @foreach ([
                        'Nama Panggilan'  => $candidate->user->nickname,
                        'NIM'             => $candidate->user->nim,
                        'Program Studi'   => $candidate->user->prodi,
                        'Kelas'           => $candidate->user->kelas,
                        'Nomor Telepon'   => $candidate->user->phone,
                        'Candidate Type'  => strtoupper($candidate->candidate_type ?? ''),
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
                        <div class="rounded-xl bg-[#F4F7FF] px-3 py-2">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-bold text-[#223872]">{{ $education->level }}</p>
                                <span class="text-[0.65rem] font-semibold text-[#64748b]">{{ $education->graduation_year }}</span>
                            </div>
                            <p class="mt-0.5 text-sm font-black text-[#111827]">{{ $education->institution_name }}</p>
                            <p class="mt-1 text-[0.65rem] text-[#64748b]">{{ $education->major ?: '-' }}</p>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-[#dce5f8] p-4 text-center text-xs font-bold text-[#64748b]">
                            Tidak ada riwayat pendidikan.
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Pengalaman Kepanitiaan --}}
            <section class="rounded-2xl border border-[#dce5f8] bg-white p-4 lg:col-span-6">
                <h4 class="text-sm font-black text-[#111827]">Pengalaman Kepanitiaan</h4>
                <div class="mt-3 space-y-2">
                    @forelse ($candidate->committees as $committee)
                        <div class="rounded-xl bg-[#F4F7FF] px-3 py-2">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-bold text-[#223872]">{{ $committee->position }}</p>
                                <span class="text-[0.65rem] font-semibold text-[#64748b]">{{ $committee->year }}</span>
                            </div>
                            <p class="mt-0.5 text-sm font-black text-[#111827]">{{ $committee->event_name }}</p>
                            <p class="mt-1 text-[0.65rem] text-[#64748b]">{{ $committee->level }}</p>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-[#dce5f8] p-4 text-center text-xs font-bold text-[#64748b]">
                            Tidak ada pengalaman kepanitiaan.
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Pengalaman Organisasi --}}
            <section class="rounded-2xl border border-[#dce5f8] bg-white p-4 lg:col-span-6">
                <h4 class="text-sm font-black text-[#111827]">Pengalaman Organisasi</h4>
                <div class="mt-3 space-y-2">
                    @forelse ($candidate->organizations as $org)
                        <div class="rounded-xl bg-[#F4F7FF] px-3 py-2">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-bold text-[#223872]">{{ $org->position }}</p>
                                <span class="text-[0.65rem] font-semibold text-[#64748b]">{{ $org->period }}</span>
                            </div>
                            <p class="mt-0.5 text-sm font-black text-[#111827]">{{ $org->organization_name }}</p>
                            <p class="mt-1 text-[0.65rem] text-[#64748b]">{{ $org->level }}</p>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-[#dce5f8] p-4 text-center text-xs font-bold text-[#64748b]">
                            Tidak ada pengalaman organisasi.
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Keahlian Tambahan --}}
            <section class="rounded-2xl border border-[#dce5f8] bg-white p-4 lg:col-span-6">
                <h4 class="text-sm font-black text-[#111827]">Keahlian (Skills)</h4>
                <div class="mt-3 flex flex-wrap gap-2">
                    @forelse ($candidate->skills as $skill)
                        <span class="inline-flex items-center gap-1 rounded-lg bg-[#EEF3FF] px-2.5 py-1 text-xs font-bold text-[#223872]">
                            <svg class="h-3.5 w-3.5 text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $skill->skill_name }}
                        </span>
                    @empty
                        <div class="w-full rounded-xl border border-dashed border-[#dce5f8] p-4 text-center text-xs font-bold text-[#64748b]">
                            Tidak ada keahlian tambahan.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</dialog>
