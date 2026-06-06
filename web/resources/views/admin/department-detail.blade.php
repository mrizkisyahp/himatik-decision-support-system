@extends('admin.layout', ['title' => 'Detail Departemen: ' . $department->name, 'hideStubBadge' => true])

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.departments') }}" class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#D8E2F3] bg-white text-[#64748B] transition hover:bg-[#F4F7FF] hover:text-[#223872]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-[#111827]">{{ $department->name }}</h2>
                <p class="text-sm text-[#64748B]">Narahubung: <span class="font-bold">{{ $department->contact_person ?: '-' }}</span></p>
            </div>
        </div>
        <div class="flex gap-2">
            <button onclick="openModal('addAgendaModal')" class="inline-flex items-center gap-2 rounded-xl border border-[#223872] bg-white px-4 py-2 text-sm font-bold text-[#223872] transition hover:bg-[#EEF4FF]">
                + Tambah Agenda
            </button>
            <button onclick="openModal('addProkerModal')" class="inline-flex items-center gap-2 rounded-xl bg-[#223872] px-4 py-2 text-sm font-bold text-white shadow-lg shadow-[#223872]/20 transition hover:bg-[#122452]">
                + Tambah Proker
            </button>
        </div>
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

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- WORK PROGRAMS --}}
        <section class="rounded-2xl border border-[#D8E2F3] bg-white shadow-sm">
            <div class="border-b border-[#D8E2F3] px-5 py-4 bg-[#F4F7FF] rounded-t-2xl">
                <h3 class="font-bold text-[#223872]">Daftar Program Kerja</h3>
            </div>
            <div class="p-5">
                @forelse($department->workPrograms->sortBy('sort_order') as $proker)
                    <div class="mb-4 last:mb-0 rounded-xl border border-[#D8E2F3] p-4 transition hover:border-[#4A90E2]/50">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h4 class="font-bold text-[#111827]">{{ $proker->name }}</h4>
                                <p class="text-xs text-[#64748B] mt-1">{{ $proker->period ?: 'Periode tidak ditentukan' }}</p>
                                <p class="text-sm text-[#333333] mt-2">{{ $proker->description ?: 'Tidak ada deskripsi.' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick='editProker(@json($proker))' class="text-xs font-bold text-[#4A90E2] hover:underline">Edit</button>
                                <form action="{{ route('admin.departments.work-programs.destroy', [$department, $proker]) }}" method="POST" onsubmit="return confirm('Hapus program kerja ini?');">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-bold text-red-500 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-sm text-[#64748B]">Belum ada Program Kerja.</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- AGENDAS --}}
        <section class="rounded-2xl border border-[#D8E2F3] bg-white shadow-sm">
            <div class="border-b border-[#D8E2F3] px-5 py-4 bg-[#F4F7FF] rounded-t-2xl">
                <h3 class="font-bold text-[#223872]">Daftar Agenda</h3>
            </div>
            <div class="p-5">
                @forelse($department->agendas->sortBy('sort_order') as $agenda)
                    <div class="mb-4 last:mb-0 rounded-xl border border-[#D8E2F3] p-4 transition hover:border-[#4A90E2]/50">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h4 class="font-bold text-[#111827]">{{ $agenda->title }}</h4>
                                <div class="mt-1 flex flex-wrap gap-x-3 gap-y-1 text-xs text-[#64748B]">
                                    <span class="flex items-center gap-1">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $agenda->start_date?->format('d M Y') }} 
                                        {{ $agenda->end_date && $agenda->end_date != $agenda->start_date ? ' - ' . $agenda->end_date->format('d M Y') : '' }}
                                    </span>
                                    @if($agenda->location)
                                        <span class="flex items-center gap-1">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $agenda->location }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-[#333333] mt-2">{{ $agenda->description ?: 'Tidak ada deskripsi.' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick='editAgenda(@json($agenda))' class="text-xs font-bold text-[#4A90E2] hover:underline">Edit</button>
                                <form action="{{ route('admin.departments.agendas.destroy', [$department, $agenda]) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?');">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-bold text-red-500 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-sm text-[#64748B]">Belum ada Agenda.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- MODALS FOR PROKER --}}
    <div id="addProkerModal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="relative w-full max-w-lg scale-95 transform rounded-2xl bg-white p-6 shadow-2xl transition-transform duration-300">
            <h3 class="mb-4 text-xl font-bold text-[#223872]">Tambah Program Kerja</h3>
            <form action="{{ route('admin.departments.work-programs.store', $department) }}" method="POST">
                @csrf
                <div class="grid gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Nama Proker <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Periode Pelaksanaan</label>
                        <input type="text" name="period" placeholder="Contoh: September - Oktober" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20"></textarea>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Urutan (Sort Order)</label>
                        <input type="number" name="sort_order" value="0" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addProkerModal')" class="rounded-xl px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#223872] px-6 py-2 text-sm font-bold text-white hover:bg-[#122452]">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editProkerModal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="relative w-full max-w-lg scale-95 transform rounded-2xl bg-white p-6 shadow-2xl transition-transform duration-300">
            <h3 class="mb-4 text-xl font-bold text-[#223872]">Edit Program Kerja</h3>
            <form id="editProkerForm" method="POST">
                @csrf @method('PUT')
                <div class="grid gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Nama Proker <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="ep_name" required class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Periode Pelaksanaan</label>
                        <input type="text" name="period" id="ep_period" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi</label>
                        <textarea name="description" id="ep_desc" rows="3" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20"></textarea>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Urutan (Sort Order)</label>
                        <input type="number" name="sort_order" id="ep_sort" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editProkerModal')" class="rounded-xl px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#223872] px-6 py-2 text-sm font-bold text-white hover:bg-[#122452]">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODALS FOR AGENDA --}}
    <div id="addAgendaModal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="relative w-full max-w-lg scale-95 transform rounded-2xl bg-white p-6 shadow-2xl transition-transform duration-300">
            <h3 class="mb-4 text-xl font-bold text-[#223872]">Tambah Agenda</h3>
            <form action="{{ route('admin.departments.agendas.store', $department) }}" method="POST">
                @csrf
                <div class="grid gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Judul Agenda <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" required class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" required class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Lokasi</label>
                        <input type="text" name="location" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addAgendaModal')" class="rounded-xl px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#223872] px-6 py-2 text-sm font-bold text-white hover:bg-[#122452]">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editAgendaModal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="relative w-full max-w-lg scale-95 transform rounded-2xl bg-white p-6 shadow-2xl transition-transform duration-300">
            <h3 class="mb-4 text-xl font-bold text-[#223872]">Edit Agenda</h3>
            <form id="editAgendaForm" method="POST">
                @csrf @method('PUT')
                <div class="grid gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Judul Agenda <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="ea_title" required class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="ea_start" required class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" id="ea_end" required class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Lokasi</label>
                        <input type="text" name="location" id="ea_loc" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi</label>
                        <textarea name="description" id="ea_desc" rows="3" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editAgendaModal')" class="rounded-xl px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100">Batal</button>
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
        void modal.offsetWidth; // trigger reflow
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

    function editProker(proker) {
        document.getElementById('editProkerForm').action = `/admin/departments/{{ $department->id }}/work-programs/${proker.id}`;
        document.getElementById('ep_name').value = proker.name;
        document.getElementById('ep_period').value = proker.period || '';
        document.getElementById('ep_desc').value = proker.description || '';
        document.getElementById('ep_sort').value = proker.sort_order || 0;
        openModal('editProkerModal');
    }

    function editAgenda(agenda) {
        document.getElementById('editAgendaForm').action = `/admin/departments/{{ $department->id }}/agendas/${agenda.id}`;
        document.getElementById('ea_title').value = agenda.title;
        // Format dates to YYYY-MM-DD for input type="date"
        document.getElementById('ea_start').value = agenda.start_date ? agenda.start_date.split('T')[0] : '';
        document.getElementById('ea_end').value = agenda.end_date ? agenda.end_date.split('T')[0] : '';
        document.getElementById('ea_loc').value = agenda.location || '';
        document.getElementById('ea_desc').value = agenda.description || '';
        openModal('editAgendaModal');
    }
</script>
@endpush
