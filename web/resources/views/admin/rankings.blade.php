@extends('layouts.admin')

@section('title', 'Ranking — ' . $department->name)
@section('subtitle', 'Hasil Profile Matching DSS dan keputusan penerimaan kandidat')

@section('topbar-actions')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Dashboard
    </a>

    {{-- Publish toggle --}}
    @php
        $anyPublished = $announcements->where('is_published', true)->count() > 0;
    @endphp
    <form action="{{ route('admin.publish') }}" method="POST" style="display:inline;">
        @csrf
        <input type="hidden" name="is_published" value="{{ $anyPublished ? '0' : '1' }}">
        <button type="submit" class="btn btn-sm {{ $anyPublished ? 'btn-danger' : 'btn-success' }}"
                onclick="return confirm('{{ $anyPublished ? 'Sembunyikan semua pengumuman dari papan publik?' : 'Publikasikan semua hasil ke papan pengumuman publik?' }}')">
            {{ $anyPublished ? '📵 Cabut Publikasi' : '📢 Publikasikan Hasil' }}
        </button>
    </form>
@endsection

@push('styles')
<style>
    .modal-backdrop {
        position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 500;
        display: flex; align-items: center; justify-content: center; padding: 16px;
        backdrop-filter: blur(3px);
    }
    .modal-box {
        background: white; border-radius: 14px; width: 100%; max-width: 440px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden;
        animation: modalSlide 0.25s cubic-bezier(0.34,1.56,0.64,1);
    }
    @keyframes modalSlide { from { transform:translateY(20px) scale(0.97); opacity:0; } to { transform:translateY(0) scale(1); opacity:1; } }
    .modal-header { display:flex; align-items:center; justify-content:space-between; padding:18px 22px; border-bottom:1px solid var(--color-border); }
    .modal-title { font-size:15px; font-weight:700; color:var(--color-text-primary); }
    .modal-close { width:28px; height:28px; border-radius:6px; background:#f1f5f9; border:none; cursor:pointer; color:var(--color-text-secondary); display:flex; align-items:center; justify-content:center; font-size:16px; }
    .modal-close:hover { background:#fee2e2; color:#dc2626; }
    .modal-body { padding:22px; }
    .modal-footer { display:flex; justify-content:flex-end; gap:8px; padding:14px 22px; border-top:1px solid var(--color-border); background:#fafafa; }

    /* Rank badges */
    .rank-badge {
        width: 32px; height: 32px; border-radius: 50%; display: inline-flex;
        align-items: center; justify-content: center; font-size: 13px; font-weight: 800;
        flex-shrink: 0;
    }
    .rank-1 { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; box-shadow: 0 3px 10px rgba(245,158,11,0.4); }
    .rank-2 { background: linear-gradient(135deg, #94a3b8, #cbd5e1); color: white; }
    .rank-3 { background: linear-gradient(135deg, #b45309, #d97706); color: white; }
    .rank-n { background: #f1f5f9; color: #64748b; }

    .row-top {
        background: linear-gradient(to right, rgba(254,240,138,0.25), transparent);
    }

    .score-bar { height: 5px; border-radius: 3px; background: #e2e8f0; margin-top: 4px; overflow: hidden; }
    .score-bar-fill { height: 100%; border-radius: 3px; background: linear-gradient(to right, #6366f1, #8b5cf6); }

    /* Department tabs */
    .dept-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
    .dept-tab {
        padding: 7px 14px; border-radius: 8px; font-size: 12.5px; font-weight: 600;
        text-decoration: none; border: 1px solid var(--color-border);
        color: var(--color-text-secondary); background: white;
        transition: all 0.15s;
    }
    .dept-tab:hover { background: #f8fafc; color: var(--color-text-primary); }
    .dept-tab.active { background: var(--color-brand); color: white; border-color: var(--color-brand); }

    .decision-accepted { color: #065f46; }
    .decision-rejected { color: #9f1239; }
    .decision-pending { color: #92400e; }
    body.admin-theme-enabled[data-theme="dark"] .ranking-avatar {
        background: linear-gradient(135deg, #312e81, #581c87) !important;
        color: #c7d2fe !important;
    }
</style>
@endpush

@section('content')

    {{-- Department Tabs --}}
    @php
        $allDepartments = \App\Models\Departmentsbiro::orderBy('name')->get();
    @endphp
    <div class="dept-tabs">
        @foreach($allDepartments as $d)
            <a href="{{ route('admin.rankings', $d) }}"
               class="dept-tab {{ $d->id === $department->id ? 'active' : '' }}">
                {{ $d->name }}
            </a>
        @endforeach
    </div>

    {{-- Info Cards --}}
    <div class="grid-4 page-section" style="margin-bottom:20px;">
        <div class="stat-card" style="padding:16px;">
            <div class="stat-label">Total Dievaluasi</div>
            <div class="stat-value" style="font-size:22px; color:var(--color-brand);">{{ count($rankings) }}</div>
        </div>
        <div class="stat-card" style="padding:16px;">
            <div class="stat-label">Core Factor</div>
            <div class="stat-value" style="font-size:22px;">{{ $department->core_factor_weight }}<span style="font-size:14px; font-weight:600; color:var(--color-text-muted);">%</span></div>
        </div>
        <div class="stat-card" style="padding:16px;">
            <div class="stat-label">Secondary Factor</div>
            <div class="stat-value" style="font-size:22px;">{{ $department->secondary_factor_weight }}<span style="font-size:14px; font-weight:600; color:var(--color-text-muted);">%</span></div>
        </div>
        <div class="stat-card" style="padding:16px;">
            <div class="stat-label">Sudah Diputuskan</div>
            <div class="stat-value" style="font-size:22px; color:#059669;">{{ $announcements->count() }}</div>
        </div>
    </div>

    {{-- Rankings Table --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <div class="admin-card-title">
                    Peringkat Kandidat — {{ $department->name }}
                </div>
                <div style="font-size:12px; color:var(--color-text-muted); margin-top:2px;">
                    Diurutkan berdasarkan Total Skor Profile Matching (NTotal)
                </div>
            </div>

            <a href="{{ route('admin.criteria', $department) }}" class="btn btn-ghost btn-sm" style="font-size:11.5px;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Kelola Kriteria
            </a>
        </div>

        @if(empty($rankings))
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="empty-state-title">Belum Ada Data Ranking</div>
                <div class="empty-state-desc">Lakukan penilaian kandidat melalui halaman Profile Matching untuk melihat peringkat di sini.</div>
                <div style="margin-top:16px;">
                    <a href="{{ route('admin.testing', ['department_id' => $department->id]) }}" class="btn btn-primary">
                        Mulai Penilaian →
                    </a>
                </div>
            </div>
        @else
            @php $maxScore = collect($rankings)->max('total_score') ?: 1; @endphp
            <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width:60px; text-align:center;">Rank</th>
                            <th>Kandidat</th>
                            <th>Program Studi</th>
                            <th>Personal <span style="font-weight:500; text-transform:none;">({{ $department->personal_aspect_weight }}%)</span></th>
                            <th>Organizational <span style="font-weight:500; text-transform:none;">({{ $department->organizational_aspect_weight }}%)</span></th>
                            <th style="color:var(--color-brand);">Total Skor</th>
                            <th>Status Keputusan</th>
                            <th style="text-align:right;">Keputusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rankings as $idx => $r)
                            @php
                                $rank = $idx + 1;
                                $candidate = $r['candidate'];
                                $announcement = $announcements[$candidate->id] ?? null;
                                $pct = $maxScore > 0 ? ($r['total_score'] / $maxScore) * 100 : 0;
                            @endphp
                            <tr class="{{ $rank === 1 ? 'row-top' : '' }}">
                                <td style="text-align:center;">
                                    <span class="rank-badge rank-{{ $rank <= 3 ? $rank : 'n' }}">
                                        {{ $rank <= 3 ? ['🥇','🥈','🥉'][$rank-1] : $rank }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div class="ranking-avatar" style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#e0e7ff,#ede9fe); color:#4f46e5; font-weight:700; font-size:13px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            {{ strtoupper(substr($candidate->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:700; font-size:13.5px; color:{{ $rank===1 ? '#b45309' : 'var(--color-text-primary)' }};">
                                                {{ $candidate->user->name ?? '—' }}
                                            </div>
                                            <div class="mono" style="font-size:11px; color:var(--color-text-muted);">{{ $candidate->nim }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:12.5px; color:var(--color-text-secondary);">{{ $candidate->prodi }}</div>
                                    <div style="font-size:11px; color:var(--color-text-muted);">{{ $candidate->kelas }}</div>
                                </td>
                                <td>
                                    <div class="mono" style="font-size:13px; font-weight:600; color:var(--color-text-secondary);">
                                        {{ number_format($r['personal_score'] ?? 0, 4) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="mono" style="font-size:13px; font-weight:600; color:var(--color-text-secondary);">
                                        {{ number_format($r['organizational_score'] ?? 0, 4) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="mono" style="font-size:16px; font-weight:800; color:{{ $rank===1 ? '#d97706' : 'var(--color-brand)' }};">
                                        {{ number_format($r['total_score'], 4) }}
                                    </div>
                                    <div class="score-bar" style="width:80px;">
                                        <div class="score-bar-fill" style="width:{{ $pct }}%;"></div>
                                    </div>
                                </td>
                                <td>
                                    @if($announcement)
                                        @if($announcement->status === 'accepted')
                                            <span class="badge badge-emerald">✓ Diterima</span>
                                            @if($announcement->assignedDepartment)
                                                <div style="font-size:11px; color:var(--color-text-muted); margin-top:3px;">→ {{ $announcement->assignedDepartment->name }}</div>
                                            @endif
                                        @else
                                            <span class="badge badge-rose">✕ Ditolak</span>
                                        @endif
                                        @if($announcement->is_published)
                                            <div style="font-size:10px; color:#059669; margin-top:2px; font-weight:600;">● Dipublikasikan</div>
                                        @endif
                                    @else
                                        <span class="badge badge-amber">⏳ Belum Diputuskan</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex; justify-content:flex-end;">
                                        <button onclick="openDecideModal({{ $candidate->id }}, {{ json_encode($candidate->user->name ?? 'Kandidat') }}, {{ json_encode($announcement?->status) }}, {{ json_encode($announcement?->assigned_department_id) }})"
                                                class="btn btn-ghost btn-xs">
                                            {{ $announcement ? 'Ubah Keputusan' : 'Buat Keputusan' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ===== DECIDE MODAL ===== --}}
    <div id="decide-modal" class="modal-backdrop hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Keputusan Penerimaan</div>
                <button class="modal-close" onclick="document.getElementById('decide-modal').classList.add('hidden')">×</button>
            </div>
            <form id="decide-form" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div style="margin-bottom:16px; padding:12px; background:#f8fafc; border-radius:8px; border:1px solid var(--color-border);">
                        <div style="font-size:12px; color:var(--color-text-muted);">Kandidat:</div>
                        <div id="decide-candidate-name" style="font-size:15px; font-weight:700; color:var(--color-text-primary); margin-top:2px;"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keputusan <span style="color:#dc2626;">*</span></label>
                        <select name="status" id="decide_status" class="form-select" required onchange="toggleDeptField()">
                            <option value="">— Pilih Keputusan —</option>
                            <option value="accepted">✓ Terima</option>
                            <option value="rejected">✕ Tolak</option>
                        </select>
                    </div>

                    <div id="dept-field" class="form-group" style="display:none; margin-bottom:0;">
                        <label class="form-label">Ditempatkan di Department <span style="color:#dc2626;">*</span></label>
                        <select name="assigned_department_id" id="decide_dept" class="form-select">
                            <option value="">— Pilih Department —</option>
                            @foreach($allDepartments as $d)
                                <option value="{{ $d->id }}" {{ $d->id === $department->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="document.getElementById('decide-modal').classList.add('hidden')" class="btn btn-ghost btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>

    function toggleDeptField() {
        const status = document.getElementById('decide_status').value;
        const deptField = document.getElementById('dept-field');
        deptField.style.display = status === 'accepted' ? 'block' : 'none';
        if (status === 'accepted') {
            document.getElementById('decide_dept').required = true;
        } else {
            document.getElementById('decide_dept').required = false;
        }
    }

    function openDecideModal(candidateId, name, currentStatus, currentDeptId) {
        document.getElementById('decide-form').action = '/admin/decide/' + candidateId;
        document.getElementById('decide-candidate-name').textContent = name;

        const statusSel = document.getElementById('decide_status');
        statusSel.value = currentStatus || '';

        if (currentDeptId) {
            document.getElementById('decide_dept').value = currentDeptId;
        }

        toggleDeptField();
        document.getElementById('decide-modal').classList.remove('hidden');
    }

    document.getElementById('decide-modal')?.addEventListener('click', e => {
        if (e.target === document.getElementById('decide-modal')) {
            document.getElementById('decide-modal').classList.add('hidden');
        }
    });
</script>
@endpush
