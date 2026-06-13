<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" type="image/png">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Kelulusan — HIMATIK PNJ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F4F7FF] font-sans text-[#333333] antialiased">
    <!-- Navbar -->
    <header class="sticky top-0 z-20 border-b border-[#b7c7ff] bg-white/90 px-5 py-4 backdrop-blur sm:px-8">
        <div class="mx-auto flex max-w-5xl items-center justify-between">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 text-sm font-extrabold text-[#223872] transition hover:text-[#4A90E2]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <img src="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" alt="Logo HIMATIK" class="h-9 w-9 object-contain">
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-5 py-10 sm:px-8">
        <div class="text-center">
            <p class="text-xs font-extrabold uppercase tracking-widest text-[#4A90E2]">Pengumuman Resmi</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight text-[#111827] sm:text-5xl">
                Hasil Open Recruitment
            </h1>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-[#64748B] sm:text-base">
                Berikut adalah daftar nama kandidat yang dinyatakan LULUS dalam proses seleksi kepengurusan HIMATIK PNJ.
            </p>
        </div>

        <div class="mt-10">
            @if(!$isPublished)
                {{-- Empty state when not published --}}
                <div class="rounded-3xl border border-[#D8E2F3] bg-white p-10 text-center shadow-sm">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#F4F7FF]">
                        <svg class="h-10 w-10 text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="mt-5 text-xl font-black text-[#111827]">Pengumuman Belum Tersedia</h2>
                    <p class="mt-2 text-sm text-[#64748B]">Panitia masih memproses hasil seleksi akhir. Silakan kembali lagi nanti.</p>
                </div>
            @else
                <div class="rounded-2xl border border-[#D8E2F3] bg-white shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#223872] text-white">
                                <tr>
                                    <th class="px-6 py-4 font-bold">Nama Kandidat</th>
                                    <th class="px-6 py-4 font-bold hidden sm:table-cell">NIM</th>
                                    <th class="px-6 py-4 font-bold">Diterima Di</th>
                                    <th class="px-6 py-4 font-bold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#D8E2F3]">
                                @forelse($announcements as $announcement)
                                    <tr class="transition hover:bg-[#F4F7FF]">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-[#111827]">{{ $announcement->candidate->user->name }}</div>
                                            <div class="mt-1 text-xs text-[#64748B] sm:hidden">{{ $announcement->candidate->user->nim }}</div>
                                        </td>
                                        <td class="px-6 py-4 hidden sm:table-cell text-[#64748B]">
                                            {{ $announcement->candidate->user->nim }}
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-[#223872]">
                                            <div class="text-[#111827]">{{ ucfirst($announcement->candidate->candidate_type) }}</div>
                                            <div class="text-xs text-[#64748B] font-normal">{{ $announcement->assignedDepartment?->name ?? 'Staff Umum' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-[0.7rem] font-black uppercase tracking-wider text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Lulus
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-[#64748B]">Tidak ada data yang lulus.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($announcements->hasPages())
                        <div class="border-t border-[#D8E2F3] px-6 py-4 bg-gray-50">
                            {{ $announcements->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </main>

</body>
</html>