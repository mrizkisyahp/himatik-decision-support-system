@extends('admin.layout')

@section('content')
<div x-data="{
    addOpen: false,
    addRole: 'admin',

    editOpen: false,
    editId: '',
    editName: '',
    editEmail: '',
    editRole: 'admin',
    editDeptId: '',

    openAdd() {
        this.addRole = 'admin';
        this.addOpen = true;
    },
    openEdit(id, name, email, role, deptId) {
        this.editId = id;
        this.editName = name;
        this.editEmail = email;
        this.editRole = role;
        this.editDeptId = deptId;
        this.editOpen = true;
    }
}">

{{-- Header --}}
<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <h2 class="text-2xl font-black text-[#111827]">Kelola Akun</h2>
        <p class="text-sm text-gray-500">Manajemen akses dan profil pengguna sistem.</p>
    </div>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        {{-- Filter Role --}}
        <div class="relative">
            <select onchange="window.location.href=this.value" class="appearance-none rounded-2xl border border-[#D8E2F3] bg-white px-4 py-2.5 pr-10 text-sm font-semibold text-[#111827] shadow-sm outline-none transition-all hover:border-[#4A90E2] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10">
                <option value="{{ route('admin.accounts') }}" {{ !$currentRole ? 'selected' : '' }}>Semua Role</option>
                <option value="{{ route('admin.accounts', ['role' => 'admin']) }}" {{ $currentRole === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="{{ route('admin.accounts', ['role' => 'interviewer']) }}" {{ $currentRole === 'interviewer' ? 'selected' : '' }}>Interviewer</option>
                <option value="{{ route('admin.accounts', ['role' => 'candidate']) }}" {{ $currentRole === 'candidate' ? 'selected' : '' }}>Candidate</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#64748b]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <button @click="openAdd()" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#223872] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#223872]/20 transition-all hover:bg-[#1a2b58] active:scale-95">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Akun
        </button>
    </div>
</div>

@if(session('success'))
<div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    </div>
</div>
@endif

{{-- Table --}}
<div class="overflow-hidden rounded-3xl border border-[#D8E2F3] bg-white shadow-xl shadow-[#223872]/5">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#F4F7FF] text-xs font-bold uppercase tracking-wider text-[#64748b]">
                <tr>
                    <th scope="col" class="px-6 py-4">Nama</th>
                    <th scope="col" class="px-6 py-4">Email</th>
                    <th scope="col" class="px-6 py-4 text-center">Role</th>
                    <th scope="col" class="px-6 py-4">Departemen (Khusus Interviewer)</th>
                    <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#D8E2F3]">
                @forelse($users as $user)
                <tr class="transition-colors hover:bg-[#F4F7FF]/50">
                    <td class="whitespace-nowrap px-6 py-4 font-bold text-[#111827]">
                        {{ $user->name }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-[#64748b]">
                        {{ $user->email }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-center">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center rounded-full bg-purple-50 px-2.5 py-0.5 text-xs font-bold text-purple-700 ring-1 ring-inset ring-purple-600/20">Admin</span>
                        @elseif($user->role === 'interviewer')
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-600/20">Interviewer</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-bold text-gray-600 ring-1 ring-inset ring-gray-500/20">Candidate</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-[#64748b]">
                        @if($user->role === 'interviewer')
                            @if($user->department)
                                <span class="font-semibold text-[#111827]">{{ $user->department->name }}</span>
                            @else
                                <span class="italic text-red-500">Belum diatur</span>
                            @endif
                        @else
                            <span class="text-gray-300">-</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button
                                @click="openEdit({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->role }}', '{{ $user->department_id }}')"
                                class="inline-flex items-center justify-center rounded-xl p-2 text-blue-600 transition-colors hover:bg-blue-50 hover:text-blue-700"
                                title="Edit Akun">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.accounts.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-xl p-2 text-red-600 transition-colors hover:bg-red-50 hover:text-red-700" title="Hapus Akun">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-[#64748b]">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="h-8 w-8 text-[#94a3b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="font-medium">Tidak ada akun yang ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Akun --}}
<div
    x-show="addOpen"
    @keydown.escape.window="addOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;">
    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 z-0 bg-[#06122d]/45" @click="addOpen = false"></div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
        <div
            x-show="addOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
            class="relative z-10 inline-block w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:align-middle">

            <div class="border-b border-[#D8E2F3] bg-[#F4F7FF] px-6 py-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-black text-[#111827]">Tambah Akun Baru</h3>
                    <button type="button" @click="addOpen = false" class="rounded-xl p-2 text-[#64748b] hover:bg-white hover:text-[#111827] transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form action="{{ route('admin.accounts.store') }}" method="POST">
                @csrf
                <div class="bg-white px-6 py-6">
                    <div class="space-y-5">
                        <div>
                            <label class="mb-1 block text-xs font-bold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required class="w-full rounded-xl border border-[#D8E2F3] bg-white px-4 py-2.5 text-sm font-semibold text-[#111827] outline-none transition-all focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10" placeholder="Masukkan nama lengkap">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required class="w-full rounded-xl border border-[#D8E2F3] bg-white px-4 py-2.5 text-sm font-semibold text-[#111827] outline-none transition-all focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10" placeholder="nama@email.com">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-gray-700">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required minlength="8" class="w-full rounded-xl border border-[#D8E2F3] bg-white px-4 py-2.5 text-sm font-semibold text-[#111827] outline-none transition-all focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10" placeholder="Minimal 8 karakter">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-gray-700">Role <span class="text-red-500">*</span></label>
                            <select name="role" x-model="addRole" required class="w-full rounded-xl border border-[#D8E2F3] bg-white px-4 py-2.5 text-sm font-semibold text-[#111827] outline-none transition-all focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10">
                                <option value="admin">Admin</option>
                                <option value="interviewer">Interviewer</option>
                                <option value="candidate">Candidate</option>
                            </select>
                        </div>
                        <div x-show="addRole === 'interviewer'">
                            <label class="mb-1 block text-xs font-bold text-gray-700">Penempatan Departemen/Biro <span class="text-red-500">*</span></label>
                            <select name="department_id" :required="addRole === 'interviewer'" class="w-full rounded-xl border border-[#D8E2F3] bg-white px-4 py-2.5 text-sm font-semibold text-[#111827] outline-none transition-all focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Wajib dipilih jika role adalah Interviewer.</p>
                        </div>
                    </div>
                </div>
                <div class="border-t border-[#D8E2F3] bg-[#F4F7FF] px-6 py-4">
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="addOpen = false" class="rounded-xl border border-[#D8E2F3] bg-white px-5 py-2.5 text-sm font-bold text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-[#4A90E2] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#4A90E2]/20 transition-all hover:bg-[#357ABD] active:scale-95">
                            Simpan Akun
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Akun --}}
<div
    x-show="editOpen"
    @keydown.escape.window="editOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;">
    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 z-0 bg-[#06122d]/45" @click="editOpen = false"></div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
        <div
            x-show="editOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
            class="relative z-10 inline-block w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:align-middle">

            <div class="border-b border-[#D8E2F3] bg-[#F4F7FF] px-6 py-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-black text-[#111827]">Edit Akun</h3>
                    <button type="button" @click="editOpen = false" class="rounded-xl p-2 text-[#64748b] hover:bg-white hover:text-[#111827] transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form :action="`/admin/accounts/${editId}`" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-6 py-6">
                    <div class="space-y-5">
                        <div>
                            <label class="mb-1 block text-xs font-bold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="editName" required class="w-full rounded-xl border border-[#D8E2F3] bg-white px-4 py-2.5 text-sm font-semibold text-[#111827] outline-none transition-all focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" x-model="editEmail" required class="w-full rounded-xl border border-[#D8E2F3] bg-white px-4 py-2.5 text-sm font-semibold text-[#111827] outline-none transition-all focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-gray-700">Password Baru</label>
                            <input type="password" name="password" minlength="8" class="w-full rounded-xl border border-[#D8E2F3] bg-white px-4 py-2.5 text-sm font-semibold text-[#111827] outline-none transition-all focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10" placeholder="Kosongkan jika tidak ingin diubah">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-gray-700">Role <span class="text-red-500">*</span></label>
                            <select name="role" x-model="editRole" required class="w-full rounded-xl border border-[#D8E2F3] bg-white px-4 py-2.5 text-sm font-semibold text-[#111827] outline-none transition-all focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10">
                                <option value="admin">Admin</option>
                                <option value="interviewer">Interviewer</option>
                                <option value="candidate">Candidate</option>
                            </select>
                        </div>
                        <div x-show="editRole === 'interviewer'">
                            <label class="mb-1 block text-xs font-bold text-gray-700">Penempatan Departemen/Biro <span class="text-red-500">*</span></label>
                            <select name="department_id" x-model="editDeptId" :required="editRole === 'interviewer'" class="w-full rounded-xl border border-[#D8E2F3] bg-white px-4 py-2.5 text-sm font-semibold text-[#111827] outline-none transition-all focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" :selected="editDeptId == {{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Wajib dipilih jika role adalah Interviewer.</p>
                        </div>
                    </div>
                </div>
                <div class="border-t border-[#D8E2F3] bg-[#F4F7FF] px-6 py-4">
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="editOpen = false" class="rounded-xl border border-[#D8E2F3] bg-white px-5 py-2.5 text-sm font-bold text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-[#4A90E2] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#4A90E2]/20 transition-all hover:bg-[#357ABD] active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
@endsection
