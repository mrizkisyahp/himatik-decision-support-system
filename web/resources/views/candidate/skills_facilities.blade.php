<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemampuan & Fasilitas - HIMATIK PNJ</title>
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
                Kemampuan & Fasilitas Penunjang
            </h1>
            <p class="mt-4 text-sm leading-relaxed text-[#64748b] sm:text-base">
                Masukkan keahlian dan alat penunjang yang kamu miliki untuk mendukung kinerjamu nanti.
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
                
                <!-- Soft Skills -->
                <div>
                    <h2 class="mb-3 text-sm font-bold text-[#333333]">Kemampuan Non-Teknis (Soft Skill)</h2>
                    <div class="space-y-3">
                        @php $softSkills = $candidate->skills->where('skill_type', 'soft'); @endphp
                        @if($softSkills->isEmpty())
                            <div class="rounded-xl border border-[#dce5f8] bg-white p-6 text-center text-[#929aaa]">
                                <p class="text-lg font-bold tracking-widest">...</p>
                                <p class="mt-1 text-xs font-medium">Belum Ada</p>
                            </div>
                        @else
                            @foreach($softSkills as $skill)
                                <div onclick='openSkillModal("soft", @json($skill))' class="group flex cursor-pointer items-center justify-between rounded-xl border border-[#dce5f8] bg-white p-4 transition hover:border-[#4A90E2] hover:shadow-sm">
                                    <div class="pr-4">
                                        <h3 class="text-sm font-bold text-[#333333]">{{ $skill->skill_name }}</h3>
                                        <p class="mt-0.5 text-xs font-medium text-[#64748b] capitalize">{{ $skill->proficiency }}</p>
                                    </div>
                                    <svg class="h-4 w-4 shrink-0 text-[#929aaa] transition group-hover:text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick='openSkillModal("soft")' class="mt-3 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#223872] text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambah Kemampuan
                    </button>
                </div>

                <!-- Hard Skills -->
                <div>
                    <h2 class="mb-3 text-sm font-bold text-[#333333]">Kemampuan Teknis (Hard Skill)</h2>
                    <div class="space-y-3">
                        @php $hardSkills = $candidate->skills->where('skill_type', 'hard'); @endphp
                        @if($hardSkills->isEmpty())
                            <div class="rounded-xl border border-[#dce5f8] bg-white p-6 text-center text-[#929aaa]">
                                <p class="text-lg font-bold tracking-widest">...</p>
                                <p class="mt-1 text-xs font-medium">Belum Ada</p>
                            </div>
                        @else
                            @foreach($hardSkills as $skill)
                                <div onclick='openSkillModal("hard", @json($skill))' class="group flex cursor-pointer items-center justify-between rounded-xl border border-[#dce5f8] bg-white p-4 transition hover:border-[#4A90E2] hover:shadow-sm">
                                    <div class="pr-4">
                                        <h3 class="text-sm font-bold text-[#333333]">{{ $skill->skill_name }}</h3>
                                        <p class="mt-0.5 text-xs font-medium text-[#64748b] capitalize">{{ $skill->proficiency }}</p>
                                    </div>
                                    <svg class="h-4 w-4 shrink-0 text-[#929aaa] transition group-hover:text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick='openSkillModal("hard")' class="mt-3 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#223872] text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambah Kemampuan
                    </button>
                </div>

                <!-- Facilities -->
                <div>
                    <h2 class="mb-3 text-sm font-bold text-[#333333]">Fasilitas yang Dimiliki</h2>
                    
                    <div class="rounded-xl border border-[#dce5f8] bg-white overflow-hidden">
                        @if($candidate->facilities->isEmpty())
                            <div class="p-4 text-center text-sm font-medium text-[#929aaa]">Belum ada fasilitas.</div>
                        @else
                            <ul class="divide-y divide-[#dce5f8]">
                                @foreach($candidate->facilities as $fac)
                                    <li class="flex items-center justify-between p-4">
                                        <span class="text-sm font-medium text-[#333333]">{{ $fac->facility_name }}</span>
                                        <form action="{{ route('candidate.facility.destroy', $fac->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-red-500 hover:text-red-700 focus:outline-none">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Inline Form for Facility -->
                    <form action="{{ route('candidate.facility.store') }}" method="POST" class="mt-3 flex items-center gap-2">
                        @csrf
                        <input type="text" name="facility_name" placeholder="Masukkan Fasilitas" required class="h-11 w-full flex-1 rounded-xl border border-[#dce5f8] bg-white px-4 text-sm font-medium text-[#333333] outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                        <button type="submit" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#223872] text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </form>
                </div>

            </div>

            <!-- Bottom Buttons -->
            <form action="{{ route('candidate.skills.next') }}" method="POST" class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center">
                @csrf
                <a href="{{ route('candidate.experience.view') }}" class="inline-flex h-12 w-full flex-1 items-center justify-center gap-2 rounded-xl border-2 border-[#dce5f8] bg-transparent px-8 text-sm font-bold text-[#64748b] transition hover:border-[#223872] hover:text-[#223872] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20 sm:w-auto">
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

    <!-- Skill Modal -->
    <div id="skillModal" class="fixed inset-0 z-50 flex hidden items-center justify-center px-4 overflow-y-auto pt-16 pb-16">
        <div class="w-full max-w-md rounded-2xl bg-[#F4F7FF] shadow-xl m-auto flex flex-col max-h-full">
            <div class="flex items-center justify-between p-5 shrink-0">
                <button type="button" onclick="closeAllModals()" class="flex items-center gap-2 text-sm font-bold text-red-500 hover:text-red-700 focus:outline-none">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </button>
            </div>
            <div class="px-5 pb-6 overflow-y-auto">
                <h3 id="skillModalTitle" class="mb-6 text-xl font-black text-[#111827]">Tambah Kemampuan</h3>
                
                <form id="skillForm" action="{{ route('candidate.skill.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="id" id="skill_id">
                    <input type="hidden" name="skill_type" id="skill_type">
                    
                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-2">Jenis Kemampuan</label>
                        <input type="text" name="skill_name" id="skill_name" required placeholder="Masukkan Kemampuan" class="h-11 w-full rounded-xl border border-[#dce5f8] px-3 text-sm font-medium outline-none transition focus:border-[#4A90E2] focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-2">Tingkat Kemampuan</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-[#333333]">
                                <input type="radio" name="proficiency" value="dasar" class="h-4 w-4 text-[#223872] focus:ring-[#4A90E2]">
                                Dasar
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-[#333333]">
                                <input type="radio" name="proficiency" value="sedang" class="h-4 w-4 text-[#223872] focus:ring-[#4A90E2]">
                                Sedang
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-[#333333]">
                                <input type="radio" name="proficiency" value="cakap" class="h-4 w-4 text-[#223872] focus:ring-[#4A90E2]">
                                Cakap
                            </label>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" id="skillSubmitBtn" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#223872] text-sm font-bold text-white shadow-sm transition hover:bg-[#1b2f60]">
                            + Tambahkan
                        </button>
                    </div>
                </form>
                
                <form id="skillDeleteForm" action="" method="POST" class="hidden mt-3">
                    @csrf
                    <button type="submit" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-red-500 text-sm font-bold text-white shadow-sm transition hover:bg-red-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const backdrop = document.getElementById('modalBackdrop');

        function closeAllModals() {
            backdrop.classList.add('hidden');
            document.getElementById('skillModal').classList.add('hidden');
        }

        function openSkillModal(type, data = null) {
            document.getElementById('skillForm').reset();
            document.getElementById('skill_type').value = type;
            
            const titleLabel = type === 'soft' ? 'Kemampuan Non-Teknis' : 'Kemampuan Teknis';

            if(data) {
                document.getElementById('skillModalTitle').textContent = 'Edit ' + titleLabel;
                document.getElementById('skill_id').value = data.id;
                document.getElementById('skill_name').value = data.skill_name;
                
                const radios = document.getElementsByName('proficiency');
                for(let r of radios) {
                    if(r.value === data.proficiency) r.checked = true;
                }

                document.getElementById('skillDeleteForm').action = `/candidate/skills/${data.id}/delete`;
                document.getElementById('skillDeleteForm').classList.remove('hidden');
                document.getElementById('skillSubmitBtn').innerHTML = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg> Edit';
            } else {
                document.getElementById('skillModalTitle').textContent = 'Tambah ' + titleLabel;
                document.getElementById('skill_id').value = '';
                document.getElementById('skillDeleteForm').classList.add('hidden');
                document.getElementById('skillSubmitBtn').innerHTML = '+ Tambahkan';
            }
            
            backdrop.classList.remove('hidden');
            document.getElementById('skillModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
