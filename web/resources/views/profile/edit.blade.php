@extends($layout, ['title' => 'Pengaturan Profil'])

@section('content')
<div class="space-y-8">
    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Informasi Profil -->
        <section class="rounded-[2rem] bg-white border border-[#E2E8F0] p-8 shadow-sm">
            <div class="mb-6">
                <h2 class="text-xl font-black text-[#0F172A]">Informasi Identitas Diri</h2>
                <p class="text-sm font-medium text-[#64748B] mt-1">Perbarui informasi profil dan identitas diri Anda.</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div class="space-y-2">
                    <label for="name" class="block text-xs font-bold text-[#64748B] uppercase tracking-wider">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="h-11 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-sm font-medium text-[#0F172A] outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" required>
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-xs font-bold text-[#64748B] uppercase tracking-wider">Email <span class="lowercase normal-case font-medium text-[#94A3B8]">(Tidak dapat diubah)</span></label>
                    <input id="email" type="email" value="{{ $user->email }}" class="h-11 w-full rounded-xl border border-[#E2E8F0] bg-gray-100 px-4 text-sm font-medium text-[#64748B] outline-none cursor-not-allowed" disabled>
                </div>

                @if($user->role === 'candidate')
                    <div class="space-y-2">
                        <label for="nickname" class="block text-xs font-bold text-[#64748B] uppercase tracking-wider">Nama Panggilan</label>
                        <input id="nickname" name="nickname" type="text" value="{{ old('nickname', $user->nickname) }}" class="h-11 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-sm font-medium text-[#0F172A] outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" required>
                    </div>

                    <div class="space-y-2">
                        <label for="nim" class="block text-xs font-bold text-[#64748B] uppercase tracking-wider">NIM</label>
                        <input id="nim" name="nim" type="text" value="{{ old('nim', $user->nim) }}" class="h-11 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-sm font-medium text-[#0F172A] outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" required>
                    </div>

                    <div class="space-y-2">
                        <label for="prodi" class="block text-xs font-bold text-[#64748B] uppercase tracking-wider">Program Studi</label>
                        <select id="prodi" name="prodi" class="h-11 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-sm font-medium text-[#0F172A] outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" required>
                            <option value="">Pilih Program Studi</option>
                            @foreach(['Teknik Informatika', 'Teknik Multimedia dan Jaringan', 'Teknik Multimedia dan Digital'] as $prodi)
                                <option value="{{ $prodi }}" @selected(old('prodi', $user->prodi) === $prodi)>{{ $prodi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="kelas" class="block text-xs font-bold text-[#64748B] uppercase tracking-wider">Kelas</label>
                        <input id="kelas" name="kelas" type="text" value="{{ old('kelas', $user->kelas) }}" class="h-11 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-sm font-medium text-[#0F172A] outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" required>
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="block text-xs font-bold text-[#64748B] uppercase tracking-wider">No. WhatsApp/Telepon</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" class="h-11 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-sm font-medium text-[#0F172A] outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" required>
                    </div>

                    <div class="space-y-2">
                        <label for="address" class="block text-xs font-bold text-[#64748B] uppercase tracking-wider">Alamat</label>
                        <textarea id="address" name="address" rows="3" class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 py-3 text-sm font-medium text-[#0F172A] outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" required>{{ old('address', $user->address) }}</textarea>
                    </div>
                @endif

                <div class="pt-4">
                    <button type="submit" class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-blue-600 px-6 text-sm font-bold text-white transition hover:bg-blue-700 shadow-sm hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>

        <!-- Ubah Password -->
        <section class="rounded-[2rem] bg-white border border-[#E2E8F0] p-8 shadow-sm">
            <div class="mb-6">
                <h2 class="text-xl font-black text-[#0F172A]">Ubah Password</h2>
                <p class="text-sm font-medium text-[#64748B] mt-1">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.</p>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="current_password" class="block text-xs font-bold text-[#64748B] uppercase tracking-wider">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password" class="h-11 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-sm font-medium text-[#0F172A] outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" required>
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-xs font-bold text-[#64748B] uppercase tracking-wider">Password Baru</label>
                    <input id="password" name="password" type="password" class="h-11 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-sm font-medium text-[#0F172A] outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" required>
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-xs font-bold text-[#64748B] uppercase tracking-wider">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="h-11 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-sm font-medium text-[#0F172A] outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" required>
                </div>

                <div class="pt-4">
                    <button type="submit" class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-[#0F172A] px-6 text-sm font-bold text-white transition hover:bg-gray-800 shadow-sm hover:-translate-y-0.5">
                        Ubah Password
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection
