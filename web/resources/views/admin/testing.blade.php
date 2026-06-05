@extends('layouts.admin')

@section('title', 'Profile Matching')
@section('subtitle', 'Evaluasi skor kandidat dan lihat hasil ranking DSS per department')

@push('styles')
<style>
    /* ====== Step Card ====== */
    .step-card {
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .step-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; border-bottom: 1px solid var(--color-border);
        background: #fafafa;
    }
    .step-header-left { display: flex; align-items: center; gap: 12px; }
    .step-number {
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white; font-size: 12px; font-weight: 800;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .step-title { font-size: 14px; font-weight: 700; color: var(--color-text-primary); }
    .step-desc { font-size: 12px; color: var(--color-text-muted); margin-top: 1px; }

    /* ====== Department Selector ====== */
    .dept-select-wrapper {
        padding: 20px;
    }
    .dept-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
        margin-top: 16px;
    }
    .dept-card {
        padding: 14px 16px; border: 2px solid var(--color-border); border-radius: 10px;
        cursor: pointer; text-decoration: none; transition: all 0.15s;
        display: block;
    }
    .dept-card:hover { border-color: #a5b4fc; background: #f5f3ff; }
    .dept-card.selected { border-color: var(--color-brand); background: #e0e7ff; }
    .dept-card-name { font-size: 13.5px; font-weight: 700; color: var(--color-text-primary); }
    .dept-card-meta { font-size: 11px; color: var(--color-text-muted); margin-top: 3px; }

    /* ====== Dept Info Strip ====== */
    .dept-info-strip {
        display: flex; gap: 16px; padding: 14px 20px; background: #f5f3ff;
        border-bottom: 1px solid #c4b5fd; flex-wrap: wrap; align-items: center;
    }
    .dept-info-item { text-align: center; }
    .dept-info-name { font-size: 13.5px; font-weight: 700; color: #3730a3; }
    .dept-info-description { font-size: 12px; color: #6d28d9; margin-top: 2px; }
    .dept-info-value { font-size: 20px; font-weight: 800; color: #4338ca; }
    .dept-info-label { font-size: 10px; font-weight: 600; color: #6d28d9; text-transform: uppercase; letter-spacing: 0.6px; }

    /* ====== Criteria Chips ====== */
    .chip-core { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
    .chip-secondary { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .chip {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 20px; font-size: 11.5px; font-weight: 700;
        transition: transform 0.1s;
    }
    .chip:hover { transform: scale(1.04); }

    /* ====== Candidate Row in Scoring ====== */
    .candidate-accordion {
        border-bottom: 1px solid #f1f5f9;
    }
    .candidate-accordion:last-child { border-bottom: none; }
    .candidate-summary {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; cursor: pointer;
        transition: background 0.15s;
        list-style: none;
    }
    .candidate-summary:hover { background: #f8fafc; }
    details[open] .candidate-summary { background: #f5f3ff; }
    .candidate-summary::-webkit-details-marker { display: none; }
    .candidate-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: linear-gradient(135deg, #e0e7ff, #ede9fe);
        color: #4f46e5; font-weight: 700; font-size: 14px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .progress-bar { width: 80px; height: 5px; border-radius: 3px; background: #e2e8f0; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 3px; background: linear-gradient(to right, #6366f1, #8b5cf6); transition: width 0.6s ease; }
    .progress-bar-fill.full { background: linear-gradient(to right, #059669, #10b981); }
    .chevron { transition: transform 0.25s ease; display: inline-block; }
    details[open] .chevron { transform: rotate(180deg); }

    /* ====== Score Input Grid ====== */
    .score-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 12px;
        padding: 16px 20px;
    }
    .score-card {
        border: 1px solid var(--color-border); border-radius: 10px; padding: 12px;
        background: white; transition: all 0.15s;
        min-width: 0;
        min-height: 116px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .score-card:hover { border-color: #a5b4fc; box-shadow: 0 2px 8px rgba(99,102,241,0.1); }
    .score-card-name { font-size: 12px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px; min-width: 0; }
    .score-card-type { font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 10px; }
    .score-select {
        width: 100%; padding: 7px 10px; border-radius: 7px;
        border: 1px solid var(--color-border); font-size: 14px; font-weight: 700;
        color: var(--color-text-primary); appearance: none; background: #f8fafc;
        text-align: center; cursor: pointer; transition: all 0.15s; outline: none;
    }
    .score-select:focus { border-color: var(--color-brand); box-shadow: 0 0 0 3px rgba(79,70,229,0.1); background: white; }
    .score-select.has-score { border-color: #6366f1; background: #e0e7ff; color: #3730a3; }
    @media (min-width: 640px) {
        .score-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (min-width: 1024px) {
        .score-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (min-width: 1280px) {
        .score-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }

    /* ====== Rankings Table ====== */
    .rank-badge {
        width: 30px; height: 30px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 800;
    }
    .rank-1 { background: linear-gradient(135deg,#f59e0b,#fbbf24); color:white; box-shadow:0 3px 8px rgba(245,158,11,0.4); }
    .rank-2 { background: linear-gradient(135deg,#94a3b8,#cbd5e1); color:white; }
    .rank-3 { background: linear-gradient(135deg,#b45309,#d97706); color:white; }
    .rank-n { background: #f1f5f9; color: #64748b; }
    .row-top { background: linear-gradient(to right, rgba(254,240,138,0.2), transparent); }

    /* ====== Modals ====== */
    .modal-backdrop {
        position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 500;
        display: flex; align-items: center; justify-content: center; padding: 16px;
        backdrop-filter: blur(3px);
    }
    .modal-box {
        background: white; border-radius: 14px; width: 100%; max-width: 580px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden;
        animation: modalSlide 0.25s cubic-bezier(0.34,1.56,0.64,1);
    }
    .modal-box-sm { max-width: 480px; }
    @keyframes modalSlide { from { transform:translateY(20px) scale(0.97); opacity:0; } to { transform:translateY(0) scale(1); opacity:1; } }
    .modal-header { display:flex; align-items:center; justify-content:space-between; padding:18px 22px; border-bottom:1px solid var(--color-border); }
    .modal-title { font-size:15px; font-weight:700; color:var(--color-text-primary); }
    .modal-close { width:28px; height:28px; border-radius:6px; background:#f1f5f9; border:none; cursor:pointer; color:var(--color-text-secondary); display:flex; align-items:center; justify-content:center; font-size:16px; transition:all 0.15s; }
    .modal-close:hover { background:#fee2e2; color:#dc2626; }
    .modal-body { padding:22px; max-height:75vh; overflow-y:auto; }
    .modal-footer { display:flex; justify-content:flex-end; gap:8px; padding:14px 22px; border-top:1px solid var(--color-border); background:#fafafa; }

    .form-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .form-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }

    /* Candidate Management Form Panel */
    .candidate-form-panel {
        background: #f8fafc; border-bottom: 1px solid var(--color-border);
        padding: 16px 20px;
    }

    /* Action bar inside scoring accordion */
    .score-action-bar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 20px 16px; flex-wrap: wrap; gap: 8px;
    }
    .score-hint { font-size: 12px; color: var(--color-text-muted); display: flex; align-items: center; gap: 5px; }
</style>
@endpush

@section('content')

{{-- Alert --}}
@if($error)
    <div class="flash-message flash-warning" style="margin-bottom:20px;">
        <div class="flash-icon" style="background:#fef3c7; color:#d97706;">⚠</div>
        <div>
            <div style="font-weight:600;">Tidak Dapat Melanjutkan</div>
            <div style="font-size:12.5px; margin-top:2px;">{{ $error }}</div>
        </div>
    </div>
@endif

{{-- ============================================================
     STEP 1: SELECT DEPARTMENT
============================================================ --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-header-left">
            <div class="step-number">1</div>
            <div>
                <div class="step-title">Pilih Department / Biro</div>
                <div class="step-desc">Tentukan department yang akan dievaluasi kandidatnya</div>
            </div>
        </div>
    </div>

    <div class="dept-select-wrapper">

        {{-- Department selection cards --}}
        <div class="dept-grid">
            @foreach($departments as $dept)
                <a href="{{ route('admin.testing', ['department_id' => $dept->id]) }}"
                   class="dept-card {{ request('department_id') == $dept->id ? 'selected' : '' }}">
                    <div class="dept-card-name">
                        {{ $dept->name }}
                        @if(!$dept->is_active)
                            <span class="badge badge-slate" style="margin-left:6px;">Nonaktif</span>
                        @endif
                    </div>
                    <div class="dept-card-meta">{{ $dept->evaluationCriteria->where('is_active', true)->count() }} kriteria aktif</div>
                </a>
            @endforeach
            @if($departments->isEmpty())
                <div style="grid-column:1/-1; text-align:center; padding:24px; color:var(--color-text-muted); font-size:13px;">
                    Belum ada department yang tersedia.
                </div>
            @endif
        </div>
    </div>


    @if($selectedDepartment)
        <div class="dept-info-strip">
            <div style="flex:1; min-width:160px;">
                <div class="dept-info-name">
                    {{ $selectedDepartment->name }}
                    @if(!$selectedDepartment->is_active)
                        <span class="badge badge-slate" style="margin-left:6px;">Nonaktif</span>
                    @endif
                </div>
                @if($selectedDepartment->description)
                    <div class="dept-info-description">{{ $selectedDepartment->description }}</div>
                @endif
            </div>
            <div class="dept-info-item">
                <div class="dept-info-value">{{ $selectedDepartment->personal_aspect_weight }}%</div>
                <div class="dept-info-label">Personal</div>
            </div>
            <div class="dept-info-item">
                <div class="dept-info-value">{{ $selectedDepartment->organizational_aspect_weight }}%</div>
                <div class="dept-info-label">Organizational</div>
            </div>
            <div class="dept-info-item">
                <div class="dept-info-value">{{ $selectedDepartment->core_factor_weight }}%</div>
                <div class="dept-info-label">Core</div>
            </div>
            <div class="dept-info-item">
                <div class="dept-info-value">{{ $selectedDepartment->secondary_factor_weight }}%</div>
                <div class="dept-info-label">Secondary</div>
            </div>
            <div class="dept-info-item">
                <div class="dept-info-value">{{ $criteria->count() }}</div>
                <div class="dept-info-label">Kriteria</div>
            </div>
            <div class="dept-info-item">
                <div class="dept-info-value">{{ $candidates->total() }}</div>
                <div class="dept-info-label">Kandidat</div>
            </div>

            @if($criteria->isNotEmpty())
                <a href="{{ route('admin.criteria', $selectedDepartment) }}" class="btn btn-ghost btn-sm" style="flex-shrink:0;">
                    Kelola Kriteria →
                </a>
            @endif
        </div>

        @if($criteria->isNotEmpty())
            <div style="padding: 12px 20px; display:flex; flex-wrap:wrap; gap:6px; border-top:1px solid #ede9fe; background:#fafbff;">
                @foreach($criteria as $c)
                    <span class="chip {{ $c->type==='core' ? 'chip-core' : 'chip-secondary' }}"
                          title="{{ $c->name }} - Target: {{ $c->target_score }}">
                        <span style="opacity:0.7;">{{ $c->aspect === 'personal' ? 'P' : 'O' }}</span>
                        <span style="opacity:0.7;">{{ $c->type==='core'?'CF':'SF' }}</span>
                        {{ $c->name }}
                        <span style="opacity:0.5;">={{ $c->target_score }}</span>
                    </span>
                @endforeach
            </div>
        @endif
    @endif
</div>

@if(!$selectedDepartment && !$error)
    <div style="text-align:center; padding:60px 20px;">
        <div style="width:64px;height:64px;background:#f1f5f9;border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;color:#94a3b8;">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>
        <div style="font-size:16px; font-weight:700; color:var(--color-text-secondary); margin-bottom:6px;">Pilih Department di Atas</div>
        <div style="font-size:13px; color:var(--color-text-muted); max-width:380px; margin:0 auto;">Pilih department/biro untuk mulai mengelola kandidat, mengisi skor evaluasi, dan melihat hasil ranking DSS.</div>
    </div>
@endif

@if($selectedDepartment && $criteria->isNotEmpty())

{{-- ============================================================
     STEP 2: MANAGE CANDIDATES
============================================================ --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-header-left">
            <div class="step-number">2</div>
            <div>
                <div class="step-title">Manajemen Kandidat</div>
                <div class="step-desc">Kelola data kandidat yang memilih department ini</div>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            {{-- Search --}}
            <form method="GET" action="{{ route('admin.testing') }}" style="position:relative;">
                <input type="hidden" name="department_id" value="{{ $selectedDepartment->id }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / NIM..."
                       class="form-input" style="padding:7px 34px 7px 12px; width:200px; font-size:13px; border-radius:8px;">
                <button type="submit" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--color-text-muted);">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>
            @if($search)
                <a href="{{ route('admin.testing', ['department_id' => $selectedDepartment->id]) }}" class="btn btn-ghost btn-sm">× Hapus Filter</a>
            @endif
            <button onclick="toggleAddPanel()" class="btn btn-primary btn-sm">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Kandidat
            </button>
        </div>
    </div>

    {{-- Add/Edit Form Panel --}}
    @php $isEditingGlobal = request()->has('edit_candidate'); @endphp
    <div id="add-candidate-panel" class="{{ ($isEditingGlobal || request('action') == 'add_candidate' || ($errors->any() && old('nim') !== null)) ? '' : 'hidden' }} candidate-form-panel">
        <div style="background:white; border:1px solid var(--color-border); border-radius:10px; padding:16px;">
            @if($isEditingGlobal)
                @php $editCandidate = $candidates->firstWhere('id', request('edit_candidate')); @endphp
                @if($editCandidate)
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid var(--color-border);">
                        <div style="font-size:13.5px; font-weight:700; color:#4338ca;">✎ Edit Kandidat: {{ $editCandidate->user->name }}</div>
                        <a href="{{ route('admin.testing', ['department_id' => $selectedDepartment->id]) }}" class="btn btn-ghost btn-xs">Batal</a>
                    </div>
                    <form method="POST" action="{{ route('admin.testing.candidates.update', $editCandidate->id) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="department_id" value="{{ $selectedDepartment->id }}">
                        <div class="form-grid-3" style="margin-bottom:12px;">
                            <div><label class="form-label">Nama</label><input type="text" name="name" value="{{ old('name', $editCandidate->user->name) }}" class="form-input" required></div>
                            <div><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $editCandidate->user->email) }}" class="form-input" required></div>
                            <div><label class="form-label">NIM</label><input type="text" name="nim" value="{{ old('nim', $editCandidate->nim) }}" class="form-input mono" required></div>
                            <div>
                                <label class="form-label">Prodi</label>
                                <select name="prodi" class="form-select" required>
                                    @foreach(['Teknik Informatika','Teknik Multimedia dan Jaringan','Teknik Multimedia dan Digital'] as $p)
                                        <option value="{{ $p }}" {{ old('prodi', $editCandidate->prodi) === $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label class="form-label">Kelas</label><input type="text" name="kelas" value="{{ old('kelas', $editCandidate->kelas) }}" class="form-input" required></div>
                            <div><label class="form-label">Phone</label><input type="text" name="phone" value="{{ old('phone', $editCandidate->phone) }}" class="form-input" required></div>
                            <div>
                                <label class="form-label">Pilihan 1</label>
                                <select name="first_choice_id" class="form-select" required>
                                    @foreach($departments as $d)
                                        <option value="{{ $d->id }}" {{ old('first_choice_id', $editCandidate->first_choice_department?->id) == $d->id ? 'selected' : '' }}>{{ $d->name }}{{ $d->is_active ? '' : ' (Nonaktif)' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Pilihan 2 <span style="color:var(--color-text-muted);">(Opsional)</span></label>
                                <select name="second_choice_id" class="form-select">
                                    <option value="">— Tidak ada —</option>
                                    @foreach($departments as $d)
                                        <option value="{{ $d->id }}" {{ old('second_choice_id', $editCandidate->second_choice_department?->id) == $d->id ? 'selected' : '' }}>{{ $d->name }}{{ $d->is_active ? '' : ' (Nonaktif)' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div style="display:flex; justify-content:flex-end;">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                        </div>
                    </form>
                @endif
            @else
                {{-- Add Form --}}
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid var(--color-border);">
                    <div style="font-size:13.5px; font-weight:700; color:var(--color-text-primary);">+ Tambah Kandidat Baru</div>
                    <button type="button" onclick="toggleAddPanel()" class="btn btn-ghost btn-xs">Tutup</button>
                </div>
                <form method="POST" action="{{ route('admin.testing.candidates.store') }}">
                    @csrf
                    <input type="hidden" name="department_id" value="{{ $selectedDepartment->id }}">
                    <div class="form-grid-3" style="margin-bottom:12px;">
                        <div><label class="form-label">Nama <span style="color:#dc2626;">*</span></label><input type="text" name="name" value="{{ old('name') }}" class="form-input" required></div>
                        <div><label class="form-label">Email <span style="color:#dc2626;">*</span></label><input type="email" name="email" value="{{ old('email') }}" class="form-input" required></div>
                        <div><label class="form-label">NIM <span style="color:#dc2626;">*</span></label><input type="text" name="nim" value="{{ old('nim') }}" class="form-input mono" required></div>
                        <div>
                            <label class="form-label">Prodi <span style="color:#dc2626;">*</span></label>
                            <select name="prodi" class="form-select" required>
                                <option value="">— Pilih —</option>
                                @foreach(['Teknik Informatika','Teknik Multimedia dan Jaringan','Teknik Multimedia dan Digital'] as $p)
                                    <option value="{{ $p }}" {{ old('prodi') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="form-label">Kelas <span style="color:#dc2626;">*</span></label><input type="text" name="kelas" value="{{ old('kelas') }}" class="form-input" required></div>
                        <div><label class="form-label">Phone <span style="color:#dc2626;">*</span></label><input type="text" name="phone" value="{{ old('phone') }}" class="form-input" required></div>
                        <div>
                            <label class="form-label">Pilihan 1 <span style="color:#dc2626;">*</span></label>
                            <select name="first_choice_id" class="form-select" required>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" {{ old('first_choice_id', $selectedDepartment->id) == $d->id ? 'selected' : '' }}>{{ $d->name }}{{ $d->is_active ? '' : ' (Nonaktif)' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Pilihan 2 <span style="color:var(--color-text-muted);">(Opsional)</span></label>
                            <select name="second_choice_id" class="form-select">
                                <option value="">— Tidak ada —</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" {{ old('second_choice_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}{{ $d->is_active ? '' : ' (Nonaktif)' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
                        <span style="font-size:12px; color:var(--color-text-muted);">Password default: <strong style="color:var(--color-text-secondary);">testing123</strong></span>
                        <button type="submit" class="btn btn-success btn-sm">+ Tambah Kandidat</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    {{-- Candidates Table --}}
    @if($candidates->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="empty-state-title">{{ $search ? 'Tidak ditemukan' : 'Belum Ada Kandidat' }}</div>
            <div class="empty-state-desc">{{ $search ? 'Tidak ada kandidat yang cocok dengan pencarian "' . $search . '"' : 'Tambahkan kandidat menggunakan tombol di atas.' }}</div>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Kandidat</th>
                        <th>Program Studi</th>
                        <th>Pilihan 1</th>
                        <th>Pilihan 2</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($candidates as $candidate)
                        <tr class="{{ request('edit_candidate') == $candidate->id ? 'bg-indigo-50' : '' }}">
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div class="candidate-avatar">{{ strtoupper(substr($candidate->user->name ?? '?', 0, 1)) }}</div>
                                    <div>
                                        <div style="font-weight:600;">{{ $candidate->user->name ?? '—' }}</div>
                                        <div class="mono" style="font-size:11px; color:var(--color-text-muted);">{{ $candidate->nim }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:13px;">{{ $candidate->prodi }}</div>
                                <div style="font-size:11px; color:var(--color-text-muted);">{{ $candidate->kelas }}</div>
                            </td>
                            <td>
                                @if($candidate->first_choice_department)
                                    <span class="badge badge-indigo">{{ $candidate->first_choice_department->name }}</span>
                                @endif
                            </td>
                            <td>
                                @if($candidate->second_choice_department)
                                    <span class="badge badge-slate">{{ $candidate->second_choice_department->name }}</span>
                                @else
                                    <span style="color:var(--color-text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:6px; justify-content:flex-end;">
                                    <a href="{{ route('admin.testing', array_merge(request()->query(), ['edit_candidate' => $candidate->id])) }}"
                                       onclick="document.getElementById('add-candidate-panel').classList.remove('hidden')"
                                       class="btn btn-ghost btn-xs">Edit</a>
                                    <form action="{{ route('admin.testing.candidates.destroy', $candidate->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus kandidat ini beserta akunnya?')">
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

        {{-- Pagination --}}
        @if($candidates->hasPages())
            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 20px; background:#fafafa; border-top:1px solid var(--color-border);">
                <span style="font-size:12.5px; color:var(--color-text-muted);">Halaman {{ $candidates->currentPage() }} dari {{ $candidates->lastPage() }} ({{ $candidates->total() }} total)</span>
                <div style="display:flex; gap:6px;">
                    @if(!$candidates->onFirstPage())
                        <a href="{{ $candidates->appends(request()->query())->previousPageUrl() }}" class="btn btn-ghost btn-xs">← Prev</a>
                    @endif
                    @if($candidates->hasMorePages())
                        <a href="{{ $candidates->appends(request()->query())->nextPageUrl() }}" class="btn btn-ghost btn-xs">Next →</a>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>

{{-- ============================================================
     STEP 3: INPUT SCORES
============================================================ --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-header-left">
            <div class="step-number">3</div>
            <div>
                <div class="step-title">Input Skor Evaluasi</div>
                <div class="step-desc">Berikan nilai 1–5 untuk setiap kriteria penilaian kandidat</div>
            </div>
        </div>
    </div>

    @if($candidates->isEmpty())
        <div class="empty-state">
            <div class="empty-state-title" style="font-size:13.5px;">Belum ada kandidat untuk dinilai.</div>
        </div>
    @else
        <div>
            @foreach($candidates as $candidate)
                @php
                    $cScores = $existingScores[$candidate->id] ?? [];
                    $filled = count($cScores);
                    $total = $criteria->count();
                    $allFilled = $filled === $total;
                @endphp
                <details class="candidate-accordion">
                    <summary class="candidate-summary">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="candidate-avatar">{{ strtoupper(substr($candidate->user->name ?? '?', 0, 1)) }}</div>
                            <div>
                                <div style="font-size:14px; font-weight:700; color:var(--color-text-primary);">{{ $candidate->user->name }}</div>
                                <div class="mono" style="font-size:11px; color:var(--color-text-muted);">{{ $candidate->nim }}</div>
                            </div>
                        </div>
                        <div style="display:flex; align-items:center; gap:16px;">
                            <div>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span style="font-size:13px; font-weight:700; color:{{ $allFilled ? '#059669' : 'var(--color-text-muted)' }};">{{ $filled }}<span style="font-weight:400; color:var(--color-text-muted); font-size:11px;">/{{ $total }}</span></span>
                                    <div class="progress-bar">
                                        <div class="progress-bar-fill {{ $allFilled ? 'full' : '' }}" style="width:{{ $total > 0 ? ($filled/$total)*100 : 0 }}%;"></div>
                                    </div>
                                </div>
                                <div style="font-size:10.5px; color:var(--color-text-muted); margin-top:2px; text-align:right;">{{ $allFilled ? '✓ Lengkap' : 'Belum lengkap' }}</div>
                            </div>
                            <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:var(--color-text-muted);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </summary>

                    {{-- Score Input --}}
                    <form action="{{ route('admin.testing.save', [$candidate->id, $selectedDepartment->id]) }}"
                          method="POST" id="score-form-{{ $candidate->id }}">
                        @csrf
                        <div class="score-grid">
                            @foreach($criteria as $c)
                                @php $val = $cScores[$c->id] ?? null; @endphp
                                <div class="score-card">
                                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:8px; margin-bottom:8px;">
                                        <div class="score-card-name" title="{{ $c->name }}">{{ \Illuminate\Support\Str::limit($c->name, 28) }}</div>
                                        <div style="display:flex; gap:4px; flex-shrink:0;">
                                            <span class="score-card-type" style="padding:2px 6px; border-radius:10px; font-size:9.5px; background:{{ $c->aspect === 'personal' ? '#dbeafe' : '#dcfce7' }}; color:{{ $c->aspect === 'personal' ? '#1d4ed8' : '#15803d' }};">
                                                {{ $c->aspect === 'personal' ? 'P' : 'O' }}
                                            </span>
                                            <span class="score-card-type {{ $c->type==='core' ? 'chip-core' : 'chip-secondary' }}" style="padding:2px 6px; border-radius:10px; font-size:9.5px;">
                                                {{ $c->type==='core'?'CF':'SF' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        <select name="scores[{{ $c->id }}]"
                                                class="score-select {{ $val ? 'has-score' : '' }}"
                                                onchange="this.className = 'score-select ' + (this.value ? 'has-score' : '')">
                                            <option value="">—</option>
                                            @for($s = 1; $s <= 5; $s++)
                                                <option value="{{ $s }}" {{ $val == $s ? 'selected' : '' }}>{{ $s }}</option>
                                            @endfor
                                        </select>
                                        <span style="font-size:11px; color:var(--color-text-muted); white-space:nowrap;">/{{ $c->target_score }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>

                    @if($filled > 0)
                        <form action="{{ route('admin.testing.reset', [$candidate->id, $selectedDepartment->id]) }}"
                              method="POST" id="reset-form-{{ $candidate->id }}">
                            @csrf @method('DELETE')
                        </form>
                    @endif

                    <div class="score-action-bar">
                        <div class="score-hint">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Nilai kosong diabaikan (tidak menghapus yang sudah ada)
                        </div>
                        <div style="display:flex; gap:8px;">
                            @if($filled > 0)
                                <button type="submit" form="reset-form-{{ $candidate->id }}"
                                        onclick="return confirm('Hapus semua skor untuk kandidat ini?')"
                                        class="btn btn-danger btn-sm">
                                    Reset Skor
                                </button>
                            @endif
                            <button type="submit" form="score-form-{{ $candidate->id }}" class="btn btn-primary btn-sm">
                                Simpan Skor
                            </button>
                        </div>
                    </div>
                </details>
            @endforeach
        </div>

        @if($candidates->hasPages())
            <div style="padding:12px 20px; border-top:1px solid var(--color-border); background:#fafafa; text-align:center;">
                <span style="font-size:12px; color:var(--color-text-muted);">Gunakan paginasi pada Langkah 2 untuk menilai kandidat di halaman lain.</span>
            </div>
        @endif
    @endif
</div>

{{-- ============================================================
     STEP 4: RANKINGS
============================================================ --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-header-left">
            <div class="step-number">4</div>
            <div>
                <div class="step-title">Hasil Akhir Profile Matching</div>
                <div class="step-desc">Peringkat diurutkan berdasarkan skor akhir Profile Matching</div>
            </div>
        </div>
        <span class="badge badge-slate">{{ count($rankings) }} kandidat</span>
    </div>

    @if(empty($rankings))
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="empty-state-title">Belum Ada Perhitungan</div>
            <div class="empty-state-desc">Input dan simpan skor minimal 1 kandidat untuk melihat hasil ranking DSS.</div>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:60px; text-align:center;">Rank</th>
                        <th>Kandidat</th>
                        <th>Personal <span style="font-weight:400; text-transform:none; font-size:10px;">({{ $selectedDepartment->personal_aspect_weight }}%)</span></th>
                        <th>Organizational <span style="font-weight:400; text-transform:none; font-size:10px;">({{ $selectedDepartment->organizational_aspect_weight }}%)</span></th>
                        <th style="color:var(--color-brand);">Total Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rankings as $idx => $r)
                        @php $rank = $idx + 1; $isTop = $rank === 1; @endphp
                        <tr class="{{ $isTop ? 'row-top' : '' }}">
                            <td style="text-align:center;">
                                <span class="rank-badge rank-{{ $rank <= 3 ? $rank : 'n' }}">{{ $rank }}</span>
                            </td>
                            <td>
                                <div style="font-weight:700; color:{{ $isTop ? '#b45309' : 'var(--color-text-primary)' }}; font-size:14px;">{{ $r['candidate']->user->name ?? '—' }}</div>
                                <div class="mono" style="font-size:11px; color:var(--color-text-muted);">{{ $r['candidate']->nim }}</div>
                            </td>
                            <td class="mono" style="font-size:13px; color:var(--color-text-secondary); font-weight:600;">{{ number_format($r['personal_score'] ?? 0, 4) }}</td>
                            <td class="mono" style="font-size:13px; color:var(--color-text-secondary); font-weight:600;">{{ number_format($r['organizational_score'] ?? 0, 4) }}</td>
                            <td>
                                <span class="mono" style="font-size:16px; font-weight:800; color:{{ $isTop ? '#d97706' : 'var(--color-brand)' }};">
                                    {{ number_format($r['total_score'], 4) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endif {{-- end if selectedDepartment && criteria->isNotEmpty() --}}

@endsection

@push('scripts')
<script>
function toggleAddPanel() {
    const panel = document.getElementById('add-candidate-panel');
    panel.classList.toggle('hidden');
}

// Close add panel button
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any() && old('nim') !== null || request('action') == 'add_candidate')
        const panel = document.getElementById('add-candidate-panel');
        if (panel) panel.classList.remove('hidden');
    @endif
});
</script>
@endpush
