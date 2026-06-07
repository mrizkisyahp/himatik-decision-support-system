<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIMATIK PNJ - Open Recruitment</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-[#223872] font-sans text-white antialiased">
    <div class="relative min-h-screen overflow-hidden bg-[#223872] text-white">
        <img src="{{ asset('images/1776510571221-foto_bersama.png') }}" alt="Foto bersama HIMATIK PNJ"
            class="absolute inset-0 h-full w-full scale-105 object-cover opacity-60 blur-[2px]">
        <div
            class="absolute inset-0 bg-[linear-gradient(180deg,rgba(9,18,43,0.56)_0%,rgba(34,56,114,0.82)_48%,rgba(34,56,114,0.98)_100%)]">
        </div>
        <div
            class="absolute inset-x-0 bottom-0 h-48 bg-[linear-gradient(180deg,rgba(34,56,114,0)_0%,rgba(34,56,114,1)_100%)]">
        </div>

        <nav class="fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-[#0f1f47]/55 backdrop-blur-2xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-3 lg:px-8">
                <a href="#hero" class="flex items-center gap-3">
                    <img src="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" alt="Logo HIMATIK"
                        class="h-9 w-9 object-contain">
                    <span class="text-sm font-extrabold tracking-wide text-white">HIMATIK PNJ</span>
                </a>

                <div class="hidden items-center gap-6 text-[13px] font-semibold text-white/78 lg:flex">
                    <a href="#tentang-kami" class="transition hover:text-white">Tentang Kami</a>
                    <a href="#visi-misi" class="transition hover:text-white">Visi Misi</a>
                    <a href="#departemen-biro" class="transition hover:text-white">Departemen/Biro</a>
                    <a href="#bergabung" class="transition hover:text-white">Bergabung</a>
                    <a href="{{ route('public.announcements') }}" class="transition hover:text-white">Pengumuman</a>
                    @auth
                        @php
                            $dashboardRoute = 'dashboard';
                            if (auth()->user()->role === 'admin') {
                                $dashboardRoute = 'admin.dashboard';
                            } elseif (auth()->user()->role === 'interviewer') {
                                $dashboardRoute = 'interviewer.dashboard';
                            } elseif (auth()->user()->role === 'candidate') {
                                $dashboardRoute = 'candidate.dashboard';
                            }
                        @endphp
                        <a href="{{ route($dashboardRoute) }}"
                            class="rounded-full bg-white/95 px-4 py-2 font-bold text-[#223872] shadow-lg shadow-black/10 transition hover:bg-white">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="rounded-full bg-white/95 px-4 py-2 font-bold text-[#223872] shadow-lg shadow-black/10 transition hover:bg-white">
                            Masuk Portal
                        </a>
                    @endauth
                </div>

                <button type="button" data-mobile-menu-button
                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-white lg:hidden"
                    aria-label="Buka menu navigasi">
                    <svg data-menu-open class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                    <svg data-menu-close class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div data-mobile-menu
                class="hidden border-t border-white/10 bg-[#0f1f47]/95 px-5 pb-5 pt-2 text-sm font-semibold text-white/85 lg:hidden">
                <div class="mx-auto flex max-w-7xl flex-col gap-2">
                    <a href="#tentang-kami" class="rounded-2xl px-4 py-3 transition hover:bg-white/10">Tentang Kami</a>
                    <a href="#visi-misi" class="rounded-2xl px-4 py-3 transition hover:bg-white/10">Visi Misi</a>
                    <a href="#departemen-biro"
                        class="rounded-2xl px-4 py-3 transition hover:bg-white/10">Departemen/Biro</a>
                    <a href="#bergabung" class="rounded-2xl px-4 py-3 transition hover:bg-white/10">Bergabung</a>
                    <a href="{{ route('public.announcements') }}" class="rounded-2xl px-4 py-3 transition hover:bg-white/10">Pengumuman</a>
                    @auth
                        @php
                            $dashboardRoute = 'dashboard';
                            if (auth()->user()->role === 'admin') {
                                $dashboardRoute = 'admin.dashboard';
                            } elseif (auth()->user()->role === 'interviewer') {
                                $dashboardRoute = 'interviewer.dashboard';
                            } elseif (auth()->user()->role === 'candidate') {
                                $dashboardRoute = 'candidate.dashboard';
                            }
                        @endphp
                        <a href="{{ route($dashboardRoute) }}"
                            class="mt-2 rounded-2xl bg-white px-4 py-3 text-center font-bold text-[#223872]">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="mt-2 rounded-2xl bg-white px-4 py-3 text-center font-bold text-[#223872]">Masuk Portal</a>
                    @endauth
                </div>
            </div>
        </nav>

        <section id="hero"
            class="relative z-10 mx-auto flex min-h-screen max-w-6xl items-center justify-center px-5 pb-16 pt-24 text-center lg:px-8">
            <div class="w-full">
                <img src="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" alt="Logo HIMATIK"
                    class="mx-auto mb-7 h-36 w-36 object-contain drop-shadow-[0_24px_55px_rgba(0,0,0,0.38)] sm:h-48 sm:w-48 lg:h-56 lg:w-56">
                <div class="mx-auto max-w-4xl">

                    <h1
                        class="text-5xl font-black leading-none tracking-tight text-white drop-shadow-[0_12px_28px_rgba(0,0,0,0.28)] sm:text-7xl lg:text-[6.5rem]">
                        HIMATIK PNJ
                    </h1>
                    <p class="mt-5 text-lg font-bold text-white/88 sm:text-2xl">
                        Himpunan Mahasiswa Teknik Informatika dan Komputer
                    </p>
                    <p class="mt-3 text-xs font-extrabold uppercase tracking-[0.35em] text-white/65 sm:text-sm">
                        Politeknik Negeri Jakarta
                    </p>
                    <p class="mx-auto mt-6 max-w-2xl text-sm leading-7 text-white/74 sm:text-base">
                        Kenali HIMATIK PNJ, jelajahi departemen dan biro, lalu temukan ruang kontribusimu dalam
                        organisasi.
                    </p>

                    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                        @auth
                            @php
                                $dashboardRoute = 'dashboard';
                                if (auth()->user()->role === 'admin') {
                                    $dashboardRoute = 'admin.dashboard';
                                } elseif (auth()->user()->role === 'interviewer') {
                                    $dashboardRoute = 'interviewer.dashboard';
                                } elseif (auth()->user()->role === 'candidate') {
                                    $dashboardRoute = 'candidate.dashboard';
                                }
                            @endphp
                            <a href="{{ route($dashboardRoute) }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-sm font-extrabold text-[#223872] shadow-xl shadow-black/15 transition hover:-translate-y-0.5 hover:bg-[#F4F7FF]">
                                Dashboard
                                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                        d="M13 7l5 5m0 0-5 5m5-5H6" />
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-sm font-extrabold text-[#223872] shadow-xl shadow-black/15 transition hover:-translate-y-0.5 hover:bg-[#F4F7FF]">
                                Masuk Portal
                                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                        d="M13 7l5 5m0 0-5 5m5-5H6" />
                                </svg>
                            </a>
                        @endauth
                        <a href="#departemen-biro"
                            class="inline-flex items-center justify-center rounded-2xl border border-white/25 bg-white/10 px-6 py-3.5 text-sm font-extrabold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/20">
                            Lihat Departemen/Biro
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <main class="bg-[#223872]">
        <section id="tentang-kami"
            class="scroll-mt-24 border-y border-white/10 bg-[#223872] px-5 py-14 text-white lg:px-8 lg:py-18">
            <div class="mx-auto grid max-w-7xl items-center gap-9 lg:grid-cols-[0.85fr_1.15fr]">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-[#9ecbff]">Tentang Kami</p>
                    <h2 class="mt-4 text-4xl font-black leading-none tracking-tight sm:text-5xl lg:text-6xl">Profil
                        HIMATIK PNJ</h2>
                    <div class="mt-6 h-1.5 w-24 rounded-full bg-[#4A90E2]"></div>
                </div>
                <div
                    class="rounded-[1.8rem] border border-white/14 bg-white/9 p-6 shadow-[0_22px_58px_rgba(0,0,0,0.14)] backdrop-blur sm:p-8">
                    <p class="text-base font-medium leading-8 text-white/86 sm:text-lg sm:leading-9">
                        HIMATIK PNJ adalah lembaga kemahasiswaan formal di Jurusan Teknik Informatika dan Komputer
                        Politeknik Negeri Jakarta. Organisasi ini bergerak di bidang keilmuan serta menjadi penggerak
                        mahasiswa dalam meningkatkan kreativitas dan prestasi.
                    </p>
                </div>
            </div>
        </section>

        <section id="visi-misi"
            class="scroll-mt-24 overflow-hidden bg-[linear-gradient(180deg,#223872_0%,#1a2d60_100%)] px-5 py-14 text-white lg:px-8 lg:py-18">
            <div class="mx-auto grid max-w-7xl items-center gap-10 lg:grid-cols-[0.9fr_1.1fr]">
                <div class="relative">
                    <div class="absolute -inset-8 rounded-full bg-[#4A90E2]/16 blur-3xl"></div>
                    <img src="{{ asset('images/1776510686448-kabinet_w_text.png') }}"
                        alt="Identitas kabinet HIMATIK PNJ"
                        class="relative mx-auto max-h-[380px] w-full max-w-md object-contain drop-shadow-[0_24px_55px_rgba(0,0,0,0.24)] lg:max-h-[470px]">
                </div>

                <div>
                    <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-[#9ecbff]">Visi Misi</p>
                    <div
                        class="mt-7 rounded-[1.7rem] border border-white/14 bg-white/9 p-6 shadow-[0_22px_58px_rgba(0,0,0,0.14)] backdrop-blur sm:p-7">
                        <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-[#9ecbff]">Visi</p>
                        <p class="mt-4 text-sm leading-7 text-white/82 sm:text-base">
                            Mewujudkan sinergi mahasiswa TIK dalam membangun HIMATIK yang berdaya, transparan, dan
                            berpola pikir luas guna memberikan dampak nyata dan kebermanfaatan.
                        </p>
                    </div>

                    <div
                        class="mt-5 rounded-[1.7rem] border border-white/14 bg-[#0f1f47]/38 p-6 shadow-[0_22px_58px_rgba(0,0,0,0.12)] backdrop-blur sm:p-7">
                        <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-[#9ecbff]">Misi</p>
                        <ol class="mt-5 space-y-3.5">
                            <li class="flex gap-4">
                                <span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-sm font-extrabold text-[#223872]">1</span>
                                <p class="text-sm leading-7 text-white/82 sm:text-base">Menjadi wadah pengembangan
                                    kompetensi dan karakter mahasiswa TIK dengan menyelenggarakan program-program unggul
                                    di bidang akademik, teknologi, dan kreativitas.</p>
                            </li>
                            <li class="flex gap-4">
                                <span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-sm font-extrabold text-[#223872]">2</span>
                                <p class="text-sm leading-7 text-white/82 sm:text-base">Menjaga dan memperkuat budaya
                                    solidaritas dalam semua kegiatan HIMATIK.</p>
                            </li>
                            <li class="flex gap-4">
                                <span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-sm font-extrabold text-[#223872]">3</span>
                                <p class="text-sm leading-7 text-white/82 sm:text-base">Mendorong profesionalisme dalam
                                    kerja HIMATIK melalui transparansi dan publikasi kegiatan secara berkala.</p>
                            </li>
                            <li class="flex gap-4">
                                <span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-sm font-extrabold text-[#223872]">4</span>
                                <p class="text-sm leading-7 text-white/82 sm:text-base">Menanamkan nilai integritas dan
                                    tanggung jawab sosial sebagai dasar dalam setiap tindakan dan program HIMATIK.</p>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section id="departemen-biro" class="scroll-mt-24 bg-[#223872] px-5 py-14 text-white lg:px-8 lg:py-18">
            <div class="mx-auto max-w-7xl">
                <div class="mb-10 text-center">
                    <h2 class="mt-3 text-4xl font-black tracking-tight text-white sm:text-5xl">Departemen dan Biro</h2>
                    <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-white/72 sm:text-base">
                        Kenali setiap departemen dan biro HIMATIK PNJ, lalu temukan ruang kontribusi yang paling sesuai dengan minatmu.
                    </p>
                </div>

                @if($departments->isEmpty())
                    <div
                        class="rounded-[2rem] border border-dashed border-white/25 bg-white/10 px-6 py-12 text-center shadow-[0_18px_50px_rgba(0,0,0,0.08)]">
                        <p class="text-base font-semibold text-white/72">Belum ada departemen/biro yang tersedia.</p>
                    </div>
                @else
                    @php
                        $departmentItems = $departments->values()->map(function ($department, $index) {
                            $workPrograms = $department->relationLoaded('workPrograms') ? $department->workPrograms : collect();
                            $agendas = $department->relationLoaded('agendas') ? $department->agendas : collect();
                            $type = str_starts_with($department->name, 'Biro')
                                ? 'BIRO'
                                : (str_starts_with($department->name, 'Departemen') ? 'DEPARTEMEN' : 'UNIT HIMATIK');

                            return [
                                'id' => 'department-' . $index,
                                'type' => $type,
                                'name' => $department->name,
                                'description' => $department->description,
                                'work_programs' => $workPrograms->map(fn($program) => [
                                    'name' => $program->name,
                                    'description' => $program->description,
                                    'period' => $program->period,
                                ])->values(),
                                'agendas' => $agendas->map(fn($agenda) => [
                                    'title' => $agenda->title,
                                    'description' => $agenda->description,
                                    'date' => $agenda->start_date ? $agenda->start_date->format('d M Y') . ($agenda->end_date ? ' - ' . $agenda->end_date->format('d M Y') : '') : null,
                                ])->values(),
                            ];
                        });
                    @endphp

                    <div class="mb-7 flex flex-wrap justify-center gap-3">
                        <button type="button" data-department-filter="SEMUA" class="rounded-full border border-white/14 bg-white/10 px-5 py-2 text-sm font-extrabold text-white/72 transition hover:-translate-y-0.5 hover:border-[#4A90E2]/55 hover:text-white">
                            Semua
                        </button>
                        <button type="button" data-department-filter="BIRO" class="rounded-full border border-white/14 bg-white/10 px-5 py-2 text-sm font-extrabold text-white/72 transition hover:-translate-y-0.5 hover:border-[#4A90E2]/55 hover:text-white">
                            Biro
                        </button>
                        <button type="button" data-department-filter="DEPARTEMEN" class="rounded-full border border-white/14 bg-white/10 px-5 py-2 text-sm font-extrabold text-white/72 transition hover:-translate-y-0.5 hover:border-[#4A90E2]/55 hover:text-white">
                            Departemen
                        </button>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($departmentItems as $item)
                            <article data-department-card data-department-id="{{ $item['id'] }}" data-department-type="{{ $item['type'] }}" class="group flex min-h-[18rem] flex-col rounded-[1.65rem] border border-white/12 bg-white/10 p-6 backdrop-blur transition hover:-translate-y-1 hover:border-[#4A90E2]/55 hover:bg-white/14 hover:shadow-[0_24px_70px_rgba(0,0,0,0.16)]">
                                <div class="mb-5 flex items-center justify-between gap-4">
                                    <span class="inline-flex rounded-full border border-[#4A90E2]/35 bg-[#4A90E2]/18 px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#9ecbff]">
                                        {{ $item['type'] }}
                                    </span>
                                    <span class="h-1.5 w-12 rounded-full bg-[#4A90E2] transition group-hover:w-16"></span>
                                </div>

                                <h3 class="text-xl font-black leading-snug text-white">{{ $item['name'] }}</h3>
                                @if($item['description'])
                                    <p class="mt-4 line-clamp-4 text-sm leading-7 text-white/70">{{ $item['description'] }}</p>
                                @endif

                                <div class="mt-auto pt-6">
                                    <div class="mb-5 flex flex-wrap gap-2 text-xs font-bold text-white/68">
                                        <span class="rounded-full border border-white/10 bg-[#0f1f47]/24 px-3 py-1">{{ count($item['work_programs']) }} Program Kerja</span>
                                        <span class="rounded-full border border-white/10 bg-[#0f1f47]/24 px-3 py-1">{{ count($item['agendas']) }} Agenda</span>
                                    </div>

                                    <button type="button" data-department-open data-department-id="{{ $item['id'] }}" class="inline-flex w-full items-center justify-center rounded-2xl border border-white/12 bg-white px-4 py-3 text-sm font-extrabold text-[#223872] transition hover:bg-[#F4F7FF]">
                                        Lihat Detail
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div data-department-modal class="fixed inset-0 z-[80] hidden items-end justify-center bg-[#06122d]/72 px-4 py-4 backdrop-blur-sm sm:items-center sm:py-8" role="dialog" aria-modal="true" aria-labelledby="department-modal-title">
                        <button type="button" data-department-modal-backdrop class="absolute inset-0" aria-label="Tutup detail departemen"></button>

                        <article class="relative max-h-[92vh] w-full max-w-3xl overflow-hidden rounded-[1.75rem] border border-white/14 bg-[#F4F7FF] text-[#333333] shadow-[0_32px_90px_rgba(0,0,0,0.30)]">
                            <div class="flex items-start justify-between gap-4 bg-[#223872] px-5 py-5 text-white sm:px-7">
                                <div>
                                    <p data-modal-type class="inline-flex rounded-full border border-[#4A90E2]/45 bg-[#4A90E2]/22 px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#cfe6ff]"></p>
                                    <h3 id="department-modal-title" data-modal-name class="mt-3 text-2xl font-black leading-tight sm:text-3xl"></h3>
                                </div>
                                <button type="button" data-department-modal-close class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/12 text-white transition hover:bg-white/20" aria-label="Tutup modal">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="max-h-[calc(92vh-7rem)] overflow-y-auto px-5 py-5 sm:px-7 sm:py-6">
                                <p data-modal-description class="text-sm leading-7 text-[#475569] sm:text-base"></p>

                                <div class="mt-6 grid gap-5 md:grid-cols-2">
                                    <section>
                                        <h4 class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#4A90E2]">Program Kerja</h4>
                                        <ul data-modal-proker-list class="mt-3 space-y-3"></ul>
                                    </section>

                                    <section>
                                        <h4 class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#4A90E2]">Agenda</h4>
                                        <ul data-modal-agenda-list class="mt-3 space-y-3"></ul>
                                    </section>
                                </div>
                            </div>
                        </article>
                    </div>

                    <script type="application/json" id="department-biro-data">@json($departmentItems)</script>
                @endif
            </div>
        </section>

        <section id="bergabung"
            class="scroll-mt-24 bg-[linear-gradient(180deg,#1a2d60_0%,#223872_100%)] px-5 py-14 text-white lg:px-8 lg:py-18">
            <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[0.85fr_1.15fr] lg:items-center">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-[#9ecbff]">Open Recruitment</p>
                    <h2 class="mt-3 text-4xl font-black tracking-tight sm:text-5xl">Tertarik untuk bergabung?</h2>
                    <p class="mt-4 max-w-xl text-base leading-8 text-white/72">
                        Pendaftaran akan dibuka sesuai periode open recruitment HIMATIK PNJ.
                    </p>
                </div>

                @if($openRecruitmentCards->isEmpty())
                    <div class="rounded-[1.7rem] border border-white/14 bg-white/10 p-6 shadow-2xl shadow-black/10 backdrop-blur">
                        <div class="mb-5 inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-bold text-white/80">
                            Tidak Ada Oprec
                        </div>
                        <h3 class="text-2xl font-black">Pendaftaran sedang ditutup</h3>
                        <p class="mt-3 text-sm leading-7 text-white/70">
                            Saat ini belum ada periode open recruitment HIMATIK PNJ yang sedang dibuka.
                        </p>
                    </div>
                @else
                    <div class="grid gap-5 md:grid-cols-2">
                        @foreach($openRecruitmentCards as $card)
                        <div
                            class="rounded-[1.7rem] border border-white/14 bg-white/10 p-6 shadow-2xl shadow-black/10 backdrop-blur">
                            <div
                                class="mb-5 inline-flex rounded-full {{ $card['is_upcoming'] ? 'bg-amber-400/20 text-amber-100' : 'bg-emerald-400/20 text-emerald-100' }} px-3 py-1 text-xs font-bold">
                                {{ $card['is_upcoming'] ? 'Akan Dibuka' : 'Sedang Dibuka' }}
                            </div>
                            <h3 class="text-2xl font-black">{{ $card['title'] }}</h3>
                            <p class="mt-3 text-sm leading-7 text-white/70">{{ $card['date_text'] }}</p>
                            <p class="mt-2 text-sm leading-6 text-white/62">{{ $card['message'] }}</p>
                            @if ($card['is_upcoming'])
                                <button disabled
                                    class="mt-6 inline-flex cursor-not-allowed items-center justify-center rounded-2xl bg-white/20 px-5 py-3 text-sm font-extrabold text-white/50">
                                    Belum Dimulai
                                </button>
                            @else
                                <a href="{{ route('user.register.view', ['candidate_type' => $card['candidate_type']]) }}"
                                    class="mt-6 inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-extrabold text-[#223872] transition hover:bg-[#F4F7FF]">
                                    Daftar Sekarang
                                </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </main>

    <footer class="border-t border-white/10 bg-[#0f1f47] px-5 py-6 text-white lg:px-8">
        <div class="mx-auto flex max-w-7xl flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" alt="Logo HIMATIK"
                    class="h-10 w-10 object-contain">
                <div>
                    <p class="text-sm font-extrabold">HIMATIK PNJ</p>
                    <p class="text-xs text-white/55">Himpunan Mahasiswa Teknik Informatika dan Komputer PNJ</p>
                </div>
            </div>
            <p class="text-xs text-white/55">&copy; {{ date('Y') }} HIMATIK PNJ.</p>
        </div>
    </footer>

    <script>
        const menuButton = document.querySelector('[data-mobile-menu-button]');
        const mobileMenu = document.querySelector('[data-mobile-menu]');
        const openIcon = document.querySelector('[data-menu-open]');
        const closeIcon = document.querySelector('[data-menu-close]');

        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', () => {
                const isOpen = !mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden', isOpen);
                openIcon?.classList.toggle('hidden', !isOpen);
                closeIcon?.classList.toggle('hidden', isOpen);
            });

            mobileMenu.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    openIcon?.classList.remove('hidden');
                    closeIcon?.classList.add('hidden');
                });
            });
        }

        const departmentDataScript = document.querySelector('#department-biro-data');
        const departmentCards = document.querySelectorAll('[data-department-card]');
        const departmentFilterButtons = document.querySelectorAll('[data-department-filter]');
        const departmentOpenButtons = document.querySelectorAll('[data-department-open]');
        const departmentModal = document.querySelector('[data-department-modal]');

        if (departmentDataScript && departmentCards.length > 0 && departmentModal) {
            const departments = JSON.parse(departmentDataScript.textContent || '[]');
            const modal = {
                type: departmentModal.querySelector('[data-modal-type]'),
                name: departmentModal.querySelector('[data-modal-name]'),
                description: departmentModal.querySelector('[data-modal-description]'),
                prokerList: departmentModal.querySelector('[data-modal-proker-list]'),
                agendaList: departmentModal.querySelector('[data-modal-agenda-list]'),
                closeButtons: departmentModal.querySelectorAll('[data-department-modal-close], [data-department-modal-backdrop]'),
            };

            const activeFilterClasses = ['border-[#4A90E2]', 'bg-white', 'text-[#223872]', 'shadow-[0_16px_36px_rgba(0,0,0,0.16)]'];
            const inactiveFilterClasses = ['border-white/14', 'bg-white/10', 'text-white/72'];

            const setFilter = (filter) => {
                departmentCards.forEach((card) => {
                    const shouldShow = filter === 'SEMUA' || card.dataset.departmentType === filter;
                    card.classList.toggle('hidden', !shouldShow);
                });

                departmentFilterButtons.forEach((button) => {
                    const isActive = button.dataset.departmentFilter === filter;
                    activeFilterClasses.forEach((className) => button.classList.toggle(className, isActive));
                    inactiveFilterClasses.forEach((className) => button.classList.toggle(className, !isActive));
                });
            };

            const emptyListItem = (message) => {
                const item = document.createElement('li');
                item.className = 'rounded-2xl border border-[#dbe5ff] bg-white px-4 py-3 text-sm leading-6 text-[#64748b]';
                item.textContent = message;
                return item;
            };

            const appendListItems = (list, rows, emptyMessage, titleKey) => {
                list.replaceChildren();

                if (!rows || rows.length === 0) {
                    list.appendChild(emptyListItem(emptyMessage));
                    return;
                }

                rows.forEach((row) => {
                    const item = document.createElement('li');
                    item.className = 'rounded-2xl border border-[#dbe5ff] bg-white px-4 py-3 shadow-sm shadow-[#223872]/5';

                    const title = document.createElement('p');
                    title.className = 'text-sm font-extrabold text-[#223872]';
                    title.textContent = row[titleKey] || '';
                    item.appendChild(title);

                    if (row.description) {
                        const description = document.createElement('p');
                        description.className = 'mt-1 text-sm leading-6 text-[#64748b]';
                        description.textContent = row.description;
                        item.appendChild(description);
                    }

                    if (row.period || row.date) {
                        const meta = document.createElement('p');
                        meta.className = 'mt-2 text-xs font-bold text-[#94a3b8]';
                        meta.textContent = row.period || row.date;
                        item.appendChild(meta);
                    }

                    list.appendChild(item);
                });
            };

            const openDepartmentModal = (departmentId) => {
                const selected = departments.find((department) => String(department.id) === String(departmentId));

                if (!selected) {
                    return;
                }

                modal.type.textContent = selected.type;
                modal.name.textContent = selected.name;
                modal.description.textContent = selected.description || 'Belum ada deskripsi yang tersedia.';

                appendListItems(
                    modal.prokerList,
                    selected.work_programs,
                    'Belum ada program kerja yang tersedia.',
                    'name'
                );
                appendListItems(
                    modal.agendaList,
                    selected.agendas,
                    'Belum ada agenda yang tersedia.',
                    'title'
                );

                departmentModal.classList.remove('hidden');
                departmentModal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            };

            const closeDepartmentModal = () => {
                departmentModal.classList.add('hidden');
                departmentModal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            };

            departmentFilterButtons.forEach((button) => {
                button.addEventListener('click', () => setFilter(button.dataset.departmentFilter));
            });

            departmentCards.forEach((card) => {
                card.addEventListener('click', () => openDepartmentModal(card.dataset.departmentId));
            });

            departmentOpenButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.stopPropagation();
                    openDepartmentModal(button.dataset.departmentId);
                });
            });

            modal.closeButtons.forEach((button) => {
                button.addEventListener('click', closeDepartmentModal);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !departmentModal.classList.contains('hidden')) {
                    closeDepartmentModal();
                }
            });

            setFilter('SEMUA');
        }
    </script>
</body>

</html>
