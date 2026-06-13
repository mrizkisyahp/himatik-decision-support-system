<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" href="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" type="image/png">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mulai Daftar - HIMATIK PNJ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4F7FF] font-sans text-[#333333] antialiased flex flex-col">
    <!-- Navbar / Top Bar -->
    <nav class="border-b border-[#dce5f8] bg-white">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-5 sm:px-8">
            <!-- Left: Logo & Title -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" alt="Logo HIMATIK" class="h-8 w-auto">
                <span class="text-lg font-black tracking-tight text-[#223872] hidden sm:block">HIMATIK PNJ</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="mx-auto flex w-full max-w-4xl flex-1 flex-col px-5 py-8 sm:px-8 sm:py-12">
        <div class="mb-8">
            <!-- Back Link -->
            <a href="{{ route('candidate.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#223872] transition hover:text-[#4A90E2]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="w-full">
            <h1 class="text-3xl font-black leading-tight tracking-tight text-[#111827] sm:text-4xl lg:text-5xl">
                Mulai Daftar Menjadi {{ isset($oprec) && $oprec->candidate_type === 'bph' ? 'BPH' : 'Staff' }} HIMATIK PNJ
            </h1>
            <p class="mt-4 text-base leading-relaxed text-[#64748b] sm:text-lg">
                Daftar Menjadi {{ isset($oprec) && $oprec->candidate_type === 'bph' ? 'BPH' : 'Staff' }} HIMATIK PNJ untuk meningkatkan pengalamanmu
            </p>

            @if (session('error'))
                <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('candidate.apply.post') }}" method="POST" class="mt-12 sm:mt-16">
                @csrf
                <input type="hidden" name="open_recruitment_id" value="{{ request('open_recruitment_id', $oprec->id ?? '') }}">
                
                <button type="submit" class="inline-flex h-14 w-full sm:w-auto sm:min-w-[200px] items-center justify-center gap-2 rounded-xl bg-[#223872] px-8 text-base font-bold text-white transition hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    Berikutnya
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        </div>
    </main>
</body>

</html>
