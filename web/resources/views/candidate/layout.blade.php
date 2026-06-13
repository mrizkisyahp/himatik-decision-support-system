<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" href="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" type="image/png">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Candidate HIMATIK PNJ' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4F7FF] font-sans text-[#333333] antialiased">
    @php
        $candidate = auth()->user()->candidate;
        $isFullyRegistered = $candidate && $candidate->candidate_signature_path && $candidate->parent_signature_path;

        $navItems = [
            ['label' => 'Dashboard', 'route' => 'candidate.dashboard'],
        ];

        if ($isFullyRegistered) {
            $navItems[] = ['label' => 'Jadwal Wawancara', 'route' => 'candidate.schedule.view'];
        }

        $navGroups = [
            [
                'label' => null,
                'items' => $navItems,
            ],
        ];
    @endphp

    <div class="min-h-screen lg:flex">
        <aside data-admin-sidebar
            class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-[#dce5f8] bg-white shadow-2xl shadow-[#223872]/10 transition lg:static lg:translate-x-0 lg:shadow-none">
            <div class="flex h-full flex-col">
                <div class="flex items-center justify-between border-b border-[#dce5f8] px-5 py-5">
                    <a href="{{ route('candidate.dashboard') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" alt="Logo HIMATIK" class="h-11 w-11 object-contain">
                        <div>
                            <p class="text-sm font-black text-[#223872]">HIMATIK PNJ</p>
                            <p class="text-xs font-semibold text-[#929aaa]">Candidate Panel</p>
                        </div>
                    </a>

                    <button type="button" data-admin-sidebar-close
                        class="rounded-xl p-2 text-[#64748b] transition hover:bg-[#F4F7FF] lg:hidden"
                        aria-label="Tutup menu">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto px-4 py-5">
                    @foreach ($navGroups as $group)
                        <div class="{{ !$loop->first ? 'mt-6' : '' }}">
                            @if ($group['label'])
                                <p class="mb-2 px-3 text-[0.68rem] font-black uppercase tracking-[0.18em] text-[#94a3b8]">
                                    {{ $group['label'] }}
                                </p>
                            @endif

                            <div class="space-y-1">
                                @foreach ($group['items'] as $item)
                                    @php $active = request()->routeIs($item['route']); @endphp
                                    <a href="{{ route($item['route']) }}"
                                        class="flex items-center justify-between rounded-2xl px-3 py-3 text-sm font-bold transition {{ $active ? 'bg-[#223872] text-white shadow-lg shadow-[#223872]/20' : 'text-[#64748b] hover:bg-[#F4F7FF] hover:text-[#223872]' }}">
                                        <span>{{ $item['label'] }}</span>
                                        @if ($active)
                                            <span class="h-2 w-2 rounded-full bg-[#4A90E2]"></span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </nav>

                <div class="border-t border-[#dce5f8] p-4 space-y-2">
                    <a href="{{ route('profile.edit') }}"
                        class="flex w-full items-center justify-center rounded-2xl border border-transparent bg-[#F4F7FF] px-4 py-3 text-sm font-black text-[#223872] transition hover:bg-white hover:border-[#dce5f8] hover:shadow-sm">
                        Pengaturan Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center justify-center rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-black text-red-600 transition hover:bg-red-100">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div data-admin-sidebar-backdrop class="fixed inset-0 z-30 hidden bg-gray-900/50 backdrop-blur-sm lg:hidden">
        </div>

        <div class="flex-1 flex flex-col min-w-0">
            <header
                class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-[#dce5f8] bg-white/80 px-5 backdrop-blur-md lg:px-8">
                <div class="flex items-center gap-4">
                    <button type="button" data-admin-sidebar-open
                        class="rounded-xl p-2 text-[#64748b] transition hover:bg-[#F4F7FF] lg:hidden">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-lg font-black tracking-tight text-[#111827] sm:text-xl">{{ $title ?? 'Dashboard' }}
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-bold text-[#111827]">{{ auth()->user()->name ?? 'Kandidat' }}</p>
                            <p class="text-[0.7rem] font-bold uppercase tracking-wider text-[#64748b]">Candidate</p>
                        </div>
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#223872] text-sm font-black text-white shadow-md shadow-[#223872]/20">
                            {{ substr(auth()->user()->name ?? 'K', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="px-5 py-6 lg:px-8 lg:py-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.querySelector('[data-admin-sidebar]');
        const sidebarBackdrop = document.querySelector('[data-admin-sidebar-backdrop]');
        const openSidebar = document.querySelector('[data-admin-sidebar-open]');
        const closeSidebar = document.querySelector('[data-admin-sidebar-close]');

        const setSidebarOpen = (isOpen) => {
            sidebar?.classList.toggle('-translate-x-full', !isOpen);
            sidebarBackdrop?.classList.toggle('hidden', !isOpen);
            document.body.classList.toggle('overflow-hidden', isOpen);
        };

        openSidebar?.addEventListener('click', () => setSidebarOpen(true));
        closeSidebar?.addEventListener('click', () => setSidebarOpen(false));
        sidebarBackdrop?.addEventListener('click', () => setSidebarOpen(false));
    </script>
    @stack('scripts')
</body>

</html>