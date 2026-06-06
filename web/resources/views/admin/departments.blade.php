@extends('admin.layout', ['title' => 'Departemen & Biro', 'hideStubBadge' => true])

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-[#111827]">Daftar Departemen & Biro</h2>
            <p class="text-sm text-[#64748B]">Kelola profil dan narahubung tiap departemen untuk pendaftaran.</p>
        </div>
        <button onclick="openModal('createModal')" class="inline-flex items-center gap-2 rounded-xl bg-[#223872] px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#223872]/20 transition hover:bg-[#122452]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Departemen
        </button>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            {{ $errors->first() }}
        </div>
    @endif
    @if (session('success'))
        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($departments as $department)
            <div class="flex flex-col justify-between rounded-2xl border {{ $department->is_active ? 'border-[#D8E2F3] bg-white' : 'border-gray-200 bg-gray-50' }} p-5 shadow-sm transition hover:shadow-md">
                <div>
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-bold text-[#223872]">{{ $department->name }}</h3>
                        @if ($department->is_active)
                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[0.6rem] font-bold text-emerald-700">Aktif</span>
                        @else
                            <span class="rounded-full bg-gray-200 px-2 py-0.5 text-[0.6rem] font-bold text-gray-600">Nonaktif</span>
                        @endif
                    </div>
                    
                    <p class="mt-2 text-xs text-[#64748B] line-clamp-2" title="{{ $department->description }}">
                        {{ $department->description ?: 'Belum ada deskripsi.' }}
                    </p>

                    <div class="mt-3 rounded-xl bg-[#F4F7FF] p-3">
                        <p class="text-[0.65rem] font-bold uppercase tracking-wider text-[#94a3b8]">Narahubung (CP)</p>
                        <p class="mt-0.5 text-sm font-medium text-[#333333]">
                            {{ $department->contact_person ?: 'Belum diatur' }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.departments.manage', $department) }}" class="flex-1 rounded-xl bg-[#223872] py-2 text-center text-xs font-bold text-white transition hover:bg-[#122452]">
                        Detail
                    </a>
                    <button onclick='editDepartment(@json($department))' class="flex-1 rounded-xl bg-[#EEF4FF] py-2 text-xs font-bold text-[#223872] transition hover:bg-[#D8E2F3]">
                        Edit
                    </button>
                    <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" onsubmit="return confirm('Hapus departemen {{ $department->name }}?');" class="flex-none">
                        @csrf @method('DELETE')
                        <button type="submit" class="rounded-xl border border-red-100 bg-white px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-50" title="Hapus">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Create Modal --}}
    <div id="createModal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="relative w-full max-w-lg scale-95 transform rounded-2xl bg-white p-6 shadow-2xl transition-transform duration-300">
            <h3 class="mb-4 text-xl font-bold text-[#223872]">Tambah Departemen</h3>
            <form action="{{ route('admin.departments.post') }}" method="POST">
                @csrf
                <div class="grid gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Nama Departemen <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Narahubung (CP)</label>
                        <input type="text" name="contact_person" placeholder="Contoh: Budi - 0812345678" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="2" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-[0.7rem] font-bold uppercase tracking-wider text-gray-500">Bobot Aspek Personal (%) <span class="text-red-500">*</span></label>
                            <input type="number" name="personal_aspect_weight" value="50" min="0" max="100" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-[0.7rem] font-bold uppercase tracking-wider text-gray-500">Bobot Aspek Organisasi (%) <span class="text-red-500">*</span></label>
                            <input type="number" name="organizational_aspect_weight" value="50" min="0" max="100" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-[0.7rem] font-bold uppercase tracking-wider text-gray-500">Bobot Core Factor (%) <span class="text-red-500">*</span></label>
                            <input type="number" name="core_factor_weight" value="60" min="0" max="100" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-[0.7rem] font-bold uppercase tracking-wider text-gray-500">Bobot Secondary Factor (%) <span class="text-red-500">*</span></label>
                            <input type="number" name="secondary_factor_weight" value="40" min="0" max="100" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="is_active" id="create_is_active" value="1" checked class="h-4 w-4 rounded border-gray-300 text-[#4A90E2]">
                        <label for="create_is_active" class="text-sm font-semibold text-gray-700">Departemen Aktif</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('createModal')" class="rounded-xl px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#223872] px-6 py-2 text-sm font-bold text-white hover:bg-[#122452]">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="relative w-full max-w-lg scale-95 transform rounded-2xl bg-white p-6 shadow-2xl transition-transform duration-300">
            <h3 class="mb-4 text-xl font-bold text-[#223872]">Edit Departemen</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Nama Departemen <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="edit_name" required class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Narahubung (CP)</label>
                        <input type="text" name="contact_person" id="edit_contact_person" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi</label>
                        <textarea name="description" id="edit_description" rows="2" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-[0.7rem] font-bold uppercase tracking-wider text-gray-500">Bobot Aspek Personal <span class="text-red-500">*</span></label>
                            <input type="number" name="personal_aspect_weight" id="edit_personal_weight" min="0" max="100" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-[0.7rem] font-bold uppercase tracking-wider text-gray-500">Bobot Aspek Organisasi <span class="text-red-500">*</span></label>
                            <input type="number" name="organizational_aspect_weight" id="edit_org_weight" min="0" max="100" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-[0.7rem] font-bold uppercase tracking-wider text-gray-500">Bobot Core Factor <span class="text-red-500">*</span></label>
                            <input type="number" name="core_factor_weight" id="edit_core_weight" min="0" max="100" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-[0.7rem] font-bold uppercase tracking-wider text-gray-500">Bobot Secondary Factor <span class="text-red-500">*</span></label>
                            <input type="number" name="secondary_factor_weight" id="edit_secondary_weight" min="0" max="100" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="h-4 w-4 rounded border-gray-300 text-[#4A90E2]">
                        <label for="edit_is_active" class="text-sm font-semibold text-gray-700">Departemen Aktif</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editModal')" class="rounded-xl px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#223872] px-6 py-2 text-sm font-bold text-white hover:bg-[#122452]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        const modalContent = modal.querySelector('div.transform');
        modal.classList.remove('hidden');
        // trigger reflow
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const modalContent = modal.querySelector('div.transform');
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function editDepartment(dept) {
        document.getElementById('editForm').action = `/admin/departments/${dept.id}`;
        document.getElementById('edit_name').value = dept.name;
        document.getElementById('edit_contact_person').value = dept.contact_person || '';
        document.getElementById('edit_description').value = dept.description || '';
        document.getElementById('edit_personal_weight').value = dept.personal_aspect_weight;
        document.getElementById('edit_org_weight').value = dept.organizational_aspect_weight;
        document.getElementById('edit_core_weight').value = dept.core_factor_weight;
        document.getElementById('edit_secondary_weight').value = dept.secondary_factor_weight;
        document.getElementById('edit_is_active').checked = dept.is_active ? true : false;
        
        openModal('editModal');
    }
</script>
@endpush
