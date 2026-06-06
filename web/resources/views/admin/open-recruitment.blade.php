@extends('admin.layout', ['title' => 'Open Recruitment', 'hideStubBadge' => true])

@section('content')
    @php
        $typeLabels = ['staff' => 'Staff', 'bph' => 'BPH'];
        $statusLabels = ['open' => 'Open', 'closed' => 'Closed'];
        $statusClasses = [
            'open' => 'bg-emerald-100 text-emerald-700',
            'closed' => 'bg-red-100 text-red-700',
        ];
    @endphp

    <div class="mx-auto max-w-7xl space-y-4">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
             ROW 1 — RECRUITMENT CONTROLS
        ════════════════════════════════════════════════════════════════ --}}
        <section class="grid gap-4 lg:grid-cols-2">
            @foreach (['staff', 'bph'] as $type)
                @php
                    $row = $openRecruitments->get($type);
                    $isOpen = $row?->isCurrentlyOpen() ?? false;
                    $timeState = !$row
                        ? 'Belum Ada'
                        : ($isOpen
                            ? 'Sedang Dibuka'
                            : ($row->status === 'closed'
                                ? 'Ditutup'
                                : ($row->starts_at && now()->lt($row->starts_at)
                                    ? 'Belum Mulai'
                                    : 'Sudah Lewat')));
                @endphp

                <article class="rounded-2xl border border-[#dce5f8] bg-white p-4 shadow-[0_4px_16px_rgba(34,56,114,0.06)]">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#4A90E2]">Periode Recruitment</p>
                            <h3 class="mt-1 text-base font-black text-[#111827]">{{ $typeLabels[$type] }}</h3>
                            <p class="mt-1 text-xs text-[#94a3b8]">
                                {{ $row ? $openRecruitmentService->messageFor($row) : "Belum ada periode {$typeLabels[$type]}. Isi tanggal lalu pilih Open atau Close." }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full px-3 py-1 text-[0.65rem] font-black {{ $row ? ($statusClasses[$row->status] ?? 'bg-[#F4F7FF] text-[#64748b]') : 'bg-amber-100 text-amber-700' }}">
                                {{ $row ? $statusLabels[$row->status] : 'Belum Ada' }}
                            </span>
                            <span class="rounded-full px-3 py-1 text-[0.65rem] font-black {{ $isOpen ? 'bg-emerald-100 text-emerald-700' : 'bg-[#F4F7FF] text-[#64748b]' }}">
                                {{ $timeState }}
                            </span>
                        </div>
                    </div>

                    @if ($row)
                        <form method="POST" action="{{ route('admin.open-recruitment.update', $row) }}" class="mt-4 grid gap-3 sm:grid-cols-2">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="mb-1 block text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">Mulai</label>
                                <input name="starts_at" type="datetime-local" value="{{ $row->starts_at?->format('Y-m-d\TH:i') }}"
                                    class="w-full rounded-xl border border-[#c7d2e5] bg-[#F4F7FF] px-3 py-2 text-sm font-semibold text-[#333333] outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20" required>
                            </div>

                            <div>
                                <label class="mb-1 block text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">Selesai</label>
                                <input name="ends_at" type="datetime-local" value="{{ $row->ends_at?->format('Y-m-d\TH:i') }}"
                                    class="w-full rounded-xl border border-[#c7d2e5] bg-[#F4F7FF] px-3 py-2 text-sm font-semibold text-[#333333] outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20" required>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">Lokasi Wawancara (Opsional)</label>
                                <input name="interview_location" type="text" value="{{ old('interview_location', $row->interview_location) }}" placeholder="Gedung / Ruangan / Link Zoom"
                                    class="w-full rounded-xl border border-[#c7d2e5] bg-[#F4F7FF] px-3 py-2 text-sm font-semibold text-[#333333] outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">Persyaratan Wawancara (Opsional)</label>
                                <textarea name="interview_requirements" rows="2" placeholder="Gunakan format bullet list atau teks biasa."
                                    class="w-full rounded-xl border border-[#c7d2e5] bg-[#F4F7FF] px-3 py-2 text-sm font-semibold text-[#333333] outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">{{ old('interview_requirements', $row->interview_requirements) }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <button type="submit" class="w-full rounded-xl bg-[#223872] px-4 py-2 text-sm font-black text-white transition hover:bg-[#122452]">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>

                        <div class="mt-3 flex gap-2">
                            <form method="POST" action="{{ route('admin.open-recruitment.status', $row) }}" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="open">
                                <button type="submit" @disabled($row->status === 'open')
                                    class="w-full rounded-xl px-4 py-2 text-sm font-black transition {{ $row->status === 'open' ? 'bg-[#EEF4FF] text-[#64748B] cursor-not-allowed' : 'bg-[#223872] text-white hover:bg-[#122452]' }}">
                                    Open
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.open-recruitment.status', $row) }}" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="closed">
                                <button type="submit" @disabled($row->status === 'closed')
                                    class="w-full rounded-xl border {{ $row->status === 'closed' ? 'border-[#D8E2F3] bg-[#F4F7FF] text-[#64748B] cursor-not-allowed' : 'border-[#223872] bg-white text-[#223872] hover:bg-[#EEF4FF]' }} px-4 py-2 text-sm font-black transition">
                                    Close
                                </button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('admin.open-recruitment.store') }}" class="mt-4 grid gap-3 sm:grid-cols-2">
                            @csrf
                            <input type="hidden" name="candidate_type" value="{{ $type }}">

                            <div>
                                <label class="mb-1 block text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">Mulai</label>
                                <input name="starts_at" type="datetime-local"
                                    class="w-full rounded-xl border border-[#c7d2e5] bg-[#F4F7FF] px-3 py-2 text-sm font-semibold text-[#333333] outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20" required>
                            </div>

                            <div>
                                <label class="mb-1 block text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">Selesai</label>
                                <input name="ends_at" type="datetime-local"
                                    class="w-full rounded-xl border border-[#c7d2e5] bg-[#F4F7FF] px-3 py-2 text-sm font-semibold text-[#333333] outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20" required>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">Lokasi Wawancara (Opsional)</label>
                                <input name="interview_location" type="text" placeholder="Gedung / Ruangan / Link Zoom"
                                    class="w-full rounded-xl border border-[#c7d2e5] bg-[#F4F7FF] px-3 py-2 text-sm font-semibold text-[#333333] outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">Persyaratan Wawancara (Opsional)</label>
                                <textarea name="interview_requirements" rows="2" placeholder="Gunakan format bullet list atau teks biasa."
                                    class="w-full rounded-xl border border-[#c7d2e5] bg-[#F4F7FF] px-3 py-2 text-sm font-semibold text-[#333333] outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20"></textarea>
                            </div>

                            <div class="flex gap-2 sm:col-span-2">
                                <button type="submit" name="status" value="open" class="flex-1 rounded-xl bg-[#223872] px-4 py-2 text-sm font-black text-white transition hover:bg-[#122452]">
                                    Open
                                </button>
                                <button type="submit" name="status" value="closed" class="flex-1 rounded-xl border border-[#223872] bg-white px-4 py-2 text-sm font-black text-[#223872] transition hover:bg-[#EEF4FF]">
                                    Close
                                </button>
                            </div>
                        </form>
                    @endif
                </article>
            @endforeach
        </section>

        {{-- ════════════════════════════════════════════════════════════════
             ROW 1.5 — EXTEND PERIODE
        ════════════════════════════════════════════════════════════════ --}}
        @if ($openRecruitments->has('staff') || $openRecruitments->has('bph'))
            <section class="overflow-hidden rounded-2xl border border-[#dce5f8] bg-white shadow-[0_4px_16px_rgba(34,56,114,0.06)]" aria-label="Extend Periode">
                <button type="button" data-accordion-btn class="flex w-full items-center justify-between px-5 py-4 text-left transition hover:bg-[#EEF4FF]">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#EEF4FF] text-[#4A90E2]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-black text-[#111827]">Extend Periode</h3>
                            <p class="mt-0.5 text-xs text-[#94a3b8]">Perpanjang masa pendaftaran yang sedang berjalan.</p>
                        </div>
                    </div>
                    <svg class="h-5 w-5 text-[#94a3b8] transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div class="max-h-0 overflow-hidden border-t border-[#dce5f8] bg-[#F4F7FF] opacity-0 transition-all duration-300 ease-out" data-accordion-content>
                    <div class="p-5">
                    <div class="grid gap-6 sm:grid-cols-2">
                        @foreach (['staff', 'bph'] as $type)
                            @if ($row = $openRecruitments->get($type))
                                <div>
                                    <div class="mb-3 flex items-center justify-between">
                                        <h4 class="text-xs font-black uppercase tracking-[0.14em] text-[#223872]">Extend {{ $typeLabels[$type] }}</h4>
                                        <span class="rounded-full bg-white px-2 py-0.5 text-[0.65rem] font-bold text-[#64748b]">Diperpanjang {{ $row->extensions->count() }}x</span>
                                    </div>
                                    <form method="POST" action="{{ route('admin.open-recruitment.extend', $row) }}" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="mb-1 block text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">Selesai Baru</label>
                                            <input name="new_ends_at" type="datetime-local" value="{{ $row->ends_at?->format('Y-m-d\TH:i') }}"
                                                class="w-full rounded-xl border border-[#c7d2e5] bg-white px-3 py-2 text-sm font-semibold text-[#333333] outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20" required>
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-[0.65rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">Alasan (opsional)</label>
                                            <input name="reason" type="text" class="w-full rounded-xl border border-[#c7d2e5] bg-white px-3 py-2 text-sm font-semibold text-[#333333] outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20" placeholder="Contoh: Kuota belum penuh">
                                        </div>
                                        <button type="submit" class="w-full rounded-xl border border-[#c7d2e5] bg-white px-4 py-2 text-sm font-black text-[#223872] transition hover:bg-[#EEF4FF]">
                                            Simpan Extend
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
             ROW 2 — QUOTA DEPARTEMEN
        ════════════════════════════════════════════════════════════════ --}}
        <section class="rounded-2xl border border-[#dce5f8] bg-white shadow-[0_4px_16px_rgba(34,56,114,0.06)]" aria-label="Quota Departemen">
            <div class="flex flex-col gap-1 border-b border-[#edf2ff] px-5 py-3.5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-sm font-black text-[#111827]">Quota Departemen / Biro</h3>
                    <p class="text-[0.65rem] text-[#94a3b8]">Quota operasional. Perubahan dicatat ke audit log.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.open-recruitment.quotas.update') }}">
                @csrf
                @method('PUT')

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-[#edf2ff] text-[0.6rem] font-black uppercase tracking-[0.14em] text-[#94a3b8]">
                                <th class="px-5 py-3">Departemen/Biro</th>
                                @foreach (['staff', 'bph'] as $type)
                                    <th class="px-4 py-3">Quota {{ $typeLabels[$type] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($departments as $department)
                                <tr class="border-b border-[#edf2ff] transition last:border-0 hover:bg-[#F4F7FF]">
                                    <td class="px-5 py-3 font-bold text-[#111827]">{{ $department->name }}</td>
                                    @foreach (['staff', 'bph'] as $type)
                                        @php
                                            $quota = $quotasByType->get($type)?->get($department->id)?->quota ?? 0;
                                        @endphp
                                        <td class="px-4 py-2">
                                            <input type="number" min="0" name="quotas[{{ $type }}][{{ $department->id }}]" value="{{ $quota }}"
                                                class="w-24 rounded-xl border border-[#dce5f8] bg-white px-3 py-1.5 text-xs font-black text-[#223872] outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-8 text-center text-sm font-bold text-[#94a3b8]">Belum ada departemen/biro aktif.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-[#edf2ff] bg-[#F4F7FF] px-5 py-3 rounded-b-2xl">
                    <button type="submit" class="rounded-xl bg-[#223872] px-4 py-2 text-sm font-black text-white transition hover:bg-[#1b2f60]">
                        Simpan Quota
                    </button>
                </div>
            </form>
        </section>

        {{-- ════════════════════════════════════════════════════════════════
             ROW 3 — LOGS
        ════════════════════════════════════════════════════════════════ --}}
        <section class="grid gap-4 lg:grid-cols-2">
            <article class="rounded-2xl border border-[#dce5f8] bg-white p-4 shadow-[0_4px_16px_rgba(34,56,114,0.06)]">
                <h3 class="mb-3 text-sm font-black text-[#111827]">Riwayat Extend</h3>
                <div class="space-y-2">
                    @forelse ($openRecruitments->flatMap(fn($row) => $row->extensions->map(fn($extension) => [$row, $extension]))->sortByDesc(fn($pair) => $pair[1]->created_at)->take(8) as [$row, $extension])
                        <div class="rounded-xl bg-[#F4F7FF] px-3 py-2.5 text-xs">
                            <p class="font-black text-[#223872]">{{ $typeLabels[$row->candidate_type] }} diperpanjang oleh {{ $extension->extender?->name ?? 'Admin' }}</p>
                            <p class="mt-0.5 text-[#94a3b8]">{{ $extension->old_ends_at?->locale('id')?->translatedFormat('d M Y H:i') }} → {{ $extension->new_ends_at?->locale('id')?->translatedFormat('d M Y H:i') }}</p>
                            @if ($extension->reason)
                                <p class="mt-1 font-semibold text-[#64748b]">{{ $extension->reason }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-[#dce5f8] bg-[#F4F7FF] px-3 py-4 text-center text-xs text-[#94a3b8]">Belum ada riwayat extend.</p>
                    @endforelse
                </div>
            </article>

            <article class="rounded-2xl border border-[#dce5f8] bg-white p-4 shadow-[0_4px_16px_rgba(34,56,114,0.06)]">
                <h3 class="mb-3 text-sm font-black text-[#111827]">Audit Quota Terbaru</h3>
                <div class="space-y-2">
                    @forelse ($quotaLogs as $log)
                        <div class="rounded-xl bg-[#F4F7FF] px-3 py-2.5 text-xs">
                            <p class="font-black text-[#223872]">{{ $log->department?->name ?? '-' }} · <span class="uppercase tracking-widest text-[#4A90E2]">{{ $log->candidate_type ?? '-' }}</span></p>
                            <p class="mt-0.5 text-[#94a3b8]">{{ $log->old_quota ?? 0 }} → <span class="font-bold text-[#111827]">{{ $log->new_quota }}</span> oleh {{ $log->changer?->name ?? 'Admin' }}</p>
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-[#dce5f8] bg-[#F4F7FF] px-3 py-4 text-center text-xs text-[#94a3b8]">Belum ada perubahan quota.</p>
                    @endforelse
                </div>
            </article>
        </section>
    </div>

    <script>
        document.querySelectorAll('[data-accordion-btn]').forEach(btn => {
            btn.addEventListener('click', () => {
                const content = btn.nextElementSibling;
                const icon = btn.querySelectorAll('svg')[1];
                const isOpen = content.dataset.open === 'true';

                content.dataset.open = isOpen ? 'false' : 'true';
                content.style.maxHeight = isOpen ? '0px' : `${content.scrollHeight}px`;
                content.classList.toggle('opacity-0', isOpen);
                content.classList.toggle('opacity-100', !isOpen);
                icon.classList.toggle('rotate-180');
            });
        });
    </script>
@endsection
