<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" href="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" type="image/png">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preferensi Departemen - HIMATIK PNJ</title>
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
    <main class="mx-auto flex w-full max-w-3xl flex-1 flex-col px-5 py-8 sm:px-8 sm:py-12">
        <div class="mb-8">
            <!-- Back Link -->
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-sm font-bold text-[#223872] transition hover:text-[#4A90E2]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="w-full">
            <h1 class="text-3xl font-black leading-tight tracking-tight text-[#111827] sm:text-4xl">
                Preferensi Departemen/Biro & Evaluasi Diri
            </h1>
            <p class="mt-4 text-sm leading-relaxed text-[#64748b] sm:text-base">
                Tentukan pilihanmu dan jabarkan alasan serta langkah konkretmu secara jujur. Masih bingung bidang yang ingin dipilih? Klik link booklet ini untuk ketahui lebih lanjut: 
                <a href="#" class="font-bold text-[#223872] underline transition hover:text-[#4A90E2]">Link Booklet</a>
            </p>

            @if (session('error'))
                <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('candidate.preferences.post') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                <input type="hidden" name="open_recruitment_id" value="{{ request('open_recruitment_id', $oprec->id ?? '') }}">

                <!-- Choice 1 -->
                <div class="space-y-2">
                    <label for="first_choice_department_id" class="block text-sm font-bold text-[#333333]">Pilihan 1 <span class="text-red-500">*</span></label>
                    <select id="first_choice_department_id" name="first_choice_department_id" class="h-12 w-full rounded-xl border border-[#dce5f8] bg-white px-4 text-sm font-medium text-[#333333] outline-none transition focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15" required>
                        <option value="" disabled selected>Pilih Biro atau Departemen HIMATIK PNJ</option>
                        @foreach ($departments ?? [] as $dept)
                            <option value="{{ $dept->id }}" {{ old('first_choice_department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Choice 2 -->
                <div class="space-y-2">
                    <label for="second_choice_department_id" class="block text-sm font-bold text-[#333333]">Pilihan 2 (Opsional)</label>
                    <select id="second_choice_department_id" name="second_choice_department_id" class="h-12 w-full rounded-xl border border-[#dce5f8] bg-white px-4 text-sm font-medium text-[#333333] outline-none transition focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15">
                        <option value="" selected>Pilih Biro atau Departemen HIMATIK PNJ</option>
                        @foreach ($departments ?? [] as $dept)
                            <option value="{{ $dept->id }}" {{ old('second_choice_department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Reason -->
                <div class="space-y-2">
                    <label for="reason_for_department" class="block text-sm font-bold text-[#333333]">Alasan Memilih Biro atau Departemen <span class="text-red-500">*</span></label>
                    <textarea id="reason_for_department" name="reason_for_department" rows="4" placeholder="Masukkan Alasan Memilih Biro atau Departemen (Pilihan 1 Maupun Pilihan 2 Jika Ada)" class="w-full resize-none rounded-xl border border-[#dce5f8] bg-white p-4 text-sm font-medium text-[#333333] outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15" required>{{ old('reason_for_department') }}</textarea>
                </div>

                <!-- Weaknesses -->
                <div class="space-y-2">
                    <label for="weaknesses" class="block text-sm font-bold text-[#333333]">Deskripsikan Kekurangan Kamu <span class="text-red-500">*</span></label>
                    <textarea id="weaknesses" name="weaknesses" rows="4" placeholder="Masukkan Kekuranganmu" class="w-full resize-none rounded-xl border border-[#dce5f8] bg-white p-4 text-sm font-medium text-[#333333] outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15" required>{{ old('weaknesses') }}</textarea>
                </div>

                <!-- Concrete Steps -->
                <div class="space-y-2">
                    <label for="concrete_steps_if_chosen" class="block text-sm font-bold text-[#333333]">Langkah Konkret Apa yang Kamu Ambil Jika Terpilih <span class="text-red-500">*</span></label>
                    <textarea id="concrete_steps_if_chosen" name="concrete_steps_if_chosen" rows="4" placeholder="Masukkan Langkah Konkretmu" class="w-full resize-none rounded-xl border border-[#dce5f8] bg-white p-4 text-sm font-medium text-[#333333] outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15" required>{{ old('concrete_steps_if_chosen') }}</textarea>
                </div>

                <!-- Bottom Buttons -->
                <div class="flex flex-col gap-4 pt-6 sm:flex-row sm:items-center">
                    <a href="javascript:history.back()" class="inline-flex h-12 w-full flex-1 items-center justify-center gap-2 rounded-xl border-2 border-[#dce5f8] bg-transparent px-8 text-sm font-bold text-[#64748b] transition hover:border-[#223872] hover:text-[#223872] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20 sm:w-auto">
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
                </div>
            </form>
        </div>
    </main>
</body>

</html>
