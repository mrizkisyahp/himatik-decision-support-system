@extends('interviewer.layout', ['title' => 'Dashboard Interviewer'])

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-[#111827]">Jadwal Interview</h1>
            <p class="text-sm text-[#64748B]">Daftar kandidat yang dijadwalkan dengan Anda.</p>
        </div>
    </div>
    
    <div class="rounded-2xl border border-[#D8E2F3] bg-white shadow-sm overflow-hidden p-8 text-center text-gray-500">
        {{-- Content schedules goes here later, for now just show a simple text --}}
        Fitur Daftar Jadwal (Schedules) sedang dalam pengembangan.
    </div>
@endsection