<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Identitas Diri - HIMATIK PNJ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4F7FF] font-sans text-[#333333] antialiased">
    <main class="flex min-h-screen justify-center px-0 py-0 sm:items-center sm:px-5 sm:py-8">
        <section class="flex min-h-screen w-full max-w-[25.5rem] flex-col bg-[#F4F7FF] sm:min-h-0 sm:overflow-hidden sm:rounded-[1.4rem] sm:border sm:border-[#dce5f8] sm:shadow-[0_16px_40px_rgba(34,56,114,0.08)]">
            <header class="border-b border-[#b7c7ff] bg-white/86 px-5 pb-5 pt-12 backdrop-blur sm:px-7 sm:pt-7">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 text-xs font-extrabold text-red-500 transition hover:text-red-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M18 12H9m9 0-3-3m3 3-3 3" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </header>

            <form action="{{ route('candidate.register.post') }}" method="POST" class="flex flex-1 flex-col px-5 py-5 sm:px-7">
                @csrf
                <input type="hidden" name="candidate_type" value="{{ old('candidate_type', $candidateType ?? 'staff') }}">

                <div>
                    <h1 class="text-[1.7rem] font-black leading-tight tracking-tight text-[#111827]">
                        Informasi Identitas Diri
                    </h1>
                    <p class="mt-2 text-[0.8rem] leading-5 text-[#929aaa]">
                        Pastikan informasi identitas dan kontak yang kamu masukkan valid serta aktif.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mt-5 space-y-4">
                    <div class="space-y-2">
                        <label for="nama" class="block text-xs font-semibold text-[#333333]">Nama Lengkap</label>
                        <input id="nama" name="nama" type="text" value="{{ old('nama', auth()->user()?->name) }}" placeholder="Masukkan Nama Lengkap" autocomplete="name" class="h-11 w-full rounded-xl border border-[#aeb8cc] bg-transparent px-3 text-sm outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15" required>
                    </div>

                    <div class="space-y-2">
                        <label for="nickname" class="block text-xs font-semibold text-[#333333]">Nama Panggilan</label>
                        <input id="nickname" name="nickname" type="text" value="{{ old('nickname') }}" placeholder="Masukkan Nama Panggilan" class="h-11 w-full rounded-xl border border-[#aeb8cc] bg-transparent px-3 text-sm outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15" required>
                    </div>

                    <div class="space-y-2">
                        <label for="nim" class="block text-xs font-semibold text-[#333333]">Nomor Induk Mahasiswa</label>
                        <input id="nim" name="nim" type="text" value="{{ old('nim') }}" inputmode="numeric" maxlength="10" placeholder="Masukkan NIM" class="h-11 w-full rounded-xl border border-[#aeb8cc] bg-transparent px-3 text-sm outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15" required>
                    </div>

                    <div class="space-y-2">
                        <label for="prodi" class="block text-xs font-semibold text-[#333333]">Program Studi</label>
                        <select id="prodi" name="prodi" class="h-11 w-full rounded-xl border border-[#aeb8cc] bg-transparent px-3 text-sm text-[#929aaa] outline-none transition focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15" required>
                            <option value="">Pilih Program Studi di TIK</option>
                            @foreach(['Teknik Informatika', 'Teknik Multimedia dan Jaringan', 'Teknik Multimedia dan Digital'] as $prodi)
                                <option value="{{ $prodi }}" @selected(old('prodi') === $prodi)>{{ $prodi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="kelas" class="block text-xs font-semibold text-[#333333]">Kelas</label>
                        <input id="kelas" name="kelas" type="text" value="{{ old('kelas') }}" placeholder="Masukkan Kelas" class="h-11 w-full rounded-xl border border-[#aeb8cc] bg-transparent px-3 text-sm outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15" required>
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="block text-xs font-semibold text-[#333333]">Nomor Telepon</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="Masukkan Nomor Telepon (cth. 081...)" class="h-11 w-full rounded-xl border border-[#aeb8cc] bg-transparent px-3 text-sm outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15" required>
                    </div>

                    <div class="space-y-2">
                        <label for="address" class="block text-xs font-semibold text-[#333333]">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="5" placeholder="Masukkan Alamat Lengkap" class="w-full rounded-xl border border-[#aeb8cc] bg-transparent px-3 py-3 text-sm outline-none transition placeholder:text-[#929aaa] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15" required>{{ old('address') }}</textarea>
                    </div>
                </div>

                <div class="mt-auto pt-5">
                    <button type="submit" class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-[#223872] px-5 text-sm font-semibold text-white transition hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20">
                        Selesai
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12.75 11.25 15 15 9.75" />
                        </svg>
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>
