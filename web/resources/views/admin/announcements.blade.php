@extends('admin.layout', ['title' => 'Pengumuman Akhir', 'hideStubBadge' => true])

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-[#111827]">Pengumuman Akhir</h2>
            <p class="text-sm text-[#64748B]">Manajemen status kelulusan kandidat ke publik.</p>
        </div>
        <div>
            <form action="{{ route('admin.publish') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin {{ $isPublished ? 'menarik/menyembunyikan' : 'mempublikasikan' }} pengumuman?');">
                @csrf
                <input type="hidden" name="is_published" value="{{ $isPublished ? 0 : 1 }}">
                @if($isPublished)
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2 text-sm font-bold text-red-600 transition hover:bg-red-50">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        Tarik Publikasi (Sembunyikan)
                    </button>
                @else
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        Publish Semua Pengumuman
                    </button>
                @endif
            </form>
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

    <div class="mb-6 rounded-2xl border border-[#D8E2F3] bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.announcements') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NIM..." class="w-full rounded-xl border border-gray-300 bg-gray-50 py-2 pl-10 pr-4 text-sm focus:border-[#4A90E2] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4A90E2]/20">
            </div>
            <select name="status" class="rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm focus:border-[#4A90E2] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4A90E2]/20">
                <option value="">Semua Keputusan</option>
                <option value="accepted" {{ $statusFilter === 'accepted' ? 'selected' : '' }}>Diterima</option>
                <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="rounded-xl bg-[#223872] px-6 py-2 text-sm font-bold text-white transition hover:bg-[#122452]">Filter</button>
            @if($search || $statusFilter)
                <a href="{{ route('admin.announcements') }}" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-600 transition hover:bg-gray-50">Reset</a>
            @endif
        </form>
    </div>

    <div class="rounded-2xl border border-[#D8E2F3] bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#F4F7FF] text-xs uppercase text-[#64748B]">
                    <tr>
                        <th class="px-6 py-4 font-bold">Kandidat</th>
                        <th class="px-6 py-4 font-bold">Pilihan Departemen</th>
                        <th class="px-6 py-4 font-bold">Status Keputusan Interviewer</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi (Timpa Keputusan)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8E2F3]">
                    @forelse ($announcements as $announcement)
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#111827]">{{ $announcement->candidate->user->name }}</div>
                                <div class="text-xs text-[#64748B]">{{ $announcement->candidate->nim }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <ul class="list-inside list-decimal text-xs text-[#333333]">
                                    @foreach($announcement->candidate->departmentChoices->sortBy('pivot.choice_order') as $choice)
                                        <li>{{ $choice->department->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4">
                                @if($announcement->status === 'accepted')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Lulus ({{ $announcement->assignedDepartment?->name ?? '-' }})
                                    </span>
                                @elseif($announcement->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-1 text-xs font-bold text-red-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Tidak Lulus
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-500"></span> {{ ucfirst($announcement->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick='openDecideModal(@json($announcement))' class="rounded-lg bg-[#EEF4FF] px-3 py-1.5 text-xs font-bold text-[#223872] transition hover:bg-[#D8E2F3]">
                                    Ubah Status
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-[#64748B]">Tidak ada data keputusan interviewer ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($announcements->hasPages())
            <div class="border-t border-[#D8E2F3] px-6 py-4">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>

    {{-- Decide Modal --}}
    <div id="decideModal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="relative w-full max-w-md scale-95 transform rounded-2xl bg-white p-6 shadow-2xl transition-transform duration-300">
            <h3 class="mb-4 text-xl font-bold text-[#223872]">Ubah Keputusan</h3>
            <p class="mb-4 text-sm text-gray-600" id="decideModalName"></p>
            <form id="decideForm" method="POST">
                @csrf
                <div class="grid gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Status Kelulusan</label>
                        <select name="status" id="decide_status" required onchange="toggleDeptSelect()" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                            <option value="accepted">Diterima (Lulus)</option>
                            <option value="rejected">Ditolak (Tidak Lulus)</option>
                        </select>
                    </div>
                    <div id="deptSelectGroup" class="hidden">
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Diterima di Departemen/Biro</label>
                        <select name="assigned_department_id" id="decide_dept" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2 text-sm outline-none focus:border-[#4A90E2] focus:bg-white focus:ring-2 focus:ring-[#4A90E2]/20">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('decideModal')" class="rounded-xl px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#223872] px-6 py-2 text-sm font-bold text-white hover:bg-[#122452]">Simpan Keputusan</button>
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

    function openDecideModal(announcement) {
        const candidate = announcement.candidate;
        document.getElementById('decideModalName').innerHTML = `Tentukan hasil akhir untuk kandidat <strong>${candidate.user.name}</strong> (${candidate.nim})`;
        document.getElementById('decideForm').action = `/admin/decide/${candidate.id}`;
        
        document.getElementById('decide_status').value = announcement.status;
        document.getElementById('decide_dept').value = announcement.assigned_department_id || '';
        toggleDeptSelect();
        
        openModal('decideModal');
    }

    function toggleDeptSelect() {
        const status = document.getElementById('decide_status').value;
        const deptGroup = document.getElementById('deptSelectGroup');
        const deptSelect = document.getElementById('decide_dept');
        if (status === 'accepted') {
            deptGroup.classList.remove('hidden');
            deptSelect.setAttribute('required', 'required');
        } else {
            deptGroup.classList.add('hidden');
            deptSelect.removeAttribute('required');
        }
    }
</script>
@endpush
