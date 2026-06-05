@extends('layouts.admin')

@section('title', 'Jadwal Interview')
@section('subtitle', 'Kelola slot waktu interview dan penugasan interviewer')

@section('topbar-actions')
    <button onclick="document.getElementById('add-schedule-modal').classList.remove('hidden')"
            class="btn btn-primary btn-sm">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Jadwal
    </button>
@endsection

@push('styles')
<style>
    .modal-backdrop {
        position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 500;
        display: flex; align-items: center; justify-content: center; padding: 16px;
        backdrop-filter: blur(3px);
        animation: modalIn 0.2s ease;
    }
    @keyframes modalIn { from { opacity:0; } to { opacity:1; } }

    .modal-box {
        background: white; border-radius: 14px; width: 100%; max-width: 540px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden;
        animation: modalSlide 0.25s cubic-bezier(0.34,1.56,0.64,1);
    }
    @keyframes modalSlide { from { transform:translateY(20px) scale(0.97); opacity:0; } to { transform:translateY(0) scale(1); opacity:1; } }

    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 22px; border-bottom: 1px solid var(--color-border);
    }
    .modal-title { font-size: 15px; font-weight: 700; color: var(--color-text-primary); }
    .modal-close {
        width: 28px; height: 28px; border-radius: 6px; background: #f1f5f9; border: none;
        cursor: pointer; color: var(--color-text-secondary); display: flex; align-items: center; justify-content: center;
        transition: all 0.15s; font-size: 16px; line-height: 1;
    }
    .modal-close:hover { background: #fee2e2; color: #dc2626; }
    .modal-body { padding: 22px; max-height: 70vh; overflow-y: auto; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 8px; padding: 14px 22px; border-top: 1px solid var(--color-border); background: #fafafa; }

    .form-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }

    .schedule-status-active { background:#d1fae5; color:#065f46; }
    .schedule-status-inactive { background:#f1f5f9; color:#64748b; }

    .interviewer-tags { display:flex; flex-wrap:wrap; gap:5px; }

    .checkbox-group {
        display: flex; flex-direction: column; gap: 8px;
        max-height: 160px; overflow-y: auto;
        border: 1px solid var(--color-border); border-radius: 8px; padding: 10px;
    }
    .checkbox-item { display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer; }
    .checkbox-item input[type="checkbox"] { width: 15px; height: 15px; accent-color: var(--color-brand); cursor: pointer; }

    .table-action-group { display:flex; gap:6px; }
</style>
@endpush

@section('content')

    {{-- Stats Row --}}
    <div class="grid-3 page-section" style="max-width: 640px;">
        <div class="stat-card" style="padding:16px;">
            <div class="stat-label">Total Jadwal</div>
            <div class="stat-value" style="font-size:22px;">{{ $schedules->count() }}</div>
        </div>
        <div class="stat-card" style="padding:16px;">
            <div class="stat-label">Jadwal Aktif</div>
            <div class="stat-value" style="font-size:22px; color:#059669;">{{ $schedules->where('is_active', true)->count() }}</div>
        </div>
        <div class="stat-card" style="padding:16px;">
            <div class="stat-label">Sudah Dibooking</div>
            <div class="stat-value" style="font-size:22px; color:#6366f1;">{{ $schedules->filter(fn($s) => $s->booking)->count() }}</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <div class="admin-card-title">Daftar Jadwal Interview</div>
                <div style="font-size:12px;color:var(--color-text-muted);margin-top:2px;">Semua slot waktu wawancara yang tersedia</div>
            </div>
        </div>

        @if($schedules->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="empty-state-title">Belum Ada Jadwal</div>
                <div class="empty-state-desc">Tambahkan slot jadwal interview untuk mulai menerima booking dari kandidat.</div>
                <div style="margin-top:16px;">
                    <button onclick="document.getElementById('add-schedule-modal').classList.remove('hidden')" class="btn btn-primary">
                        + Tambah Jadwal Pertama
                    </button>
                </div>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Sesi / Nama</th>
                            <th>Department</th>
                            <th>Waktu</th>
                            <th>Lokasi</th>
                            <th>Interviewer</th>
                            <th>Kandidat</th>
                            <th>Status</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td>
                                    <div style="font-weight:600; font-size:13.5px;">{{ $schedule->session_name }}</div>
                                    <div class="mono" style="font-size:11px;color:var(--color-text-muted);">#{{ $schedule->id }}</div>
                                </td>
                                <td>
                                    @if($schedule->department)
                                        <span class="badge badge-indigo">{{ $schedule->department->name }}</span>
                                    @else
                                        <span style="color:var(--color-text-muted);">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size:13px; font-weight:500;">{{ \Carbon\Carbon::parse($schedule->scheduled_at)->format('d M Y') }}</div>
                                    <div style="font-size:11px; color:var(--color-text-muted);">{{ \Carbon\Carbon::parse($schedule->scheduled_at)->format('H:i') }} WIB</div>
                                </td>
                                <td style="font-size:13px; max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $schedule->location }}</td>
                                <td>
                                    @if($schedule->interviewers->isNotEmpty())
                                        <div class="interviewer-tags">
                                            @foreach($schedule->interviewers->take(2) as $iv)
                                                <span class="badge badge-violet" style="font-size:10.5px;">{{ $iv->name }}</span>
                                            @endforeach
                                            @if($schedule->interviewers->count() > 2)
                                                <span class="badge badge-slate" style="font-size:10.5px;">+{{ $schedule->interviewers->count() - 2 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span style="color:var(--color-text-muted); font-size:12px;">Belum ditugaskan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->booking && $schedule->booking->candidate)
                                        <div style="font-size:13px; font-weight:500;">{{ $schedule->booking->candidate->user->name ?? '—' }}</div>
                                        <div class="mono" style="font-size:10.5px; color:var(--color-text-muted);">{{ $schedule->booking->candidate->nim ?? '' }}</div>
                                    @else
                                        <span class="badge badge-emerald" style="font-size:10.5px;">Tersedia</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $schedule->is_active ? 'schedule-status-active' : 'schedule-status-inactive' }}">
                                        {{ $schedule->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-action-group" style="justify-content:flex-end;">
                                        <button onclick="openEditModal({{ $schedule->id }}, {{ json_encode($schedule) }}, {{ json_encode($schedule->interviewers->pluck('id')) }})"
                                                class="btn btn-ghost btn-xs">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST"
                                              onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ===== ADD MODAL ===== --}}
    <div id="add-schedule-modal" class="modal-backdrop hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Tambah Jadwal Baru</div>
                <button class="modal-close" onclick="document.getElementById('add-schedule-modal').classList.add('hidden')">×</button>
            </div>
            <form action="{{ route('admin.schedules.post') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Sesi <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="session_name" class="form-input" placeholder="Contoh: Sesi Interview Pagi – KOMINFO" required value="{{ old('session_name') }}">
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Department <span style="color:#dc2626;">*</span></label>
                            <select name="department_id" class="form-select" required>
                                <option value="">— Pilih Department —</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Waktu Pelaksanaan <span style="color:#dc2626;">*</span></label>
                            <input type="datetime-local" name="scheduled_at" class="form-input" required value="{{ old('scheduled_at') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lokasi <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="location" class="form-input" placeholder="Contoh: Ruang Rapat Lantai 2" required value="{{ old('location') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Penugasan Interviewer</label>
                        @if($interviewers->isEmpty())
                            <div style="font-size:12.5px; color:var(--color-text-muted); padding:12px; background:#f8fafc; border-radius:8px; border:1px solid var(--color-border);">
                                Belum ada interviewer. <a href="{{ route('admin.interviewers') }}" style="color:var(--color-brand);">Tambahkan interviewer dulu →</a>
                            </div>
                        @else
                            <div class="checkbox-group">
                                @foreach($interviewers as $iv)
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="interviewer_ids[]" value="{{ $iv->id }}"
                                               {{ in_array($iv->id, old('interviewer_ids', [])) ? 'checked' : '' }}>
                                        <span>{{ $iv->name }}</span>
                                        <span style="font-size:11px; color:var(--color-text-muted);">{{ $iv->email }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="checkbox-item">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <span style="font-size:13px; font-weight:500;">Jadwal Aktif (bisa dibooking kandidat)</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="document.getElementById('add-schedule-modal').classList.add('hidden')" class="btn btn-ghost btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== EDIT MODAL ===== --}}
    <div id="edit-schedule-modal" class="modal-backdrop hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Edit Jadwal</div>
                <button class="modal-close" onclick="document.getElementById('edit-schedule-modal').classList.add('hidden')">×</button>
            </div>
            <form id="edit-schedule-form" action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Sesi <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="session_name" id="edit_session_name" class="form-input" required>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Department <span style="color:#dc2626;">*</span></label>
                            <select name="department_id" id="edit_department_id" class="form-select" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Waktu Pelaksanaan <span style="color:#dc2626;">*</span></label>
                            <input type="datetime-local" name="scheduled_at" id="edit_scheduled_at" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lokasi <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="location" id="edit_location" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Penugasan Interviewer</label>
                        <div class="checkbox-group" id="edit-interviewers-list">
                            @foreach($interviewers as $iv)
                                <label class="checkbox-item">
                                    <input type="checkbox" name="interviewer_ids[]" value="{{ $iv->id }}" class="edit-iv-check" data-id="{{ $iv->id }}">
                                    <span>{{ $iv->name }}</span>
                                    <span style="font-size:11px; color:var(--color-text-muted);">{{ $iv->email }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="checkbox-item">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1">
                            <span style="font-size:13px; font-weight:500;">Jadwal Aktif</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="document.getElementById('edit-schedule-modal').classList.add('hidden')" class="btn btn-ghost btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
function openEditModal(id, schedule, assignedInterviewerIds) {
    const form = document.getElementById('edit-schedule-form');
    form.action = '/admin/schedules/' + id;

    document.getElementById('edit_session_name').value = schedule.session_name || '';
    document.getElementById('edit_department_id').value = schedule.department_id || '';
    document.getElementById('edit_location').value = schedule.location || '';
    document.getElementById('edit_is_active').checked = schedule.is_active == 1;

    // Format datetime-local
    if (schedule.scheduled_at) {
        const dt = new Date(schedule.scheduled_at);
        const pad = n => String(n).padStart(2, '0');
        const local = dt.getFullYear() + '-' + pad(dt.getMonth()+1) + '-' + pad(dt.getDate()) + 'T' + pad(dt.getHours()) + ':' + pad(dt.getMinutes());
        document.getElementById('edit_scheduled_at').value = local;
    }

    // Set interviewer checkboxes
    document.querySelectorAll('.edit-iv-check').forEach(cb => {
        cb.checked = assignedInterviewerIds.includes(parseInt(cb.dataset.id));
    });

    document.getElementById('edit-schedule-modal').classList.remove('hidden');
}

// Close modal on outside click
['add-schedule-modal', 'edit-schedule-modal'].forEach(id => {
    const modal = document.getElementById(id);
    if (modal) {
        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.add('hidden');
        });
    }
});

// Auto open add modal only if this specific form was submitted with errors
@if($errors->any() && old('session_name') !== null)
    document.getElementById('add-schedule-modal').classList.remove('hidden');
@endif
</script>
@endpush