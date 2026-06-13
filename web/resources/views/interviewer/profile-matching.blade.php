@extends('interviewer.layout', ['title' => 'Profile Matching (DSS)'])

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-[#111827]">Profile Matching</h1>
            <p class="text-sm text-[#64748B]">Tinjau skor wawancara dan lihat peringkat kandidat per departemen.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif
    @if($error)
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800">
            ⚠ {{ $error }}
        </div>
    @endif

    {{-- Step 1: Select Department (Locked for Interviewer) --}}
    <div class="rounded-2xl border border-[#D8E2F3] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#223872] text-sm font-black text-white">1</div>
                <div>
                    <h2 class="font-bold text-[#111827]">Departemen Anda</h2>
                    <p class="text-xs text-[#64748B]">Anda sedang menilai kandidat untuk {{ $selectedDepartment->name }}.</p>
                </div>
            </div>
            
            <div class="relative w-full sm:w-72">
                <div class="w-full appearance-none rounded-xl border border-gray-300 bg-gray-50 py-3 px-4 text-sm font-bold text-gray-700 outline-none text-center">
                    {{ $selectedDepartment->name }}
                </div>
            </div>
        </div>
    </div>

    @if($selectedDepartment && $criteria->isNotEmpty())

        {{-- Step 2: View & Edit Scores --}}
        <div class="rounded-2xl border border-[#D8E2F3] bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-[#D8E2F3] bg-[#F4F7FF] px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#223872] text-sm font-black text-white">2</div>
                    <div>
                        <h2 class="font-bold text-[#111827]">Input Skor Evaluasi</h2>
                        <div class="mt-1 text-xs text-[#64748B]">
                            Bobot: Personal ({{ $selectedDepartment->personal_aspect_weight }}%), Organizational ({{ $selectedDepartment->organizational_aspect_weight }}%), CF ({{ $selectedDepartment->core_factor_weight }}%), SF ({{ $selectedDepartment->secondary_factor_weight }}%)
                        </div>
                    </div>
                </div>
                <a href="{{ route('interviewer.criteria') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#4A90E2] px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-[#357ABD]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Kelola Kriteria
                </a>
            </div>

            @if($candidates->isEmpty())
                <div class="p-6 text-center text-sm text-[#64748B]">Belum ada kandidat di departemen ini.</div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($candidates as $candidate)
                        @php
                            $cScores = $existingScores[$candidate->id] ?? [];
                            $filled = count($cScores);
                            $total = $criteria->count();
                            $allFilled = $filled === $total;
                        @endphp
                        <details class="group">
                            <summary class="flex cursor-pointer items-center justify-between p-6 transition hover:bg-gray-50 list-none [&::-webkit-details-marker]:hidden">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-700">
                                        {{ strtoupper(substr($candidate->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div class="font-bold text-[#111827]">{{ $candidate->user->name }}</div>
                                            <button type="button" onclick="event.preventDefault(); document.getElementById('detailModal-{{ $candidate->id }}').showModal()"
                                                    class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#EEF3FF] text-[#223872] transition-colors hover:bg-[#dce5f8] hover:text-[#1b2f60]"
                                                    title="Lihat Detail Kandidat">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        </div>
                                        <div class="text-xs text-[#64748B]">{{ $candidate->user->nim }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="text-right hidden sm:block">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold {{ $allFilled ? 'text-emerald-600' : 'text-gray-400' }}">{{ $filled }}/{{ $total }}</span>
                                            <div class="h-1.5 w-24 overflow-hidden rounded-full bg-gray-200">
                                                <div class="h-full rounded-full transition-all duration-500 {{ $allFilled ? 'bg-emerald-500' : 'bg-indigo-500' }}" style="width: {{ $total > 0 ? ($filled / $total) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                        <div class="mt-0.5 text-[10px] text-gray-500">{{ $allFilled ? 'Lengkap' : 'Belum lengkap' }}</div>
                                    </div>
                                    <svg class="h-5 w-5 text-gray-400 transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </summary>
                            
                            <div class="border-t border-gray-100 bg-gray-50/50 p-6">
                                <form action="{{ route('interviewer.grade.post', [$candidate->id, $selectedDepartment->id]) }}" method="POST" id="score-form-{{ $candidate->id }}">
                                    @csrf
                                    <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                        @foreach($criteria as $c)
                                            @php 
                                                $val = $cScores[$c->id]['score'] ?? null; 
                                            @endphp
                                            <div class="flex flex-col justify-between rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:border-[#4A90E2]">
                                                <div>
                                                    <div class="flex items-start justify-between gap-2">
                                                        <h4 class="text-xs font-semibold text-gray-700 line-clamp-2" title="{{ $c->name }}">{{ $c->name }}</h4>
                                                        <div class="flex shrink-0 gap-1">
                                                            <span class="rounded px-1.5 py-0.5 text-[9px] font-bold {{ $c->aspect === 'personal' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                                                {{ $c->aspect === 'personal' ? 'P' : 'O' }}
                                                            </span>
                                                            <span class="rounded px-1.5 py-0.5 text-[9px] font-bold {{ $c->type === 'core' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700' }}">
                                                                {{ $c->type === 'core' ? 'CF' : 'SF' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 flex items-center gap-2">
                                                    <select name="scores[{{ $c->id }}]" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-1.5 text-center text-sm font-bold text-gray-700 outline-none transition focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20 {{ $val ? 'border-indigo-300 bg-indigo-50 text-indigo-800' : '' }}">
                                                        <option value="">—</option>
                                                        @for($s = 1; $s <= 5; $s++)
                                                            <option value="{{ $s }}" {{ $val == $s ? 'selected' : '' }}>{{ $s }}</option>
                                                        @endfor
                                                    </select>
                                                    <span class="text-xs text-gray-400">/{{ $c->target_score }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @php
                                        $globalNote = '';
                                        foreach($cScores as $eval) {
                                            if (!empty($eval['notes'])) {
                                                $globalNote = $eval['notes'];
                                                break;
                                            }
                                        }
                                    @endphp
                                    <div class="mt-6">
                                        <label class="mb-2 block text-sm font-bold text-[#223872]">Catatan Evaluasi (Opsional)</label>
                                        <textarea name="global_notes" rows="3" placeholder="Masukkan catatan keseluruhan mengenai kandidat ini..." class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">{{ $globalNote }}</textarea>
                                    </div>
                                    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Pilih nilai 1-5. Kosongkan jika belum dinilai.
                                        </div>
                                        <div class="flex flex-wrap gap-3">
                                            <a href="{{ route('interviewer.profile-matching.calculation', $candidate->id) }}"
                                               class="rounded-xl border border-[#D8E2F3] bg-white px-4 py-2 text-sm font-bold text-[#223872] transition hover:border-[#4A90E2] hover:text-[#1b2f60]">
                                                Lihat Detail Hitung
                                            </a>
                                            @if($filled > 0)
                                                <button type="button" onclick="if(confirm('Hapus semua skor untuk kandidat ini?')) document.getElementById('reset-form-{{ $candidate->id }}').submit();" class="rounded-xl px-4 py-2 text-sm font-bold text-red-600 transition hover:bg-red-50">Reset Skor</button>
                                            @endif
                                            <button type="submit" class="rounded-xl bg-[#223872] px-6 py-2 text-sm font-bold text-white transition hover:bg-[#122452]">Simpan Skor</button>
                                        </div>
                                    </div>
                                </form>
                                @if($filled > 0)
                                    <form action="{{ route('interviewer.profile-matching.reset', [$candidate->id, $selectedDepartment->id]) }}" method="POST" id="reset-form-{{ $candidate->id }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </details>
                        <x-candidate-detail-modal :candidate="$candidate" />
                    @endforeach
                </div>
                
                @if($candidates->hasPages())
                    <div class="border-t border-gray-100 px-6 py-4">
                        {{ $candidates->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- Step 3: Ranking Board --}}
        <div class="rounded-2xl border border-[#D8E2F3] bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-[#D8E2F3] bg-[#F4F7FF] px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#223872] text-sm font-black text-white">3</div>
                    <div>
                        <h2 class="font-bold text-[#111827]">Papan Peringkat (DSS)</h2>
                        <p class="text-xs text-[#64748B]">Kalkulasi otomatis berdasarkan skor interviewer.</p>
                    </div>
                </div>
                <div class="rounded-full bg-[#EEF4FF] px-3 py-1 text-xs font-bold text-[#223872]">{{ count($rankings) }} Kandidat</div>
            </div>

            @if(empty($rankings))
                <div class="flex flex-col items-center justify-center p-12 text-center">
                    <div class="mb-4 rounded-full bg-gray-100 p-4 text-gray-400">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-[#111827]">Belum Ada Perhitungan</h3>
                    <p class="mt-1 text-sm text-[#64748B]">Skor kandidat masih kosong atau belum lengkap.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-[#64748B]">
                            <tr>
                                <th class="px-6 py-4 font-bold text-center w-20">Rank</th>
                                <th class="px-6 py-4 font-bold">Kandidat</th>
                                <th class="px-6 py-4 font-bold text-center">Personal Score</th>
                                <th class="px-6 py-4 font-bold text-center">Org. Score</th>
                                <th class="px-6 py-4 font-bold text-right text-[#223872]">Total Skor DSS</th>
                                <th class="px-6 py-4 font-bold text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#D8E2F3]">
                            @foreach($rankings as $idx => $r)
                                @php
                                    $rank = $idx + 1;
                                    $isTop3 = $rank <= 3;
                                @endphp
                                <tr class="transition hover:bg-gray-50 {{ $rank === 1 ? 'bg-amber-50/50' : '' }}">
                                    <td class="px-6 py-4 text-center">
                                        @if($rank === 1)
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-amber-600 font-black text-white shadow-md">1</span>
                                        @elseif($rank === 2)
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-slate-300 to-slate-500 font-black text-white shadow-md">2</span>
                                        @elseif($rank === 3)
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-orange-600 to-orange-800 font-black text-white shadow-md">3</span>
                                        @else
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 font-bold text-gray-500">{{ $rank }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-[#111827] {{ $rank === 1 ? 'text-amber-700 text-base' : '' }}">{{ $r['candidate']->user->name }}</div>
                                        <div class="text-xs text-[#64748B]">{{ $r['candidate']->user->nim }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-mono font-medium text-gray-600">{{ number_format($r['personal_score'] ?? 0, 4) }}</td>
                                    <td class="px-6 py-4 text-center font-mono font-medium text-gray-600">{{ number_format($r['organizational_score'] ?? 0, 4) }}</td>
                                    <td class="px-6 py-4 text-right font-mono text-lg font-black {{ $rank === 1 ? 'text-amber-600' : 'text-[#223872]' }}">
                                        {{ number_format($r['total_score'], 4) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php $ann = $r['candidate']->announcement; @endphp
                                        <div class="flex flex-col items-center gap-2">
                                        <a href="{{ route('interviewer.profile-matching.calculation', $r['candidate']->id) }}"
                                           class="rounded-lg border border-[#D8E2F3] bg-white px-3 py-1.5 text-[10px] font-bold text-[#223872] transition hover:border-[#4A90E2] hover:text-[#1b2f60]">
                                            Detail Hitung
                                        </a>
                                        @if($ann)
                                            @if($ann->status === 'accepted')
                                                <div class="flex flex-col items-center gap-2">
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-[10px] font-bold text-emerald-700">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                        Lulus ({{ $ann->assignedDepartment?->name ?? '?' }})
                                                    </span>
                                                    <form action="{{ route('interviewer.decide', $r['candidate']->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" onclick="return confirm('Ubah status menjadi Ditolak?')" class="rounded-lg bg-gray-100 px-3 py-1.5 text-[10px] font-bold text-[#223872] transition hover:bg-gray-200">Ubah Status</button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="flex flex-col items-center gap-2">
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-[10px] font-bold text-red-700">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                        Ditolak
                                                    </span>
                                                    <form action="{{ route('interviewer.decide', $r['candidate']->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="accepted">
                                                        <input type="hidden" name="assigned_department_id" value="{{ $selectedDepartment->id }}">
                                                        <button type="submit" onclick="return confirm('Ubah status menjadi Lulus untuk {{ $selectedDepartment->name }}?')" class="rounded-lg bg-gray-100 px-3 py-1.5 text-[10px] font-bold text-[#223872] transition hover:bg-gray-200">Ubah Status</button>
                                                    </form>
                                                </div>
                                            @endif
                                        @else
                                            <div class="flex items-center justify-center gap-2">
                                                <form action="{{ route('interviewer.decide', $r['candidate']->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="accepted">
                                                    <input type="hidden" name="assigned_department_id" value="{{ $selectedDepartment->id }}">
                                                    <button type="submit" onclick="return confirm('Terima kandidat ini untuk {{ $selectedDepartment->name }}?')" class="rounded-lg border border-emerald-200 bg-emerald-50 p-2 text-emerald-600 transition hover:bg-emerald-500 hover:text-white" title="Terima (Lulus)">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('interviewer.decide', $r['candidate']->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" onclick="return confirm('Tolak kandidat ini?')" class="rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 transition hover:bg-red-500 hover:text-white" title="Tolak">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
