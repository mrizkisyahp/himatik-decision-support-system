@extends('interviewer.layout', ['title' => 'Jadwal Wawancara'])

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-[#223872]">Jadwal Wawancara</h2>
            <p class="text-sm text-[#64748B]">Lihat dan kelola ketersediaan jadwal wawancara untuk departemen Anda.</p>
        </div>
    </div>

    @if(empty($dates))
        <div class="rounded-2xl border border-[#D8E2F3] bg-white p-10 text-center shadow-sm">
            <h3 class="mb-2 text-lg font-bold text-[#223872]">Belum ada jadwal</h3>
            <p class="mb-6 text-sm text-[#64748B]">Jadwal wawancara belum dibuat oleh admin untuk departemen ini.</p>
        </div>
    @else
        <div class="mb-4 flex items-center justify-between rounded-xl bg-white p-4 border border-[#D8E2F3] shadow-sm">
            <div class="flex items-center gap-5 flex-wrap">
                <div class="flex items-center gap-2"><div class="h-4 w-4 rounded bg-[#FFFFFF] border border-[#D8E2F3]"></div> <span class="text-xs font-semibold text-[#64748B]">Tersedia</span></div>
                <div class="flex items-center gap-2"><div class="h-4 w-4 rounded bg-[#FEE2E2] border border-[#F87171]"></div> <span class="text-xs font-semibold text-[#64748B]">Blocked (Oleh Anda)</span></div>
                <div class="flex items-center gap-2"><div class="h-4 w-4 rounded bg-[#FEF3C7] border border-[#FBBF24]"></div> <span class="text-xs font-semibold text-[#64748B]">Booked (Belum Wawancara)</span></div>
                <div class="flex items-center gap-2"><div class="h-4 w-4 rounded bg-[#E0F2FE] border border-[#7DD3FC]"></div> <span class="text-xs font-semibold text-[#64748B]">Selesai Diwawancara</span></div>
            </div>
            <div class="text-xs text-[#64748b]">
                Klik jadwal yang "Tersedia" untuk melakukan block pada jadwal tersebut.
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
                                [$startTime, $endTime] = explode(' - ', $ts);
                                $timeKey = $ts;
                                $displayTime = \Carbon\Carbon::parse($startTime)->format('H:i') . ' - ' . \Carbon\Carbon::parse($endTime)->format('H:i');
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
                                                $isInterviewed = $isBooked && $schedule->booking->candidate->evaluations->where('department_id', $department->id)->isNotEmpty();
                                                
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

                                                $canToggle = !$isBooked && ($activeDepartmentId == $department->id);
                                            @endphp

                                            <div id="cell-{{ $schedule->id }}" 
                                                 class="h-full w-full min-h-[48px] rounded-md border p-2 transition-all {{ $canToggle ? 'cursor-pointer' : 'cursor-default' }} shadow-sm {{ $bgClass }} {{ $textClass }}"
                                                 @if($canToggle) onclick="toggleBlock({{ $schedule->id }})" @endif>
                                                
                                                @if($isInterviewed || $isBooked)
                                                    <div class="text-xs font-bold leading-tight" title="{{ $schedule->booking->candidate->user->name }}">
                                                        {{ $schedule->booking->candidate->user->name }}
                                                    </div>
                                                    @if($isInterviewed)
                                                        <div class="mt-1 text-[9px] font-black uppercase tracking-wider opacity-80">Selesai</div>
                                                    @endif
                                                @elseif($schedule->is_blocked)
                                                    <div class="text-xs font-bold leading-tight">Blocked</div>
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
            <a href="{{ route('interviewer.schedules', ['department_id' => $dept->id]) }}" 
               class="whitespace-nowrap rounded-t-lg px-5 py-2.5 text-xs font-bold transition-all border border-b-0 {{ $activeDepartmentId == $dept->id ? 'bg-white text-[#223872] border-[#D8E2F3] shadow-[0_-2px_4px_rgba(0,0,0,0.02)]' : 'bg-transparent border-transparent text-[#64748B] hover:bg-white/50 hover:text-[#4A90E2]' }}">
                {{ $dept->name }} {{ $department->id == $dept->id ? '(Dept. Anda)' : '' }}
            </a>
        @endforeach
    </div>

@endsection

@push('scripts')
<script>
function toggleBlock(scheduleId) {
    const cell = document.getElementById('cell-' + scheduleId);
    
    // Optimistic UI Update
    const isCurrentlyBlocked = cell.classList.contains('bg-[#FEE2E2]');

    if (isCurrentlyBlocked) {
        // Change to White
        cell.classList.remove('bg-[#FEE2E2]', 'border-[#F87171]', 'text-[#991B1B]');
        cell.classList.add('bg-white', 'border-[#D8E2F3]', 'text-[#64748B]', 'hover:border-[#4A90E2]');
        cell.innerHTML = '';
    } else {
        // Change to Red
        cell.classList.remove('bg-white', 'border-[#D8E2F3]', 'text-[#64748B]', 'hover:border-[#4A90E2]');
        cell.classList.add('bg-[#FEE2E2]', 'border-[#F87171]', 'text-[#991B1B]');
        cell.innerHTML = '<div class="text-xs font-bold leading-tight">Blocked</div>';
    }

    fetch(`/interviewer/schedules/${scheduleId}/toggle-block`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then(res => {
        if (res.status === 419) {
            alert('Sesi Anda telah habis. Halaman akan dimuat ulang.');
            window.location.reload();
            return Promise.reject('Session expired');
        }
        return res.json();
    }).then(data => {
        if (!data.success) {
            alert(data.message || 'Gagal menyimpan status');
            window.location.reload();
        }
    }).catch(err => {
        if (err !== 'Session expired') {
            console.error(err);
            alert('Terjadi kesalahan jaringan atau server.');
            window.location.reload();
        }
    });
}
</script>
@endpush
