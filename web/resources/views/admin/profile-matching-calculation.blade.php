@extends('admin.layout', ['title' => 'Detail Perhitungan SPK'])

@section('content')
    @php
        $finalScore = (float) ($spkResult->final_score ?? data_get($details, 'final_score', 0));
        $personalScore = (float) data_get($details, 'personal_score', 0);
        $organizationalScore = (float) data_get($details, 'organizational_score', 0);
        $personalCoreScore = (float) data_get($details, 'personal_core_score', 0);
        $personalSecondaryScore = (float) data_get($details, 'personal_secondary_score', 0);
        $organizationalCoreScore = (float) data_get($details, 'organizational_core_score', 0);
        $organizationalSecondaryScore = (float) data_get($details, 'organizational_secondary_score', 0);
        $ncf = (float) data_get($details, 'ncf', 0);
        $nsf = (float) data_get($details, 'nsf', 0);
        $personalWeight = (float) data_get($weights, 'personal_aspect_weight', $department->personal_aspect_weight);
        $organizationalWeight = (float) data_get($weights, 'organizational_aspect_weight', $department->organizational_aspect_weight);
        $coreWeight = (float) data_get($weights, 'core_factor_weight', $department->core_factor_weight);
        $secondaryWeight = (float) data_get($weights, 'secondary_factor_weight', $department->secondary_factor_weight);

        $criteriaCodes = $breakdown->pluck('code')->map(fn ($code) => $code ?: '-')->values();
        $criteriaNames = $breakdown->pluck('criteria_name')->values();
        $actualMatrix = $breakdown->pluck('actual_score')->values();
        $targetMatrix = $breakdown->pluck('target_score')->values();
        $gapMatrix = $breakdown->pluck('gap')->values();
        $weightMatrix = $breakdown->pluck('mapped_weight')->map(fn ($value) => number_format((float) $value, 4))->values();
        $typeMatrix = $breakdown->pluck('criteria_type')->map(fn ($value) => strtoupper($value === 'core' ? 'CF' : 'SF'))->values();
        $aspectMatrix = $breakdown->pluck('aspect')->map(fn ($value) => ucfirst($value))->values();
    @endphp

    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-[#4A90E2]">Profile Matching</p>
                <h1 class="text-2xl font-black tracking-tight text-[#111827]">Detail Perhitungan SPK</h1>
                <p class="mt-1 text-sm text-[#64748B]">Format presentasi matriks untuk audit langkah perhitungan kandidat per departemen.</p>
            </div>
            <a href="{{ route('admin.profile-matching', ['department_id' => $department->id]) }}"
               class="inline-flex items-center justify-center rounded-xl border border-[#D8E2F3] bg-white px-4 py-2 text-sm font-bold text-[#223872] transition hover:border-[#4A90E2] hover:text-[#1b2f60]">
                Kembali ke Profile Matching
            </a>
        </div>

        <section class="grid gap-4 lg:grid-cols-4">
            <article class="rounded-2xl border border-[#D8E2F3] bg-white p-5 shadow-sm lg:col-span-2">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-[#94A3B8]">Objek Perhitungan</p>
                <h2 class="mt-2 text-xl font-black text-[#111827]">{{ $candidate->user?->name ?? '-' }}</h2>
                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    <div>
                        <p class="text-[0.7rem] font-black uppercase tracking-[0.14em] text-[#94A3B8]">NIM</p>
                        <p class="mt-1 text-sm font-semibold text-[#334155]">{{ $candidate->user?->nim ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[0.7rem] font-black uppercase tracking-[0.14em] text-[#94A3B8]">Departemen</p>
                        <p class="mt-1 text-sm font-semibold text-[#334155]">{{ $department->name }}</p>
                    </div>
                    <div>
                        <p class="text-[0.7rem] font-black uppercase tracking-[0.14em] text-[#94A3B8]">Calculated At</p>
                        <p class="mt-1 text-sm font-semibold text-[#334155]">{{ $spkResult?->calculated_at?->format('d M Y H:i') ?? 'Belum tersedia' }}</p>
                    </div>
                    <div>
                        <p class="text-[0.7rem] font-black uppercase tracking-[0.14em] text-[#94A3B8]">Rank</p>
                        <p class="mt-1 text-sm font-semibold text-[#334155]">{{ $spkResult?->rank_position ? '#'.$spkResult->rank_position : 'Belum tersedia' }}</p>
                    </div>
                </div>
            </article>
            <article class="rounded-2xl border border-[#D8E2F3] bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-[#94A3B8]">Final Score</p>
                <p class="mt-2 text-3xl font-black text-[#223872]">{{ number_format($finalScore, 4) }}</p>
                <p class="mt-1 text-sm text-[#64748B]">Nilai akhir hasil pembobotan aspek.</p>
            </article>
            <article class="rounded-2xl border border-[#D8E2F3] bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-[#94A3B8]">NCF / NSF Global</p>
                <div class="mt-2 space-y-2">
                    <div class="flex items-center justify-between rounded-xl bg-[#F4F7FF] px-3 py-2">
                        <span class="text-sm font-bold text-[#475569]">NCF</span>
                        <span class="font-mono text-sm font-black text-[#223872]">{{ number_format($ncf, 4) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#F4F7FF] px-3 py-2">
                        <span class="text-sm font-bold text-[#475569]">NSF</span>
                        <span class="font-mono text-sm font-black text-[#223872]">{{ number_format($nsf, 4) }}</span>
                    </div>
                </div>
            </article>
        </section>

        @if($breakdown->isEmpty())
            <section class="rounded-2xl border border-dashed border-[#D8E2F3] bg-white p-10 text-center shadow-sm">
                <h2 class="text-lg font-black text-[#111827]">Data perhitungan belum tersedia</h2>
                <p class="mt-2 text-sm text-[#64748B]">Belum ada breakdown SPK yang dapat ditampilkan untuk kandidat dan departemen ini.</p>
            </section>
        @else
            <section class="rounded-2xl border border-[#D8E2F3] bg-white shadow-sm">
                <div class="border-b border-[#EDF2FF] px-6 py-4">
                    <h2 class="text-lg font-black text-[#111827]">1. Matriks Profil dan Gap</h2>
                    <p class="mt-1 text-sm text-[#64748B]">Representasi horizontal per kriteria agar urutan hitung lebih mudah dilacak.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <tbody class="divide-y divide-[#EDF2FF]">
                            @foreach([
                                'Kode Kriteria' => $criteriaCodes,
                                'Nama Kriteria' => $criteriaNames,
                                'Aspek' => $aspectMatrix,
                                'Tipe' => $typeMatrix,
                                'Nilai Aktual' => $actualMatrix,
                                'Target Score' => $targetMatrix,
                                'Gap' => $gapMatrix,
                                'Bobot Gap' => $weightMatrix,
                            ] as $label => $rowValues)
                                <tr>
                                    <th class="w-48 bg-[#F8FAFC] px-5 py-3 text-left text-[0.72rem] font-black uppercase tracking-[0.12em] text-[#64748B]">{{ $label }}</th>
                                    @foreach($rowValues as $value)
                                        <td class="min-w-[116px] px-4 py-3 text-center {{ $loop->parent->iteration <= 2 ? 'font-semibold text-[#111827]' : 'font-mono text-[#334155]' }}">
                                            {{ $value }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-2">
                @foreach(['personal' => 'Personal', 'organizational' => 'Organizational'] as $aspectKey => $aspectLabel)
                    <article class="rounded-2xl border border-[#D8E2F3] bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-black text-[#111827]">2. Matriks {{ $aspectLabel }}</h2>
                                <p class="mt-1 text-sm text-[#64748B]">Pemecahan kriteria berdasarkan Core Factor dan Secondary Factor.</p>
                            </div>
                            <span class="rounded-full bg-[#EEF3FF] px-3 py-1 text-xs font-black text-[#223872]">
                                {{ $groupedBreakdown[$aspectKey]['core']->count() + $groupedBreakdown[$aspectKey]['secondary']->count() }} kriteria
                            </span>
                        </div>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            @foreach(['core' => 'Core Factor', 'secondary' => 'Secondary Factor'] as $typeKey => $typeLabel)
                                <div class="rounded-2xl bg-[#F8FAFC] p-4">
                                    <h3 class="text-sm font-black text-[#223872]">{{ $typeLabel }}</h3>
                                    @if($groupedBreakdown[$aspectKey][$typeKey]->isEmpty())
                                        <p class="mt-3 rounded-xl border border-dashed border-[#D8E2F3] px-3 py-4 text-center text-xs text-[#94A3B8]">Tidak ada kriteria dalam grup ini.</p>
                                    @else
                                        <div class="mt-3 overflow-x-auto">
                                            <table class="min-w-full text-xs">
                                                <thead>
                                                    <tr class="text-[#94A3B8]">
                                                        <th class="px-2 py-2 text-left font-black uppercase tracking-[0.12em]">Kode</th>
                                                        <th class="px-2 py-2 text-center font-black uppercase tracking-[0.12em]">Aktual</th>
                                                        <th class="px-2 py-2 text-center font-black uppercase tracking-[0.12em]">Target</th>
                                                        <th class="px-2 py-2 text-center font-black uppercase tracking-[0.12em]">Gap</th>
                                                        <th class="px-2 py-2 text-center font-black uppercase tracking-[0.12em]">Bobot</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-[#E2E8F0]">
                                                    @foreach($groupedBreakdown[$aspectKey][$typeKey] as $item)
                                                        <tr>
                                                            <td class="px-2 py-2 font-bold text-[#111827]">{{ $item['code'] ?: '-' }}</td>
                                                            <td class="px-2 py-2 text-center font-mono text-[#334155]">{{ $item['actual_score'] }}</td>
                                                            <td class="px-2 py-2 text-center font-mono text-[#334155]">{{ $item['target_score'] }}</td>
                                                            <td class="px-2 py-2 text-center font-mono text-[#334155]">{{ $item['gap'] }}</td>
                                                            <td class="px-2 py-2 text-center font-mono font-black text-[#223872]">{{ number_format((float) $item['mapped_weight'], 4) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </section>

            <section class="rounded-2xl border border-[#D8E2F3] bg-white p-5 shadow-sm">
                <h2 class="text-lg font-black text-[#111827]">3. Rekap NCF / NSF per Aspek</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @foreach([
                        ['label' => 'Personal Core', 'value' => $personalCoreScore],
                        ['label' => 'Personal Secondary', 'value' => $personalSecondaryScore],
                        ['label' => 'Organizational Core', 'value' => $organizationalCoreScore],
                        ['label' => 'Organizational Secondary', 'value' => $organizationalSecondaryScore],
                    ] as $metric)
                        <div class="rounded-2xl bg-[#F4F7FF] px-4 py-4">
                            <p class="text-[0.72rem] font-black uppercase tracking-[0.12em] text-[#94A3B8]">{{ $metric['label'] }}</p>
                            <p class="mt-2 font-mono text-2xl font-black text-[#223872]">{{ number_format((float) $metric['value'], 4) }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-2">
                <article class="rounded-2xl border border-[#D8E2F3] bg-white p-5 shadow-sm">
                    <h2 class="text-lg font-black text-[#111827]">4. Rumus Skor Aspek</h2>
                    <div class="mt-4 space-y-4">
                        <div class="rounded-2xl bg-[#F8FAFC] p-4">
                            <p class="text-sm font-black text-[#223872]">Skor Personal</p>
                            <p class="mt-2 text-sm text-[#475569]">(CF {{ number_format($coreWeight / 100, 2) }} x {{ number_format($personalCoreScore, 4) }}) + (SF {{ number_format($secondaryWeight / 100, 2) }} x {{ number_format($personalSecondaryScore, 4) }})</p>
                            <p class="mt-3 font-mono text-xl font-black text-[#111827]">= {{ number_format($personalScore, 4) }}</p>
                        </div>
                        <div class="rounded-2xl bg-[#F8FAFC] p-4">
                            <p class="text-sm font-black text-[#223872]">Skor Organizational</p>
                            <p class="mt-2 text-sm text-[#475569]">(CF {{ number_format($coreWeight / 100, 2) }} x {{ number_format($organizationalCoreScore, 4) }}) + (SF {{ number_format($secondaryWeight / 100, 2) }} x {{ number_format($organizationalSecondaryScore, 4) }})</p>
                            <p class="mt-3 font-mono text-xl font-black text-[#111827]">= {{ number_format($organizationalScore, 4) }}</p>
                        </div>
                    </div>
                </article>

                <article class="rounded-2xl border border-[#D8E2F3] bg-white p-5 shadow-sm">
                    <h2 class="text-lg font-black text-[#111827]">5. Rekap Pembobotan Akhir</h2>
                    <div class="overflow-hidden rounded-2xl border border-[#EDF2FF]">
                        <table class="min-w-full text-sm">
                            <tbody class="divide-y divide-[#EDF2FF]">
                                <tr>
                                    <th class="bg-[#F8FAFC] px-4 py-3 text-left text-[0.72rem] font-black uppercase tracking-[0.12em] text-[#64748B]">Bobot Personal</th>
                                    <td class="px-4 py-3 font-mono font-bold text-[#334155]">{{ number_format($personalWeight, 2) }}%</td>
                                </tr>
                                <tr>
                                    <th class="bg-[#F8FAFC] px-4 py-3 text-left text-[0.72rem] font-black uppercase tracking-[0.12em] text-[#64748B]">Bobot Organizational</th>
                                    <td class="px-4 py-3 font-mono font-bold text-[#334155]">{{ number_format($organizationalWeight, 2) }}%</td>
                                </tr>
                                <tr>
                                    <th class="bg-[#F8FAFC] px-4 py-3 text-left text-[0.72rem] font-black uppercase tracking-[0.12em] text-[#64748B]">Bobot Core Factor</th>
                                    <td class="px-4 py-3 font-mono font-bold text-[#334155]">{{ number_format($coreWeight, 2) }}%</td>
                                </tr>
                                <tr>
                                    <th class="bg-[#F8FAFC] px-4 py-3 text-left text-[0.72rem] font-black uppercase tracking-[0.12em] text-[#64748B]">Bobot Secondary Factor</th>
                                    <td class="px-4 py-3 font-mono font-bold text-[#334155]">{{ number_format($secondaryWeight, 2) }}%</td>
                                </tr>
                                <tr>
                                    <th class="bg-[#F8FAFC] px-4 py-3 text-left text-[0.72rem] font-black uppercase tracking-[0.12em] text-[#64748B]">Rumus Akhir</th>
                                    <td class="px-4 py-3 text-[#334155]">(Personal {{ number_format($personalWeight / 100, 2) }} x {{ number_format($personalScore, 4) }}) + (Organizational {{ number_format($organizationalWeight / 100, 2) }} x {{ number_format($organizationalScore, 4) }})</td>
                                </tr>
                                <tr>
                                    <th class="bg-[#EEF3FF] px-4 py-3 text-left text-[0.72rem] font-black uppercase tracking-[0.12em] text-[#223872]">Final Score</th>
                                    <td class="bg-[#EEF3FF] px-4 py-3 font-mono text-xl font-black text-[#223872]">{{ number_format($finalScore, 4) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </article>
            </section>

            <section class="rounded-2xl border border-[#D8E2F3] bg-white p-5 shadow-sm">
                <h2 class="text-lg font-black text-[#111827]">6. Matriks Mapping Gap Weight</h2>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <tbody class="divide-y divide-[#EDF2FF]">
                            <tr>
                                <th class="w-48 bg-[#F8FAFC] px-5 py-3 text-left text-[0.72rem] font-black uppercase tracking-[0.12em] text-[#64748B]">Gap</th>
                                @foreach($gapWeights as $gap => $weight)
                                    <td class="min-w-[88px] px-4 py-3 text-center font-mono font-bold text-[#334155]">{{ $gap }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th class="w-48 bg-[#F8FAFC] px-5 py-3 text-left text-[0.72rem] font-black uppercase tracking-[0.12em] text-[#64748B]">Bobot</th>
                                @foreach($gapWeights as $gap => $weight)
                                    <td class="min-w-[88px] px-4 py-3 text-center font-mono font-black text-[#223872]">{{ number_format((float) $weight, 4) }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </div>
@endsection
