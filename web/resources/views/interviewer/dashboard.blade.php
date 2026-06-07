@extends('interviewer.layout', ['title' => 'Dashboard Interviewer'])

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="relative overflow-hidden rounded-[2rem] bg-white p-8 border border-[#E2E8F0] shadow-sm">
        <!-- Subtle mesh gradient background -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60"></div>
        <div class="absolute bottom-0 right-40 -mb-20 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div>
                <p class="text-xs font-bold text-blue-600 mb-2 uppercase tracking-widest">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <h1 class="text-3xl md:text-4xl font-black text-[#0F172A] tracking-tight">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                <p class="mt-3 text-[#64748B] max-w-xl text-sm md:text-base font-medium leading-relaxed">Selamat datang di dashboard interviewer. Mari evaluasi kandidat terbaik untuk masa depan HIMATIK PNJ.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl p-5 min-w-[140px] shadow-sm">
                    <p class="text-[0.65rem] font-bold text-[#64748B] uppercase tracking-widest mb-1.5">Total Pendaftar</p>
                    <p class="text-3xl font-black text-[#0F172A]">{{ $department->candidateChoices()->count() ?? 0 }}</p>
                </div>
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 min-w-[140px] shadow-sm">
                    <p class="text-[0.65rem] font-bold text-blue-600 uppercase tracking-widest mb-1.5">Jadwal Hari Ini</p>
                    <p class="text-3xl font-black text-blue-700">{{ count($todaySchedules) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Left -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <a href="{{ route('interviewer.schedules') }}" class="group relative overflow-hidden rounded-[1.5rem] border border-[#E2E8F0] bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-blue-300 hover:-translate-y-1">
                    <div class="flex items-center gap-5">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#F1F5F9] text-[#64748B] group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0F172A] text-lg">Matriks Jadwal</h3>
                            <p class="text-xs font-medium text-[#64748B] mt-0.5">Atur ketersediaan waktu wawancara</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('interviewer.profile-matching') }}" class="group relative overflow-hidden rounded-[1.5rem] border border-[#E2E8F0] bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-indigo-300 hover:-translate-y-1">
                    <div class="flex items-center gap-5">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#F1F5F9] text-[#64748B] group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0F172A] text-lg">Mulai Penilaian</h3>
                            <p class="text-xs font-medium text-[#64748B] mt-0.5">Isi form evaluasi kandidat</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Top Candidates -->
            <div class="rounded-[2rem] bg-white border border-[#E2E8F0] shadow-sm overflow-hidden">
                <div class="px-7 py-6 border-b border-[#F1F5F9] flex items-center justify-between">
                    <h3 class="font-black text-[#0F172A] text-lg">Top 5 Kandidat</h3>
                    <a href="{{ route('interviewer.profile-matching') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">Lihat Semua</a>
                </div>
                
                <div class="p-7">
                    <div class="space-y-3">
                        @forelse($topCandidates as $idx => $cand)
                            <div class="flex items-center justify-between group p-3.5 rounded-2xl hover:bg-[#F8FAFC] transition-colors border border-transparent hover:border-[#E2E8F0]">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl font-black text-sm
                                        {{ $idx === 0 ? 'bg-[#FFFBEB] text-[#D97706] ring-1 ring-[#FEF3C7]' : 
                                          ($idx === 1 ? 'bg-[#F1F5F9] text-[#475569] ring-1 ring-[#E2E8F0]' : 
                                          ($idx === 2 ? 'bg-[#FFEDD5] text-[#C2410C] ring-1 ring-[#FFEDD5]' : 
                                          'bg-[#F8FAFC] text-[#94A3B8]')) }}">
                                        #{{ $idx + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#0F172A]">{{ $cand->user->name }}</p>
                                        <p class="text-xs font-medium text-[#64748B] mt-0.5">{{ $cand->nim }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-lg font-black text-[#0F172A]">{{ number_format($cand->total_score ?? 0, 2, ',', '.') }}</span>
                                    <span class="text-[0.65rem] font-bold text-[#94A3B8] uppercase tracking-wider mt-0.5">Skor Total</span>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <div class="h-16 w-16 bg-[#F8FAFC] rounded-2xl border border-[#E2E8F0] flex items-center justify-center mb-4 shadow-sm">
                                    <svg class="h-7 w-7 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                </div>
                                <h4 class="text-sm font-black text-[#0F172A]">Belum ada penilaian</h4>
                                <p class="text-xs font-medium text-[#64748B] mt-1.5 max-w-sm">Anda belum memberikan evaluasi kepada kandidat manapun.</p>
                                <a href="{{ route('interviewer.profile-matching') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-50 px-4 py-2 text-xs font-bold text-blue-600 transition hover:bg-blue-100">
                                    Mulai Menilai
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar (Timeline) -->
        <div class="lg:col-span-1">
            <div class="rounded-[2rem] bg-white border border-[#E2E8F0] shadow-sm h-full flex flex-col">
                <div class="px-7 py-6 border-b border-[#F1F5F9]">
                    <div class="flex items-center justify-between">
                        <h3 class="font-black text-[#0F172A] text-lg">Timeline Hari Ini</h3>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[0.65rem] font-bold bg-[#F1F5F9] text-[#475569] uppercase tracking-wider">
                            {{ count($todaySchedules) }} Sesi
                        </span>
                    </div>
                </div>
                
                <div class="flex-1 p-7">
                    @if(count($todaySchedules) > 0)
                        <div class="relative border-l-2 border-[#F1F5F9] ml-3 space-y-8 py-2">
                            @foreach($todaySchedules as $index => $sch)
                                <div class="relative pl-6">
                                    <!-- Timeline dot -->
                                    <div class="absolute left-[-5px] top-1.5 h-2.5 w-2.5 rounded-full bg-blue-500 ring-4 ring-white"></div>
                                    
                                    <div class="group">
                                        <p class="text-[0.65rem] font-bold text-blue-600 mb-1.5 uppercase tracking-widest">{{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}</p>
                                        <div class="bg-white border border-[#E2E8F0] rounded-2xl p-4 shadow-sm hover:shadow-md transition-all group-hover:border-blue-300 hover:-translate-y-0.5">
                                            <h4 class="font-bold text-[#0F172A] mb-1.5 line-clamp-1" title="{{ $sch->booking->candidate->user->name ?? 'Kandidat' }}">{{ $sch->booking->candidate->user->name ?? 'Kandidat' }}</h4>
                                            <div class="flex items-center gap-2 text-xs font-medium text-[#64748B]">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                Ruang {{ $sch->location ?? 'TBD' }}
                                            </div>
                                            <div class="mt-4 pt-4 border-t border-[#F8FAFC] flex items-center justify-between">
                                                <span class="text-[0.65rem] font-bold text-[#94A3B8] uppercase">Sesi {{ $index + 1 }}</span>
                                                <a href="{{ route('interviewer.grade.view', [$sch->booking->candidate_id, $department->id]) }}" class="inline-flex items-center gap-1 text-[0.65rem] font-bold text-white bg-[#0F172A] px-3 py-1.5 rounded-lg hover:bg-blue-600 transition-colors uppercase tracking-wider">
                                                    Beri Nilai
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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