@extends('admin.layout', ['title' => 'Rankings'])

@section('content')
<div class="space-y-6">
    <section class="rounded-[28px] border border-[#dbe5f5] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.22em] text-[#4A90E2]">Decision Support</p>
                <h2 class="mt-2 text-2xl font-black tracking-tight text-[#111827]">Rankings Kandidat</h2>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-[#64748B]">
                    Tinjau ranking hasil Profile Matching per departemen/biro atau secara keseluruhan berdasarkan skor SPK yang sudah tersimpan.
                </p>
            </div>

            <div class="inline-flex rounded-2xl border border-[#dbe5f5] bg-[#F4F7FF] p-1">
                <a href="{{ route('admin.rankings', ['mode' => 'department']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-bold transition {{ $mode === 'department' ? 'bg-[#223872] text-white shadow-sm' : 'text-[#64748B] hover:text-[#223872]' }}">
                    Per departemen/biro
                </a>
                <a href="{{ route('admin.rankings', ['mode' => 'all']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-bold transition {{ $mode === 'all' ? 'bg-[#223872] text-white shadow-sm' : 'text-[#64748B] hover:text-[#223872]' }}">
                    Semua
                </a>
            </div>
        </div>
    </section>

    @if($mode === 'department')
        @if($departmentRankings->isEmpty())
            <section class="rounded-[28px] border border-dashed border-[#cfdcf2] bg-white px-6 py-10 text-center shadow-sm">
                <h3 class="text-lg font-black text-[#111827]">Belum ada ranking yang bisa ditampilkan.</h3>
                <p class="mt-2 text-sm text-[#64748B]">Pastikan nilai evaluasi kandidat sudah diinput pada halaman Profile Matching.</p>
            </section>
        @else
            <div class="space-y-5">
                @foreach($departmentRankings as $group)
                    @php
                        $department = $group['department'];
                        $rankings = $group['rankings'];
                    @endphp
                    <section class="rounded-[28px] border border-[#dbe5f5] bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-3 border-b border-[#edf2fb] pb-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-xl font-black tracking-tight text-[#111827]">{{ $department->name }}</h3>
                                <p class="mt-1 text-sm text-[#64748B]">{{ $rankings->total() }} kandidat memiliki hasil ranking di departemen/biro ini.</p>
                            </div>
                            <div class="rounded-full bg-[#EEF4FF] px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-[#223872]">
                                Ranking departemen
                            </div>
                        </div>

                        <div class="mt-5 overflow-x-auto">
                            <table class="min-w-full divide-y divide-[#edf2fb]">
                                <thead class="bg-[#F8FBFF]">
                                    <tr class="text-left text-xs font-black uppercase tracking-[0.18em] text-[#94A3B8]">
                                        <th class="px-5 py-4">#</th>
                                        <th class="px-5 py-4">Nama</th>
                                        <th class="px-5 py-4">NIM</th>
                                        <th class="px-5 py-4">Departemen</th>
                                        <th class="px-5 py-4">Score</th>
                                        <th class="px-5 py-4">Status</th>
                                        <th class="px-5 py-4">Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#edf2fb] bg-white">
                                    @foreach($rankings as $item)
                                        <tr class="text-sm text-[#334155]">
                                            <td class="px-5 py-4">
                                                <span class="inline-flex h-8 min-w-[2rem] items-center justify-center rounded-full bg-[#EEF4FF] px-2 font-black text-[#223872]">
                                                    {{ $item['rank_position'] ?? $loop->iteration }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 font-bold text-[#111827]">{{ $item['candidate']->user->name ?? '-' }}</td>
                                            <td class="px-5 py-4 text-[#64748B]">{{ $item['candidate']->user->nim ?? '-' }}</td>
                                            <td class="px-5 py-4 font-semibold text-[#223872]">{{ $department->name }}</td>
                                            <td class="px-5 py-4">
                                                <span class="inline-flex rounded-full bg-[#223872] px-3 py-1 text-xs font-black text-white">
                                                    {{ number_format((float) ($item['total_score'] ?? 0), 4) }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="inline-flex rounded-full border border-[#dbe5f5] bg-[#F8FBFF] px-3 py-1 text-xs font-bold text-[#475569]">
                                                    {{ ucfirst($item['candidate']->status ?? 'unknown') }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <a href="{{ route('admin.profile-matching.calculation', [$department->id, $item['candidate']->id]) }}"
                                                    class="inline-flex items-center rounded-full bg-[#223872] px-3 py-1 text-xs font-bold text-white transition hover:bg-[#1B2E5C]">
                                                    Lihat Detail Hitung
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex flex-col gap-3 border-t border-[#edf2fb] pt-4 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm text-[#64748B]">
                                Showing {{ $rankings->firstItem() ?? 0 }} to {{ $rankings->lastItem() ?? 0 }} of {{ $rankings->total() }} results
                            </p>
                            @if($rankings->hasPages())
                                <div class="rounded-2xl border border-[#dbe5f5] bg-white px-2 py-2 shadow-sm">
                                    {{ $rankings->links() }}
                                </div>
                            @endif
                        </div>
                    </section>
                @endforeach
            </div>
        @endif
    @else
        <section class="rounded-[28px] border border-[#dbe5f5] bg-white shadow-sm overflow-hidden">
            <div class="flex flex-col gap-2 border-b border-[#edf2fb] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-black tracking-tight text-[#111827]">Ranking Semua Hasil SPK</h3>
                    <p class="mt-1 text-sm text-[#64748B]">Urutan seluruh hasil SPK aktif berdasarkan skor tertinggi.</p>
                </div>
                <div class="rounded-full bg-[#EEF4FF] px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-[#223872]">
                    {{ $allRankings->count() }} hasil
                </div>
            </div>

            @if($allRankings->isEmpty())
                <div class="px-6 py-10 text-center">
                    <h4 class="text-lg font-black text-[#111827]">Belum ada hasil ranking tersimpan.</h4>
                    <p class="mt-2 text-sm text-[#64748B]">Jalankan atau buka halaman Profile Matching per departemen terlebih dahulu.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#edf2fb]">
                        <thead class="bg-[#F8FBFF]">
                            <tr class="text-left text-xs font-black uppercase tracking-[0.18em] text-[#94A3B8]">
                                <th class="px-6 py-4">#</th>
                                <th class="px-6 py-4">Nama</th>
                                <th class="px-6 py-4">NIM</th>
                                <th class="px-6 py-4">Departemen</th>
                                <th class="px-6 py-4">Score</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#edf2fb] bg-white">
                            @foreach($allRankings as $index => $result)
                                <tr class="text-sm text-[#334155]">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex h-8 min-w-[2rem] items-center justify-center rounded-full bg-[#EEF4FF] px-2 font-black text-[#223872]">
                                            {{ (($allRankings->currentPage() - 1) * $allRankings->perPage()) + $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-[#111827]">{{ $result->candidate?->user?->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-[#64748B]">{{ $result->candidate?->user?->nim ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-[#223872]">{{ $result->department?->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full bg-[#223872] px-3 py-1 text-xs font-black text-white">
                                            {{ number_format((float) $result->final_score, 4) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full bg-[#F8FBFF] px-3 py-1 text-xs font-bold text-[#475569] border border-[#dbe5f5]">
                                            {{ ucfirst($result->candidate?->status ?? 'unknown') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($result->department && $result->candidate)
                                            <a href="{{ route('admin.profile-matching.calculation', [$result->department->id, $result->candidate->id]) }}"
                                                class="inline-flex items-center rounded-full bg-[#223872] px-3 py-1 text-xs font-bold text-white transition hover:bg-[#1B2E5C]">
                                                Lihat Detail Hitung
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($allRankings->hasPages())
                    <div class="border-t border-[#edf2fb] px-6 py-4">
                        {{ $allRankings->links() }}
                    </div>
                @endif
            @endif
        </section>
    @endif
</div>
@endsection
