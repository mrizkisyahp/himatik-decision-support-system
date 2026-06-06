@extends('admin.layout', ['title' => 'Sesi Interview', 'hideStubBadge' => true])

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-[#223872]">Matriks Penjadwalan</h2>
            <p class="text-sm text-[#64748B]">Kelola jadwal wawancara untuk {{ $departments->firstWhere('id', $activeDepartmentId)->name ?? 'Departemen' }}.</p>
        </div>
        <button onclick="openModal()" class="rounded-xl bg-[#223872] px-5 py-2 text-sm font-bold text-white shadow-lg shadow-[#223872]/20 transition hover:bg-[#122452]">
            + Generate Grid
        </button>
    </div>

    @if(empty($dates))
        <div class="rounded-2xl border border-[#D8E2F3] bg-white p-10 text-center shadow-sm">
            <h3 class="mb-2 text-lg font-bold text-[#223872]">Belum ada jadwal untuk departemen ini</h3>
            <p class="mb-6 text-sm text-[#64748B]">Klik tombol Generate Grid di atas untuk membuat matriks jadwal wawancara.</p>
            <button onclick="openModal()" class="rounded-xl bg-[#223872] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-[#122452] shadow-lg shadow-[#223872]/20">
                Generate Grid Jadwal
            </button>
        </div>
    @else
        <div class="mb-4 flex items-center justify-between rounded-xl bg-white p-4 border border-[#D8E2F3] shadow-sm">
            <div class="flex items-center gap-5 flex-wrap">
                <div class="flex items-center gap-2"><div class="h-4 w-4 rounded bg-[#FFFFFF] border border-[#D8E2F3]"></div> <span class="text-xs font-semibold text-[#64748B]">Tersedia</span></div>
                <div class="flex items-center gap-2"><div class="h-4 w-4 rounded bg-[#FEE2E2] border border-[#F87171]"></div> <span class="text-xs font-semibold text-[#64748B]">Blocked (Oleh Anda)</span></div>
                <div class="flex items-center gap-2"><div class="h-4 w-4 rounded bg-[#FEF3C7] border border-[#FBBF24]"></div> <span class="text-xs font-semibold text-[#64748B]">Booked (Belum Wawancara)</span></div>
                <div class="flex items-center gap-2"><div class="h-4 w-4 rounded bg-[#E0F2FE] border border-[#7DD3FC]"></div> <span class="text-xs font-semibold text-[#64748B]">Selesai Diwawancara</span></div>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.schedules.clear') }}" method="POST" onsubmit="return confirm('Hapus semua slot kosong di departemen ini?');">
                    @csrf @method('DELETE')
                    <input type="hidden" name="department_id" value="{{ $activeDepartmentId }}">
                    <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-bold text-red-600 transition hover:bg-red-50 hover:text-red-800">Bersihkan Dept. Ini</button>
                </form>
                <form action="{{ route('admin.schedules.clear') }}" method="POST" onsubmit="return confirm('AWAS: Hapus SEMUA slot kosong di SELURUH departemen?');">
                    @csrf @method('DELETE')
                    <input type="hidden" name="all_departments" value="1">
                    <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-bold text-red-600 shadow-sm transition hover:bg-red-600 hover:text-white">Bersihkan Semua Dept.</button>
                </form>
            </div>
        </div>

        {{-- Spreadsheet Grid --}}
        <div class="rounded-t-2xl border border-[#D8E2F3] bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto max-h-[60vh]">
                <table class="w-full text-left text-sm text-[#333333] border-collapse relative">
                    <thead class="bg-[#F4F7FF] sticky top-0 z-20">
                        <tr>
                            <th class="border-b border-r border-[#D8E2F3] px-4 py-3 font-bold text-[#223872] whitespace-nowrap sticky left-0 bg-[#F4F7FF] z-30 w-24 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">WAKTU</th>
                            @foreach($dates as $date)
                                <th class="border-b border-r border-[#D8E2F3] px-4 py-3 font-bold text-[#223872] text-center min-w-[200px] bg-[#F4F7FF]">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d M') }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeSlots as $ts)
                            @php
                                $timeKey = $ts['start_time'] . '|' . $ts['end_time'];
                                $displayTime = \Carbon\Carbon::parse($ts['start_time'])->format('H:i') . ' - ' . \Carbon\Carbon::parse($ts['end_time'])->format('H:i');
                            @endphp
                            <tr class="border-b border-[#D8E2F3] last:border-0 hover:bg-[#F4F7FF]/50 transition-colors">
                                <td class="border-r border-[#D8E2F3] px-4 py-3 font-semibold text-[#64748B] whitespace-nowrap sticky left-0 bg-white z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                    {{ $displayTime }}
                                </td>
                                @foreach($dates as $date)
                                    @php
                                        $dateKey = \Carbon\Carbon::parse($date)->format('Y-m-d');
                                        $schedule = $schedules[$dateKey][$timeKey] ?? null;
                                    @endphp
                                    <td class="border-r border-[#D8E2F3] p-1.5 align-top">
                                        @if($schedule)
                                            @php
                                                $isBooked = $schedule->booking !== null;
                                                $isInterviewed = $isBooked && $schedule->booking->candidate->evaluations->isNotEmpty();
                                                
                                                if($isInterviewed) {
                                                    $bgClass = 'bg-[#E0F2FE] border-[#7DD3FC]';
                                                    $textClass = 'text-[#0284C7]';
                                                } elseif($isBooked) {
                                                    $bgClass = 'bg-[#FEF3C7] border-[#FBBF24]';
                                                    $textClass = 'text-[#92400E]';
                                                } elseif($schedule->is_blocked) {
                                                    $bgClass = 'bg-[#FEE2E2] border-[#F87171]';
                                                    $textClass = 'text-[#991B1B]';
                                                } else {
                                                    $bgClass = 'bg-white border-[#D8E2F3] hover:border-[#4A90E2]';
                                                    $textClass = 'text-[#64748B]';
                                                }
                                            @endphp

                                            <div id="cell-{{ $schedule->id }}" 
                                                 class="h-full w-full min-h-[48px] rounded-md border p-2 transition-all cursor-pointer shadow-sm {{ $bgClass }} {{ $textClass }}"
                                                 @if(!$isBooked) onclick="toggleBlock({{ $schedule->id }})" @endif>
                                                
                                                @if($isInterviewed || $isBooked)
                                                    <div class="text-xs font-bold leading-tight" title="{{ $schedule->booking->candidate->user->name }}">
                                                        {{ $schedule->booking->candidate->user->name }}
                                                    </div>
                                                    @if($isInterviewed)
                                                        <div class="mt-1 text-[9px] font-black uppercase tracking-wider opacity-80">Selesai</div>
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            <div class="h-full w-full min-h-[48px] rounded-md bg-[#F8FAFC] border border-dashed border-[#E2E8F0]"></div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Tabs Department (Moved to Bottom) --}}
    <div class="flex gap-1 overflow-x-auto border-t-0 border border-[#D8E2F3] bg-[#F4F7FF] px-2 pt-2 rounded-b-2xl shadow-sm">
        @foreach($departments as $dept)
            <a href="{{ route('admin.schedules', ['department_id' => $dept->id]) }}" 
               class="whitespace-nowrap rounded-t-lg px-5 py-2.5 text-xs font-bold transition-all border border-b-0 {{ $activeDepartmentId == $dept->id ? 'bg-white text-[#223872] border-[#D8E2F3] shadow-[0_-2px_4px_rgba(0,0,0,0.02)]' : 'bg-transparent border-transparent text-[#64748B] hover:bg-white/50 hover:text-[#4A90E2]' }}">
                {{ $dept->name }}
            </a>
        @endforeach
    </div>

    {{-- MODAL GENERATE --}}
    <div id="generate-schedule-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#122452]/40 p-4 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="border-b border-[#D8E2F3] px-6 py-5 flex justify-between items-center bg-[#F4F7FF]">
                <h3 class="text-lg font-black text-[#223872]">Generate Matriks Jadwal</h3>
                <button type="button" onclick="closeModal()" class="text-[#64748B] hover:text-[#223872]">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form action="{{ route('admin.schedules.generate') }}" method="POST">
                @csrf
                <div class="px-6 py-5 space-y-5">
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-[#333333]">Pilih Departemen/Biro</label>
                        <select name="department_id" id="department_select" class="w-full rounded-xl border border-[#D8E2F3] px-4 py-2.5 focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $activeDepartmentId == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <label class="mt-2 flex items-center gap-2 text-sm text-[#333333]">
                            <input type="checkbox" name="all_departments" value="1" class="rounded border-[#D8E2F3] text-[#223872] focus:ring-[#223872]" onchange="document.getElementById('department_select').disabled = this.checked">
                            <span>Terapkan ke <strong>Semua Departemen/Biro</strong> sekaligus</span>
                        </label>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-[#333333]">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="w-full rounded-xl border border-[#D8E2F3] px-4 py-2.5 focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]" required>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-[#333333]">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="w-full rounded-xl border border-[#D8E2F3] px-4 py-2.5 focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]" required>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-[#333333]">Daftar Jam Wawancara</label>
                        <textarea name="time_slots" rows="3" placeholder="09:00-09:40, 09:50-10:30, 10:40-11:20" class="w-full rounded-xl border border-[#D8E2F3] px-4 py-2.5 focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]" required>09:00-09:40, 09:50-10:30, 10:40-11:20, 12:50-13:30, 13:40-14:20, 14:30-15:10, 15:20-16:00</textarea>
                        <p class="mt-1.5 text-xs text-[#64748B]">Pisahkan dengan koma. Gunakan format HH:MM-HH:MM.</p>
                    </div>
                </div>
                <div class="border-t border-[#D8E2F3] bg-[#F4F7FF] px-6 py-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-bold text-[#64748B] hover:text-[#333333]">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#223872] px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#223872]/20 hover:bg-[#122452]">Generate Data</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
function openModal() {
    const modal = document.getElementById('generate-schedule-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('generate-schedule-modal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function toggleBlock(scheduleId) {
    const cell = document.getElementById('cell-' + scheduleId);
    
    // Optimistic UI Update
    const isCurrentlyBlocked = cell.classList.contains('bg-[#FEE2E2]');
    const statusText = cell.querySelector('.status-text');

    if (isCurrentlyBlocked) {
        // Change to White
        cell.classList.remove('bg-[#FEE2E2]', 'border-[#F87171]', 'text-[#991B1B]');
        cell.classList.add('bg-white', 'border-[#D8E2F3]', 'text-[#64748B]', 'hover:border-[#4A90E2]');
        if(statusText) statusText.innerText = 'Tersedia';
    } else {
        // Change to Red
        cell.classList.remove('bg-white', 'border-[#D8E2F3]', 'text-[#64748B]', 'hover:border-[#4A90E2]');
        cell.classList.add('bg-[#FEE2E2]', 'border-[#F87171]', 'text-[#991B1B]');
        if(statusText) statusText.innerText = 'Tidak Tersedia';
    }

    // Call API
    fetch(`/admin/schedules/${scheduleId}/toggle-block`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then(res => res.json())
      .then(data => {
          if(!data.success) {
              alert('Gagal menyimpan status');
              window.location.reload();
          }
      }).catch(err => {
          console.error(err);
          alert('Terjadi kesalahan jaringan');
          window.location.reload();
      });
}

// Close modal on click outside
const modal = document.getElementById('generate-schedule-modal');
modal.addEventListener('click', (e) => {
    if(e.target === modal) closeModal();
});
</script>
@endpush