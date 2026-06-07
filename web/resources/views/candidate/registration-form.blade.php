@extends('candidate.layout', ['title' => 'Formulir Pendaftaran'])

@section('content')
<div class="mx-auto max-w-3xl space-y-8">
    <div class="mb-6">
        <a href="{{ route('candidate.interview.detail') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#223872] hover:text-[#1b2f60] transition group">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <div>
        <h1 class="text-2xl md:text-3xl font-black tracking-tight text-[#0F172A]">Formulir Pendaftaran</h1>
        <p class="text-sm font-medium text-[#64748B] mt-2 mb-8">Ingin mengubah formulir? <a href="#" class="text-[#223872] hover:underline">Kontak</a></p>

        <!-- Biro Pilihan -->
        <section class="mb-6">
            <h2 class="text-sm font-medium text-[#0F172A] mb-2">Departemen/Biro Pilihan</h2>
            <div class="rounded-xl border border-[#dce5f8] bg-[#F4F7FF] divide-y divide-[#dce5f8] overflow-hidden">
                <div class="flex justify-between items-center px-4 py-3 text-sm">
                    <span class="font-medium text-[#333333]">{{ $candidate->first_choice_department?->name ?? 'Belum memilih' }}</span>
                    <span class="font-bold text-[#64748B]">1</span>
                </div>
                <div class="flex justify-between items-center px-4 py-3 text-sm">
                    <span class="font-medium text-[#333333]">{{ $candidate->second_choice_department?->name ?? 'Belum memilih' }}</span>
                    <span class="font-bold text-[#64748B]">2</span>
                </div>
            </div>
        </section>

        <!-- Alasan -->
        <section class="mb-6">
            <h2 class="text-sm font-medium text-[#0F172A] mb-2">Alasan Memilih Biro atau Departemen</h2>
            <div class="rounded-xl border border-[#dce5f8] bg-[#F4F7FF] px-4 py-3 text-sm font-medium text-[#333333]">
                {{ $candidate->department_choice_reason ?: 'Belum diisi' }}
            </div>
        </section>

        <!-- Kekurangan -->
        <section class="mb-6">
            <h2 class="text-sm font-medium text-[#0F172A] mb-2">Deskripsikan Kekurangan Kamu</h2>
            <div class="rounded-xl border border-[#dce5f8] bg-[#F4F7FF] px-4 py-3 text-sm font-medium text-[#333333]">
                {{ $candidate->weakness_description ?: 'Belum diisi' }}
            </div>
        </section>

        <!-- Langkah Konkret -->
        <section class="mb-8">
            <h2 class="text-sm font-medium text-[#0F172A] mb-2">Langkah Konkret Apa yang Kamu Ambil Jika Terpilih</h2>
            <div class="rounded-xl border border-[#dce5f8] bg-[#F4F7FF] px-4 py-3 text-sm font-medium text-[#333333]">
                {{ $candidate->contribution_plan ?: 'Belum diisi' }}
            </div>
        </section>

        <!-- Pendidikan Informal -->
        <section class="mb-6">
            <h2 class="text-sm font-medium text-[#0F172A] mb-2">Riwayat Pendidikan Informal</h2>
            <div class="rounded-xl border border-[#dce5f8] bg-[#F4F7FF] divide-y divide-[#dce5f8] overflow-hidden">
                @php $informalEdu = $candidate->educations->where('education_type', 'informal'); @endphp
                @if($informalEdu->isEmpty())
                    <div class="px-4 py-6 text-center text-sm font-medium text-[#94a3b8]">
                        •••<br>Belum Ada
                    </div>
                @else
                    @foreach($informalEdu as $edu)
                        <div class="px-4 py-3 text-sm">
                            <p class="font-bold text-[#333333]">{{ $edu->school_name }}</p>
                            <p class="text-xs text-[#64748B] mt-1">Tahun {{ $edu->start_year }} s.d. {{ $edu->end_year ?: 'Sekarang' }}, di {{ $edu->city }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        <!-- Organisasi -->
        <section class="mb-6">
            <h2 class="text-sm font-medium text-[#0F172A] mb-2">Pengalaman Organisasi Luar Kampus</h2>
            <div class="rounded-xl border border-[#dce5f8] bg-[#F4F7FF] divide-y divide-[#dce5f8] overflow-hidden">
                @if($candidate->organizations->isEmpty())
                    <div class="px-4 py-6 text-center text-sm font-medium text-[#94a3b8]">
                        •••<br>Belum Ada
                    </div>
                @else
                    @foreach($candidate->organizations as $org)
                        <div class="px-4 py-3 text-sm">
                            <p class="font-bold text-[#333333]">{{ $org->organization_name }}</p>
                            <p class="text-xs text-[#64748B] mt-1">Tahun {{ $org->start_year }}, di {{ $org->place_or_institution }}. Sebagai {{ $org->position }}.</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        <!-- Kepanitiaan -->
        <section class="mb-6">
            <h2 class="text-sm font-medium text-[#0F172A] mb-2">Pengalaman Kepanitiaan Dalam/Luar Kampus</h2>
            <div class="rounded-xl border border-[#dce5f8] bg-[#F4F7FF] divide-y divide-[#dce5f8] overflow-hidden">
                @if($candidate->committees->isEmpty())
                    <div class="px-4 py-6 text-center text-sm font-medium text-[#94a3b8]">
                        •••<br>Belum Ada
                    </div>
                @else
                    @foreach($candidate->committees as $com)
                        <div class="px-4 py-3 text-sm">
                            <p class="font-bold text-[#333333]">{{ $com->committee_name }}</p>
                            <p class="text-xs text-[#64748B] mt-1">Tahun {{ $com->start_year }}, oleh {{ $com->organizer }}. Sebagai {{ $com->position }}.</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        <!-- Pendidikan Formal -->
        <section class="mb-8">
            <h2 class="text-sm font-medium text-[#0F172A] mb-2">Riwayat Pendidikan Formal</h2>
            <div class="rounded-xl border border-[#dce5f8] bg-[#F4F7FF] divide-y divide-[#dce5f8] overflow-hidden">
                @php $formalEdu = $candidate->educations->where('education_type', 'formal'); @endphp
                @if($formalEdu->isEmpty())
                    <div class="px-4 py-6 text-center text-sm font-medium text-[#94a3b8]">
                        •••<br>Belum Ada
                    </div>
                @else
                    @foreach($formalEdu as $edu)
                        <div class="px-4 py-3 text-sm">
                            <p class="font-bold text-[#333333]">{{ $edu->school_name }}</p>
                            <p class="text-xs text-[#64748B] mt-1">Tahun {{ $edu->start_year }} s.d. {{ $edu->end_year ?: 'Sekarang' }}, di {{ $edu->city }}. {{ $edu->major ? 'Jurusan ' . $edu->major : '' }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        <!-- Soft Skill -->
        <section class="mb-6">
            <h2 class="text-sm font-medium text-[#0F172A] mb-2">Kemampuan Non-Teknis (Soft Skill)</h2>
            <div class="rounded-xl border border-[#dce5f8] bg-[#F4F7FF] divide-y divide-[#dce5f8] overflow-hidden">
                @php $softSkills = $candidate->skills->where('skill_type', 'soft'); @endphp
                @if($softSkills->isEmpty())
                    <div class="px-4 py-6 text-center text-sm font-medium text-[#94a3b8]">
                        •••<br>Belum Ada
                    </div>
                @else
                    @foreach($softSkills as $skill)
                        <div class="px-4 py-3 text-sm">
                            <p class="font-bold text-[#333333]">{{ $skill->skill_name }}</p>
                            <p class="text-xs text-[#64748B] mt-1">{{ ucfirst($skill->proficiency) }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        <!-- Hard Skill -->
        <section class="mb-6">
            <h2 class="text-sm font-medium text-[#0F172A] mb-2">Kemampuan Teknis (Hard Skill)</h2>
            <div class="rounded-xl border border-[#dce5f8] bg-[#F4F7FF] divide-y divide-[#dce5f8] overflow-hidden">
                @php $hardSkills = $candidate->skills->where('skill_type', 'hard'); @endphp
                @if($hardSkills->isEmpty())
                    <div class="px-4 py-6 text-center text-sm font-medium text-[#94a3b8]">
                        •••<br>Belum Ada
                    </div>
                @else
                    @foreach($hardSkills as $skill)
                        <div class="px-4 py-3 text-sm">
                            <p class="font-bold text-[#333333]">{{ $skill->skill_name }}</p>
                            <p class="text-xs text-[#64748B] mt-1">{{ ucfirst($skill->proficiency) }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        <!-- Fasilitas -->
        <section class="mb-12">
            <h2 class="text-sm font-medium text-[#0F172A] mb-2">Fasilitas yang Dimiliki</h2>
            <div class="rounded-xl border border-[#dce5f8] bg-[#F4F7FF] divide-y divide-[#dce5f8] overflow-hidden">
                @if($candidate->facilities->isEmpty())
                    <div class="px-4 py-6 text-center text-sm font-medium text-[#94a3b8]">
                        •••<br>Belum Ada
                    </div>
                @else
                    @foreach($candidate->facilities as $facility)
                        <div class="px-4 py-3 text-sm font-medium text-[#333333]">
                            {{ $facility->facility_name }}
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        <!-- Button -->
        <div class="pb-8">
            <a href="{{ route('candidate.registration.attachments') }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#223872] px-4 py-4 text-sm font-bold text-white transition hover:bg-[#1b2f60] shadow-md hover:shadow-lg hover:-translate-y-0.5">
                Lihat Lampiran
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
            </a>
        </div>
    </div>
</div>
@endsection
