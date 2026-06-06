@extends('admin.layout', ['title' => 'Default Criteria (DSS)', 'hideStubBadge' => true])

@section('content')
<div class="space-y-8" x-data="{
    showAddModal: false,
    showEditModal: false,
    editData: {},
    openEditModal(criterion) {
        this.editData = criterion;
        this.showEditModal = true;
    }
}">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-[#111827]">Default Criteria</h1>
            <p class="text-sm text-[#64748B]">Kelola setelan kriteria awal untuk seluruh departemen & biro.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex justify-end mb-4">
        <button @click="showAddModal = true" class="inline-flex items-center gap-2 rounded-xl bg-[#223872] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#122452]">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Default Kriteria
        </button>
    </div>

    <div class="rounded-2xl border border-[#D8E2F3] bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-[#64748B] border-b border-[#D8E2F3]">
                    <tr>
                        <th class="px-6 py-4 font-bold">Code</th>
                        <th class="px-6 py-4 font-bold">Kriteria</th>
                        <th class="px-6 py-4 font-bold">Aspek</th>
                        <th class="px-6 py-4 font-bold">Tipe (CF/SF)</th>
                        <th class="px-6 py-4 font-bold text-center">Target Skor</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8E2F3]">
                    @forelse($criteria as $c)
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono text-gray-500">{{ $c->code ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#111827]">{{ $c->name }}</div>
                                <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $c->description ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold {{ $c->aspect === 'personal' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($c->aspect) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold {{ $c->type === 'core' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $c->type === 'core' ? 'Core Factor' : 'Secondary Factor' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-black text-lg text-[#223872]">{{ $c->target_score }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click='openEditModal(@json($c))' class="rounded-lg bg-gray-100 p-2 text-gray-600 transition hover:bg-gray-200 hover:text-gray-900">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.default-criteria.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus default kriteria ini? Ini tidak akan menghapus kriteria yang sudah dicopy di masing-masing departemen.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-red-50 p-2 text-red-600 transition hover:bg-red-100 hover:text-red-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada default criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm px-4" x-cloak style="display: none;">
        <div @click.away="showAddModal = false" class="w-full max-w-lg rounded-2xl bg-white shadow-xl overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <h3 class="font-black text-gray-900">Tambah Default Criteria</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
            </div>
            <form action="{{ route('admin.default-criteria.post') }}" method="POST" class="p-6 flex flex-col gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Kriteria <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kode (Opsional)</label>
                        <input type="text" name="code" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Target Skor <span class="text-red-500">*</span></label>
                        <select name="target_score" required class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3" selected>3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Aspek Mutlak <span class="text-red-500">*</span></label>
                        <select name="aspect" required class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                            <option value="personal">Personal</option>
                            <option value="organizational">Organizational</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tipe Faktor <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                            <option value="core">Core Factor</option>
                            <option value="secondary">Secondary Factor</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]"></textarea>
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" @click="showAddModal = false" class="rounded-xl px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#223872] px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-[#122452]">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm px-4" x-cloak style="display: none;">
        <div @click.away="showEditModal = false" class="w-full max-w-lg rounded-2xl bg-white shadow-xl overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4 flex justify-between items-center">
                <h3 class="font-black text-gray-900">Edit Default Criteria</h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
            </div>
            <form :action="`/admin/default-criteria/${editData.id}`" method="POST" class="p-6 flex flex-col gap-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Kriteria <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kode</label>
                        <input type="text" name="code" x-model="editData.code" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Target Skor <span class="text-red-500">*</span></label>
                        <select name="target_score" x-model="editData.target_score" required class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Aspek <span class="text-red-500">*</span></label>
                        <select name="aspect" x-model="editData.aspect" required class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                            <option value="personal">Personal</option>
                            <option value="organizational">Organizational</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tipe Faktor <span class="text-red-500">*</span></label>
                        <select name="type" x-model="editData.type" required class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                            <option value="core">Core Factor</option>
                            <option value="secondary">Secondary Factor</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" x-model="editData.description" rows="2" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]"></textarea>
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" @click="showEditModal = false" class="rounded-xl px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#223872] px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-[#122452]">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
