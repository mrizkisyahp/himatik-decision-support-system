@extends('layouts.admin')

@section('title', 'Kriteria Evaluasi — ' . $department->name)
@section('subtitle', 'Kelola bobot dan kriteria penilaian Profile Matching untuk department ini')

@section('topbar-actions')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
    <button onclick="document.getElementById('add-criterion-modal').classList.remove('hidden')" class="btn btn-primary btn-sm">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kriteria
    </button>
@endsection

@push('styles')
<style>
    .modal-backdrop {
        position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 500;
        display: flex; align-items: center; justify-content: center; padding: 16px;
        backdrop-filter: blur(3px);
    }
    .modal-box {
        background: white; border-radius: 14px; width: 100%; max-width: 540px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden;
        animation: modalSlide 0.25s cubic-bezier(0.34,1.56,0.64,1);
    }
    @keyframes modalSlide { from { transform:translateY(20px) scale(0.97); opacity:0; } to { transform:translateY(0) scale(1); opacity:1; } }
    .modal-header { display:flex; align-items:center; justify-content:space-between; padding:18px 22px; border-bottom:1px solid var(--color-border); }
    .modal-title { font-size:15px; font-weight:700; color:var(--color-text-primary); }
    .modal-close { width:28px; height:28px; border-radius:6px; background:#f1f5f9; border:none; cursor:pointer; color:var(--color-text-secondary); display:flex; align-items:center; justify-content:center; transition:all 0.15s; font-size:16px; }
    .modal-close:hover { background:#fee2e2; color:#dc2626; }
    .modal-body { padding:22px; max-height:75vh; overflow-y:auto; }
    .modal-footer { display:flex; justify-content:flex-end; gap:8px; padding:14px 22px; border-top:1px solid var(--color-border); background:#fafafa; }

    .form-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .form-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }

    .dept-info-card {
        background: linear-gradient(135deg, #ede9fe, #e0e7ff);
        border-radius: 12px; padding: 20px;
        border: 1px solid #c4b5fd;
        margin-bottom: 24px;
    }
    .dept-stat { text-align: center; }
    .dept-stat-value { font-size: 24px; font-weight: 800; color: #4338ca; }
    .dept-stat-label { font-size: 11px; font-weight: 600; color: #6d28d9; text-transform: uppercase; letter-spacing: 0.6px; margin-top: 2px; }

    .criteria-chip-core { background:#e0e7ff; color:#3730a3; border:1px solid #c7d2fe; font-size:11px; font-weight:700; padding:2px 8px; border-radius:20px; }
    .criteria-chip-secondary { background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-size:11px; font-weight:700; padding:2px 8px; border-radius:20px; }
    .criteria-chip-personal { background:#dbeafe; color:#1e40af; font-size:10px; font-weight:600; padding:2px 7px; border-radius:20px; }
    .criteria-chip-organizational { background:#d1fae5; color:#065f46; font-size:10px; font-weight:600; padding:2px 7px; border-radius:20px; }
    .criteria-code-badge {
        font-size: 12px;
        font-weight: 600;
        background: #f1f5f9;
        padding: 2px 7px;
        border-radius: 5px;
        color: var(--color-text-secondary);
    }

    .criteria-inactive { opacity: 0.55; }

    .confirm-danger {
        background: #fff1f2; border: 1px solid #fca5a5; border-radius: 10px;
        padding: 14px 16px; font-size: 13px; color: #b91c1c; margin-top: 16px;
        display: flex; align-items: flex-start; gap: 10px;
    }
</style>
@endpush

@section('content')

    {{-- Department Info --}}
    <div class="dept-info-card">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
            <div>
                <div style="font-size:11px; font-weight:600; color:#6d28d9; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Department</div>
                <div style="font-size:20px; font-weight:800; color:#3730a3;">{{ $department->name }}</div>
                @if($department->description)
                    <div style="font-size:13px; color:#5b21b6; margin-top:4px;">{{ $department->description }}</div>
                @endif
            </div>
            <div style="display:flex; gap:24px;">
                <div class="dept-stat">
                    <div class="dept-stat-value">{{ $department->core_factor_weight }}%</div>
                    <div class="dept-stat-label">Core Factor</div>
                </div>
                <div class="dept-stat">
                    <div class="dept-stat-value">{{ $department->secondary_factor_weight }}%</div>
                    <div class="dept-stat-label">Secondary Factor</div>
                </div>
                <div class="dept-stat">
                    <div class="dept-stat-value">{{ $criteria->count() }}</div>
                    <div class="dept-stat-label">Total Kriteria</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Criteria Table --}}
    <div class="admin-card" style="margin-bottom:20px;">
        <div class="admin-card-header">
            <div>
                <div class="admin-card-title">Daftar Kriteria Penilaian</div>
                <div style="font-size:12px; color:var(--color-text-muted); margin-top:2px;">Diurutkan berdasarkan urutan dan tipe faktor</div>
            </div>
            <form action="{{ route('admin.criteria.reset', $department) }}" method="POST"
                  onsubmit="return confirm('Reset semua kriteria ke default? Semua kriteria yang sudah dikustomisasi akan dihapus dan diganti dengan default.')">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm" style="color:#d97706; border-color:#fcd34d;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset ke Default
                </button>
            </form>
        </div>

        @if($criteria->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="empty-state-title">Belum Ada Kriteria</div>
                <div class="empty-state-desc">Tambahkan kriteria evaluasi atau reset ke kriteria default untuk memulai penilaian.</div>
                <div style="margin-top:16px; display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                    <button onclick="document.getElementById('add-criterion-modal').classList.remove('hidden')" class="btn btn-primary btn-sm">
                        + Tambah Kriteria
                    </button>
                    <form action="{{ route('admin.criteria.reset', $department) }}" method="POST" onsubmit="return confirm('Reset ke kriteria default?')">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm">Reset ke Default</button>
                    </form>
                </div>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Tipe Faktor</th>
                            <th>Aspek</th>
                            <th>Target Score</th>
                            <th>Status</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criteria as $i => $c)
                            <tr class="{{ !$c->is_active ? 'criteria-inactive' : '' }}">
                                <td class="mono" style="font-size:12px; color:var(--color-text-muted);">{{ $c->sort_order ?? ($i+1) }}</td>
                                <td>
                                    @if($c->code)
                                        <span class="criteria-code-badge mono">{{ $c->code }}</span>
                                    @else
                                        <span style="color:var(--color-text-muted);">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight:600; font-size:13.5px;">{{ $c->name }}</div>
                                    @if($c->description)
                                        <div style="font-size:11.5px; color:var(--color-text-muted); margin-top:2px; max-width:280px;">{{ Str::limit($c->description, 80) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="{{ $c->type === 'core' ? 'criteria-chip-core' : 'criteria-chip-secondary' }}">
                                        {{ $c->type === 'core' ? '⚡ Core Factor' : '○ Secondary Factor' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="{{ $c->aspect === 'personal' ? 'criteria-chip-personal' : 'criteria-chip-organizational' }}">
                                        {{ $c->aspect === 'personal' ? 'Personal' : 'Organizational' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        <span style="font-size:18px; font-weight:800; color:var(--color-brand);">{{ $c->target_score }}</span>
                                        <span style="font-size:11px; color:var(--color-text-muted);">/ 5</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $c->is_active ? 'badge-emerald' : 'badge-slate' }}">
                                        {{ $c->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex; gap:6px; justify-content:flex-end;">
                                        <button onclick="openEditCriterion({{ $c->id }}, {{ json_encode($c) }})"
                                                class="btn btn-ghost btn-xs">Edit</button>
                                        <form action="{{ route('admin.criteria.destroy', [$department, $c]) }}" method="POST"
                                              onsubmit="return confirm('Hapus kriteria ini?')">
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
    <div id="add-criterion-modal" class="modal-backdrop hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Tambah Kriteria Baru</div>
                <button class="modal-close" onclick="document.getElementById('add-criterion-modal').classList.add('hidden')">×</button>
            </div>
            <form action="{{ route('admin.criteria.post', $department) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Kode <span style="color:var(--color-text-muted);">(Opsional)</span></label>
                            <input type="text" name="code" class="form-input mono" placeholder="C01" value="{{ old('code') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Urutan Sort</label>
                            <input type="number" name="sort_order" class="form-input" placeholder="0" value="{{ old('sort_order', 0) }}" min="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Kriteria <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="name" class="form-input" placeholder="Contoh: Kemampuan Berkomunikasi" required value="{{ old('name') }}">
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group">
                            <label class="form-label">Tipe Faktor <span style="color:#dc2626;">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="core" {{ old('type') === 'core' ? 'selected' : '' }}>Core Factor</option>
                                <option value="secondary" {{ old('type') === 'secondary' ? 'selected' : '' }}>Secondary Factor</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Aspek <span style="color:#dc2626;">*</span></label>
                            <select name="aspect" class="form-select" required>
                                <option value="personal" {{ old('aspect') === 'personal' ? 'selected' : '' }}>Personal</option>
                                <option value="organizational" {{ old('aspect') === 'organizational' ? 'selected' : '' }}>Organizational</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Target Score <span style="color:#dc2626;">*</span></label>
                            <select name="target_score" class="form-select" required>
                                @for($s = 1; $s <= 5; $s++)
                                    <option value="{{ $s }}" {{ old('target_score', 3) == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-input" rows="2" placeholder="Deskripsi singkat tentang kriteria ini...">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan Penilaian</label>
                        <textarea name="catatan" class="form-input" rows="2" placeholder="Panduan pemberian skor untuk kriteria ini...">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="checkbox-item" style="display:flex; align-items:center; gap:8px; font-size:13px; cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} style="width:15px;height:15px;accent-color:var(--color-brand);">
                            <span style="font-weight:500;">Kriteria Aktif</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="document.getElementById('add-criterion-modal').classList.add('hidden')" class="btn btn-ghost btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Tambah Kriteria</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== EDIT MODAL ===== --}}
    <div id="edit-criterion-modal" class="modal-backdrop hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Edit Kriteria</div>
                <button class="modal-close" onclick="document.getElementById('edit-criterion-modal').classList.add('hidden')">×</button>
            </div>
            <form id="edit-criterion-form" action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Kode</label>
                            <input type="text" name="code" id="edit_c_code" class="form-input mono" placeholder="C01">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Urutan Sort</label>
                            <input type="number" name="sort_order" id="edit_c_sort" class="form-input" min="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Kriteria <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="name" id="edit_c_name" class="form-input" required>
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group">
                            <label class="form-label">Tipe Faktor <span style="color:#dc2626;">*</span></label>
                            <select name="type" id="edit_c_type" class="form-select" required>
                                <option value="core">Core Factor</option>
                                <option value="secondary">Secondary Factor</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Aspek <span style="color:#dc2626;">*</span></label>
                            <select name="aspect" id="edit_c_aspect" class="form-select" required>
                                <option value="personal">Personal</option>
                                <option value="organizational">Organizational</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Target Score <span style="color:#dc2626;">*</span></label>
                            <select name="target_score" id="edit_c_target" class="form-select" required>
                                @for($s = 1; $s <= 5; $s++)
                                    <option value="{{ $s }}">{{ $s }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" id="edit_c_desc" class="form-input" rows="2"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan Penilaian</label>
                        <textarea name="catatan" id="edit_c_catatan" class="form-input" rows="2"></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label style="display:flex; align-items:center; gap:8px; font-size:13px; cursor:pointer;">
                            <input type="checkbox" name="is_active" id="edit_c_active" value="1" style="width:15px;height:15px;accent-color:var(--color-brand);">
                            <span style="font-weight:500;">Kriteria Aktif</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="document.getElementById('edit-criterion-modal').classList.add('hidden')" class="btn btn-ghost btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
function openEditCriterion(criterionId, c) {
    const baseUrl = '{{ url('/admin/criteria/' . $department->slug) }}';
    document.getElementById('edit-criterion-form').action = baseUrl + '/' + criterionId;

    document.getElementById('edit_c_code').value = c.code || '';
    document.getElementById('edit_c_sort').value = c.sort_order || 0;
    document.getElementById('edit_c_name').value = c.name || '';
    document.getElementById('edit_c_type').value = c.type || 'core';
    document.getElementById('edit_c_aspect').value = c.aspect || 'personal';
    document.getElementById('edit_c_target').value = c.target_score || 3;
    document.getElementById('edit_c_desc').value = c.description || '';
    document.getElementById('edit_c_catatan').value = c.catatan || '';
    document.getElementById('edit_c_active').checked = c.is_active == 1;

    document.getElementById('edit-criterion-modal').classList.remove('hidden');
}

['add-criterion-modal', 'edit-criterion-modal'].forEach(id => {
    const modal = document.getElementById(id);
    if (modal) {
        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.add('hidden');
        });
    }
});

@if($errors->any() && old('name') !== null && old('type') !== null)
    document.getElementById('add-criterion-modal').classList.remove('hidden');
@endif
</script>
@endpush
