<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat & Pengalaman - HIMATIK PNJ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4F7FF] font-sans text-[#333333] antialiased flex flex-col relative">
    <!-- Navbar -->
    <nav class="border-b border-[#dce5f8] bg-white">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-5 sm:px-8">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" alt="Logo HIMATIK" class="h-8 w-auto">
                <span class="text-lg font-black tracking-tight text-[#223872] hidden sm:block">HIMATIK PNJ</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="mx-auto flex w-full max-w-3xl flex-1 flex-col px-5 py-8 sm:px-8 sm:py-12">
        <div class="w-full">
            <h1 class="text-3xl font-black leading-tight tracking-tight text-[#111827] sm:text-4xl">
                Riwayat Pendidikan & Pengalaman
            </h1>
            <p class="mt-4 text-sm leading-relaxed text-[#64748b] sm:text-base">
                Ceritakan latar belakang dan pengalaman yang telah kamu lalui.
            </p>

            @if (session('success'))
                <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mt-8 space-y-8">
                
                <!-- Formal Education -->
                <div>
                    <h2 class="mb-3 text-sm font-bold text-[#333333]">Riwayat Pendidikan Formal</h2>
                    <div class="space-y-3">
                        @php $formalEds = $candidate->educations->where('education_type', 'formal'); @endphp
                        @if($formalEds->isEmpty())
                            <div class="rounded-xl border border-[#dce5f8] bg-white p-6 text-center text-[#929aaa]">
                                <p class="text-lg font-bold tracking-widest">...</p>
                                <p class="mt-1 text-xs font-medium">Belum Ada</p>
                            </div>
                        @else
                            @foreach($formalEds as $ed)
                                <div onclick='openEducationModal("formal", @json($ed))' class="group flex cursor-pointer items-center justify-between rounded-xl border border-[#dce5f8] bg-white p-4 transition hover:border-[#4A90E2] hover:shadow-sm">
                                    <div class="pr-4">
                                        <h3 class="text-sm font-bold text-[#333333]">{{ $ed->school_name }}</h3>
                                        <p class="mt-0.5 text-xs font-medium text-[#64748b]">Tahun {{ $ed->start_year }} s.d. {{ $ed->end_year ?: 'Sekarang' }}, di {{ $ed->city }}.{{ $ed->major ? ' Jurusan '.$ed->major : '' }}</p>
                                    </div>
                                    <svg class="h-4 w-4 shrink-0 text-[#929aaa] transition group-hover:text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick='openEducationModal("formal")' class="mt-3 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#223872] text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambah Riwayat
                    </button>
                </div>

                <!-- Informal Education -->
                <div>
                    <h2 class="mb-3 text-sm font-bold text-[#333333]">Riwayat Pendidikan Informal</h2>
                    <div class="space-y-3">
                        @php $informalEds = $candidate->educations->where('education_type', 'informal'); @endphp
                        @if($informalEds->isEmpty())
                            <div class="rounded-xl border border-[#dce5f8] bg-white p-6 text-center text-[#929aaa]">
                                <p class="text-lg font-bold tracking-widest">...</p>
                                <p class="mt-1 text-xs font-medium">Belum Ada</p>
                            </div>
                        @else
                            @foreach($informalEds as $ed)
                                <div onclick='openEducationModal("informal", @json($ed))' class="group flex cursor-pointer items-center justify-between rounded-xl border border-[#dce5f8] bg-white p-4 transition hover:border-[#4A90E2] hover:shadow-sm">
                                    <div class="pr-4">
                                        <h3 class="text-sm font-bold text-[#333333]">{{ $ed->school_name }}</h3>
                                        <p class="mt-0.5 text-xs font-medium text-[#64748b]">Tahun {{ $ed->start_year }} s.d. {{ $ed->end_year ?: 'Sekarang' }}, di {{ $ed->city }}.{{ $ed->major ? ' Jurusan '.$ed->major : '' }}</p>
                                    </div>
                                    <svg class="h-4 w-4 shrink-0 text-[#929aaa] transition group-hover:text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick='openEducationModal("informal")' class="mt-3 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#223872] text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambah Riwayat
                    </button>
                </div>

                <!-- Organization -->
                <div>
                    <h2 class="mb-3 text-sm font-bold text-[#333333]">Pengalaman Organisasi Luar Kampus</h2>
                    <div class="space-y-3">
                        @if($candidate->organizations->isEmpty())
                            <div class="rounded-xl border border-[#dce5f8] bg-white p-6 text-center text-[#929aaa]">
                                <p class="text-lg font-bold tracking-widest">...</p>
                                <p class="mt-1 text-xs font-medium">Belum Ada</p>
                            </div>
                        @else
                            @foreach($candidate->organizations as $org)
                                <div onclick='openOrgModal(@json($org))' class="group flex cursor-pointer items-center justify-between rounded-xl border border-[#dce5f8] bg-white p-4 transition hover:border-[#4A90E2] hover:shadow-sm">
                                    <div class="pr-4">
                                        <h3 class="text-sm font-bold text-[#333333]">{{ $org->organization_name }}</h3>
                                        <p class="mt-0.5 text-xs font-medium text-[#64748b]">Tahun {{ $org->start_year }} s.d. {{ $org->end_year ?: 'Sekarang' }}, di {{ $org->place_or_institution }}, Sebagai {{ $org->position }}.</p>
                                    </div>
                                    <svg class="h-4 w-4 shrink-0 text-[#929aaa] transition group-hover:text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick='openOrgModal()' class="mt-3 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#223872] text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambah Pengalaman
                    </button>
                </div>

                <!-- Committee -->
                <div>
                    <h2 class="mb-3 text-sm font-bold text-[#333333]">Pengalaman Kepanitiaan Dalam/Luar Kampus</h2>
                    <div class="space-y-3">
                        @if($candidate->committees->isEmpty())
                            <div class="rounded-xl border border-[#dce5f8] bg-white p-6 text-center text-[#929aaa]">
                                <p class="text-lg font-bold tracking-widest">...</p>
                                <p class="mt-1 text-xs font-medium">Belum Ada</p>
                            </div>
                        @else
                            @foreach($candidate->committees as $com)
                                <div onclick='openCommitteeModal(@json($com))' class="group flex cursor-pointer items-center justify-between rounded-xl border border-[#dce5f8] bg-white p-4 transition hover:border-[#4A90E2] hover:shadow-sm">
                                    <div class="pr-4">
                                        <h3 class="text-sm font-bold text-[#333333]">{{ $com->committee_name }}</h3>
                                        <p class="mt-0.5 text-xs font-medium text-[#64748b]">Tahun {{ $com->start_year }} s.d. {{ $com->end_year ?: 'Sekarang' }}, oleh {{ $com->organizer }}. Sebagai {{ $com->position }}.</p>
                                    </div>
                                    <svg class="h-4 w-4 shrink-0 text-[#929aaa] transition group-hover:text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick='openCommitteeModal()' class="mt-3 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#223872] text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambah Pengalaman
                    </button>
                </div>

            </div>

            <!-- Bottom Buttons -->
            <form action="{{ route('candidate.experience.next') }}" method="POST" class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center">
                @csrf
                <a href="{{ route('candidate.preferences.view') }}" class="inline-flex h-12 w-full flex-1 items-center justify-center gap-2 rounded-xl border-2 border-[#dce5f8] bg-transparent px-8 text-sm font-bold text-[#64748b] transition hover:border-[#223872] hover:text-[#223872] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20 sm:w-auto">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                
                <button type="submit" class="inline-flex h-12 w-full flex-1 items-center justify-center gap-2 rounded-xl bg-[#223872] px-8 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20 sm:w-auto">
                    Berikutnya
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        </div>
    </main>

    <!-- Modals -->

    <!-- Backdrop -->
    <div id="modalBackdrop" class="fixed inset-0 z-40 hidden bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeAllModals()"></div>

    <!-- Education Modal -->
    <div id="educationModal" class="fixed inset-0 z-50 flex hidden items-center justify-center px-4 overflow-y-auto pt-16 pb-16">
        <div class="w-full max-w-md rounded-2xl bg-[#F4F7FF] shadow-xl m-auto flex flex-col max-h-full">
            <div class="flex items-center justify-between border-b border-[#dce5f8] p-5 shrink-0">
                <button type="button" onclick="closeAllModals()" class="flex items-center gap-2 text-sm font-bold text-red-500 hover:text-red-700 focus:outline-none">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </button>
            </div>
            <div class="p-5 overflow-y-auto">
                <h3 id="educationModalTitle" class="mb-5 text-xl font-black text-[#111827]">Tambah Riwayat Pendidikan</h3>
                
                <form id="educationForm" action="{{ route('candidate.education.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="id" id="education_id">
                    <input type="hidden" name="education_type" id="education_type">
                    
                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Nama Sekolah <span class="text-red-500">*</span></label>
                        <input type="text" name="school_name" id="education_school_name" required placeholder="Masukkan Nama Sekolah" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Tahun Pendidikan <span class="text-red-500">*</span></label>
                        <div class="flex gap-3">
                            <input type="number" name="start_year" id="education_start_year" required placeholder="Awal" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                            <input type="number" name="end_year" id="education_end_year" placeholder="Akhir (Opsional)" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Tempat/Kota <span class="text-red-500">*</span></label>
                        <input type="text" name="city" id="education_city" required placeholder="Masukkan Tempat Sekolah" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Jurusan</label>
                        <input type="text" name="major" id="education_major" placeholder="Masukkan Jurusan" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" id="educationSubmitBtn" class="flex h-11 flex-1 items-center justify-center gap-2 rounded-xl bg-[#223872] text-sm font-bold text-white shadow-sm transition hover:bg-[#1b2f60]">
                            + Tambahkan
                        </button>
                    </div>
                </form>
                
                <form id="educationDeleteForm" action="" method="POST" class="hidden mt-3">
                    @csrf
                    <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-red-500 text-sm font-bold text-white shadow-sm transition hover:bg-red-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Organization Modal -->
    <div id="orgModal" class="fixed inset-0 z-50 flex hidden items-center justify-center px-4 overflow-y-auto pt-16 pb-16">
        <div class="w-full max-w-md rounded-2xl bg-[#F4F7FF] shadow-xl m-auto flex flex-col max-h-full">
            <div class="flex items-center justify-between border-b border-[#dce5f8] p-5 shrink-0">
                <button type="button" onclick="closeAllModals()" class="flex items-center gap-2 text-sm font-bold text-red-500 hover:text-red-700 focus:outline-none">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </button>
            </div>
            <div class="p-5 overflow-y-auto">
                <h3 id="orgModalTitle" class="mb-5 text-xl font-black text-[#111827]">Tambah Pengalaman Organisasi Luar Kampus</h3>
                
                <form id="orgForm" action="{{ route('candidate.organization.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="id" id="org_id">
                    
                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Nama Organisasi <span class="text-red-500">*</span></label>
                        <input type="text" name="organization_name" id="org_name" required placeholder="Masukkan Nama Organisasi" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Tahun <span class="text-red-500">*</span></label>
                        <div class="flex gap-3">
                            <input type="number" name="start_year" id="org_start_year" required placeholder="Awal" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                            <input type="number" name="end_year" id="org_end_year" placeholder="Akhir (Opsional)" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Tempat/Institusi <span class="text-red-500">*</span></label>
                        <input type="text" name="place_or_institution" id="org_place" required placeholder="Masukkan Tempat" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="position" id="org_position" required placeholder="Masukkan Jabatan" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" id="orgSubmitBtn" class="flex h-11 flex-1 items-center justify-center gap-2 rounded-xl bg-[#223872] text-sm font-bold text-white shadow-sm transition hover:bg-[#1b2f60]">
                            + Tambahkan
                        </button>
                    </div>
                </form>
                
                <form id="orgDeleteForm" action="" method="POST" class="hidden mt-3">
                    @csrf
                    <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-red-500 text-sm font-bold text-white shadow-sm transition hover:bg-red-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Committee Modal -->
    <div id="committeeModal" class="fixed inset-0 z-50 flex hidden items-center justify-center px-4 overflow-y-auto pt-16 pb-16">
        <div class="w-full max-w-md rounded-2xl bg-[#F4F7FF] shadow-xl m-auto flex flex-col max-h-full">
            <div class="flex items-center justify-between border-b border-[#dce5f8] p-5 shrink-0">
                <button type="button" onclick="closeAllModals()" class="flex items-center gap-2 text-sm font-bold text-red-500 hover:text-red-700 focus:outline-none">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </button>
            </div>
            <div class="p-5 overflow-y-auto">
                <h3 id="committeeModalTitle" class="mb-5 text-xl font-black text-[#111827]">Tambah Pengalaman Kepanitiaan</h3>
                
                <form id="committeeForm" action="{{ route('candidate.committee.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="id" id="com_id">
                    
                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Nama Kepanitiaan <span class="text-red-500">*</span></label>
                        <input type="text" name="committee_name" id="com_name" required placeholder="Masukkan Nama Kepanitiaan" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Tahun <span class="text-red-500">*</span></label>
                        <div class="flex gap-3">
                            <input type="number" name="start_year" id="com_start_year" required placeholder="Awal" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                            <input type="number" name="end_year" id="com_end_year" placeholder="Akhir (Opsional)" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Penyelenggara <span class="text-red-500">*</span></label>
                        <input type="text" name="organizer" id="com_organizer" required placeholder="Masukkan Penyelenggara" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="position" id="com_position" required placeholder="Masukkan Jabatan" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" id="committeeSubmitBtn" class="flex h-11 flex-1 items-center justify-center gap-2 rounded-xl bg-[#223872] text-sm font-bold text-white shadow-sm transition hover:bg-[#1b2f60]">
                            + Tambahkan
                        </button>
                    </div>
                </form>
                
                <form id="committeeDeleteForm" action="" method="POST" class="hidden mt-3">
                    @csrf
                    <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-red-500 text-sm font-bold text-white shadow-sm transition hover:bg-red-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const backdrop = document.getElementById('modalBackdrop');
        const modals = ['educationModal', 'orgModal', 'committeeModal'];

        function closeAllModals() {
            backdrop.classList.add('hidden');
            modals.forEach(m => document.getElementById(m).classList.add('hidden'));
        }

        function toggleEditMode(isEdit, modalType) {
            let submitBtnId, deleteFormId;
            if(modalType === 'education') { submitBtnId = 'educationSubmitBtn'; deleteFormId = 'educationDeleteForm'; }
            if(modalType === 'org') { submitBtnId = 'orgSubmitBtn'; deleteFormId = 'orgDeleteForm'; }
            if(modalType === 'committee') { submitBtnId = 'committeeSubmitBtn'; deleteFormId = 'committeeDeleteForm'; }
            
            const btn = document.getElementById(submitBtnId);
            const del = document.getElementById(deleteFormId);
            
            if(isEdit) {
                btn.innerHTML = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg> Edit';
                del.classList.remove('hidden');
            } else {
                btn.innerHTML = '+ Tambahkan';
                del.classList.add('hidden');
            }
        }

        function openEducationModal(type, data = null) {
            document.getElementById('educationForm').reset();
            document.getElementById('education_type').value = type;
            
            if(data) {
                document.getElementById('educationModalTitle').textContent = 'Edit Riwayat Pendidikan ' + (type === 'formal' ? 'Formal' : 'Informal');
                document.getElementById('education_id').value = data.id;
                document.getElementById('education_school_name').value = data.school_name;
                document.getElementById('education_start_year').value = data.start_year;
                document.getElementById('education_end_year').value = data.end_year || '';
                document.getElementById('education_city').value = data.city;
                document.getElementById('education_major').value = data.major || '';
                document.getElementById('educationDeleteForm').action = `/candidate/experience/education/${data.id}/delete`;
                toggleEditMode(true, 'education');
            } else {
                document.getElementById('educationModalTitle').textContent = 'Tambah Riwayat Pendidikan ' + (type === 'formal' ? 'Formal' : 'Informal');
                document.getElementById('education_id').value = '';
                toggleEditMode(false, 'education');
            }
            
            backdrop.classList.remove('hidden');
            document.getElementById('educationModal').classList.remove('hidden');
        }

        function openOrgModal(data = null) {
            document.getElementById('orgForm').reset();
            
            if(data) {
                document.getElementById('orgModalTitle').textContent = 'Edit Pengalaman Organisasi Luar Kampus';
                document.getElementById('org_id').value = data.id;
                document.getElementById('org_name').value = data.organization_name;
                document.getElementById('org_start_year').value = data.start_year;
                document.getElementById('org_end_year').value = data.end_year || '';
                document.getElementById('org_place').value = data.place_or_institution;
                document.getElementById('org_position').value = data.position;
                document.getElementById('orgDeleteForm').action = `/candidate/experience/organization/${data.id}/delete`;
                toggleEditMode(true, 'org');
            } else {
                document.getElementById('orgModalTitle').textContent = 'Tambah Pengalaman Organisasi Luar Kampus';
                document.getElementById('org_id').value = '';
                toggleEditMode(false, 'org');
            }
            
            backdrop.classList.remove('hidden');
            document.getElementById('orgModal').classList.remove('hidden');
        }

        function openCommitteeModal(data = null) {
            document.getElementById('committeeForm').reset();
            
            if(data) {
                document.getElementById('committeeModalTitle').textContent = 'Edit Pengalaman Kepanitiaan';
                document.getElementById('com_id').value = data.id;
                document.getElementById('com_name').value = data.committee_name;
                document.getElementById('com_start_year').value = data.start_year;
                document.getElementById('com_end_year').value = data.end_year || '';
                document.getElementById('com_organizer').value = data.organizer;
                document.getElementById('com_position').value = data.position;
                document.getElementById('committeeDeleteForm').action = `/candidate/experience/committee/${data.id}/delete`;
                toggleEditMode(true, 'committee');
            } else {
                document.getElementById('committeeModalTitle').textContent = 'Tambah Pengalaman Kepanitiaan';
                document.getElementById('com_id').value = '';
                toggleEditMode(false, 'committee');
            }
            
            backdrop.classList.remove('hidden');
            document.getElementById('committeeModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
