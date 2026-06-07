@extends('interviewer.layout', ['title' => 'Pendaftaran'])

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-[#111827]">Daftar Pendaftar</h1>
                <p class="text-sm text-[#64748B]">Lihat seluruh kandidat yang mendaftar ke HIMATIK.</p>
            </div>
            <div class="rounded-xl bg-white px-4 py-2 border border-[#dce5f8] shadow-sm">
                <p class="text-xs font-bold text-[#64748B]">Total Pendaftar</p>
                <p class="text-xl font-black text-[#223872]">{{ $candidates->count() }}</p>
            </div>
        </div>

        <section class="rounded-2xl border border-[#dce5f8] bg-white shadow-[0_4px_16px_rgba(34,56,114,0.06)]" aria-label="Daftar Kandidat">
            <div class="overflow-x-auto">
                @if ($candidates->isEmpty())
                    <p class="px-5 py-10 text-center text-sm text-[#64748b]">
                        Belum ada kandidat terdaftar.
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($candidates as $candidate)
                                <tr class="border-b border-[#edf2ff] transition last:border-0 hover:bg-[#F4F7FF]">
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
                                    <td class="px-4 py-3 font-mono text-[0.68rem] text-[#64748b]">{{ $candidate->user->nim }}</td>
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-[#333333]">{{ $candidate->user->prodi }}</p>
                                        <p class="text-[0.65rem] text-[#64748b]">{{ $candidate->user->kelas }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-[#EEF3FF] px-2.5 py-0.5 text-[0.65rem] font-black text-[#223872]">
                                            {{ strtoupper($candidate->candidate_type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-[#64748b]">{{ $candidate->first_choice_department?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-[#64748b]">{{ $candidate->second_choice_department?->name ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $schedule = $candidate->selectedInterviewSchedule?->schedule;
                                        @endphp
                                        @if ($schedule)
                                            <p class="font-bold text-[#333333]">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                                            <p class="text-[0.65rem] text-[#64748b]">{{ \Carbon\Carbon::parse($schedule->date)->locale('id')->translatedFormat('d M Y') }}</p>
                                        @else
                                            <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[0.65rem] font-black text-amber-700">Belum pilih</span>
                                        @endif
                                    </td>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </section>
    </div>
@endsection
