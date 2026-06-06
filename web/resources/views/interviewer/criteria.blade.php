@extends('interviewer.layout', ['title' => "Kriteria Departemen"])

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
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-[#111827]">Kelola Kriteria & Bobot</h1>
            <p class="text-sm text-[#64748B]">Departemen: {{ $department->name }}</p>
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

    {{-- Bobot Settings Form --}}
    <div class="rounded-2xl border border-[#D8E2F3] bg-white p-6 shadow-sm">
        <h2 class="mb-4 font-bold text-[#111827] flex items-center gap-2">
            <svg class="h-5 w-5 text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Pengaturan Bobot Perhitungan
        </h2>
        <form action="{{ route('interviewer.criteria.weights') }}" method="POST">
            @csrf @method('PUT')
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="rounded-xl bg-[#F4F7FF] p-4 border border-[#D8E2F3]">
                    <div class="mb-3 text-xs font-bold text-[#223872] uppercase tracking-wider">Aspek Utama (Total 100%)</div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Personal (%)</label>
                            <input type="number" step="0.01" name="personal_aspect_weight" value="{{ $department->personal_aspect_weight }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Organizational (%)</label>
                            <input type="number" step="0.01" name="organizational_aspect_weight" value="{{ $department->organizational_aspect_weight }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                        </div>
                    </div>
                </div>
                <div class="rounded-xl bg-[#F4F7FF] p-4 border border-[#D8E2F3]">
                    <div class="mb-3 text-xs font-bold text-[#223872] uppercase tracking-wider">Tipe Faktor (Total 100%)</div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Core Factor (%)</label>
                            <input type="number" step="0.01" name="core_factor_weight" value="{{ $department->core_factor_weight }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Secondary Factor (%)</label>
                            <input type="number" step="0.01" name="secondary_factor_weight" value="{{ $department->secondary_factor_weight }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#4A90E2] focus:ring-1 focus:ring-[#4A90E2]">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="rounded-xl bg-[#4A90E2] px-6 py-2 text-sm font-bold text-white transition hover:bg-[#357ABD]">Simpan Bobot</button>
            </div>
        </form>
    </div>

    {{-- Alert Reset --}}
    @if($isDirty)
    <div class="flex flex-col gap-4 rounded-xl border border-amber-200 bg-amber-50 p-5 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <svg class="h-6 w-6 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <h3 class="font-bold text-amber-800">Kriteria Telah Dimodifikasi</h3>
                <p class="mt-1 text-sm text-amber-700">Kriteria departemen ini telah diubah dari versi Default. Jika Anda ingin mengembalikan susunan kriteria seperti semula, silakan reset.</p>
            </div>
        </div>
        <form action="{{ route('interviewer.criteria.reset') }}" method="POST" onsubmit="return confirm('Hapus semua kriteria kustom ini dan kembalikan ke setelan default sistem? TINDAKAN INI TIDAK BISA DIBATALKAN.')">
            @csrf
            <button type="submit" class="shrink-0 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-amber-700">Reset ke Default</button>
        </form>
    </div>
    @endif

    {{-- Criteria Table --}}
    <div class="flex justify-end">
        <button @click="showAddModal = true" class="inline-flex items-center gap-2 rounded-xl bg-[#223872] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#122452]">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kriteria
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
                                    <form action="{{ route('interviewer.criteria.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus kriteria ini secara permanen?')">
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
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada kriteria di departemen ini. Silakan tambahkan atau reset ke Default.</td>
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
                <h3 class="font-black text-gray-900">Tambah Kriteria</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
            </div>
            <form action="{{ route('interviewer.criteria.post') }}" method="POST" class="p-6 flex flex-col gap-4">
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
                <h3 class="font-black text-gray-900">Edit Kriteria</h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
            </div>
            <form :action="`/interviewer/criteria/${editData.id}`" method="POST" class="p-6 flex flex-col gap-4">
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
                        <label class="block text-xs font-bold text-gray-500 mb-1">Aspek (Mutlak)</label>
                        <input type="text" disabled x-model="editData.aspect" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-500 cursor-not-allowed uppercase font-bold">
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
