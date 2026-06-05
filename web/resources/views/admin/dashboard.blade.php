@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang di panel administrasi HIMATIK DSS')

@push('styles')
<style>
    .stat-card { cursor: default; }
    .stat-icon-indigo { background: #e0e7ff; color: #4338ca; }
    .stat-icon-violet { background: #ede9fe; color: #6d28d9; }
    .stat-icon-emerald { background: #d1fae5; color: #065f46; }
    .stat-icon-amber { background: #fef3c7; color: #92400e; }

    .recent-table-wrapper { overflow-x: auto; }
    .candidate-avatar {
        width: 32px; height: 32px; border-radius: 50%;
        background: linear-gradient(135deg, #e0e7ff, #ede9fe);
        color: #4f46e5; font-weight: 700; font-size: 13px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }

    .quick-action-item {
        display: flex; align-items: center; gap: 14px;
        padding: 13px 16px; border-radius: 10px; cursor: pointer;
        text-decoration: none; transition: all 0.15s ease;
        border: 1px solid transparent;
    }
    .quick-action-item:hover { background: #f8fafc; border-color: var(--color-border); }
    .quick-action-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .quick-action-title { font-size: 13.5px; font-weight: 600; color: var(--color-text-primary); }
    .quick-action-desc { font-size: 11.5px; color: var(--color-text-muted); margin-top: 1px; }

    .dept-item { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
    .dept-item:last-child { border-bottom: none; }
    .dept-bar { height: 4px; border-radius: 4px; background: #e2e8f0; overflow: hidden; margin-top: 4px; }
    .dept-bar-fill { height: 100%; border-radius: 4px; background: linear-gradient(to right, #6366f1, #8b5cf6); transition: width 0.6s ease; }

    body.admin-theme-enabled[data-theme="dark"] .quick-action-item:hover {
        background: #1e293b;
        border-color: var(--color-border);
    }
    body.admin-theme-enabled[data-theme="dark"] .candidate-avatar {
        background: linear-gradient(135deg, #312e81, #581c87);
        color: #c7d2fe;
    }
    body.admin-theme-enabled[data-theme="dark"] .dept-item {
        border-bottom-color: #1e293b;
    }
    body.admin-theme-enabled[data-theme="dark"] .dept-bar {
        background: #334155;
    }
    body.admin-theme-enabled[data-theme="dark"] .stat-icon-indigo { background: #312e81; color: #c7d2fe; }
    body.admin-theme-enabled[data-theme="dark"] .stat-icon-violet { background: #4c1d95; color: #ddd6fe; }
    body.admin-theme-enabled[data-theme="dark"] .stat-icon-emerald { background: #064e3b; color: #a7f3d0; }
    body.admin-theme-enabled[data-theme="dark"] .stat-icon-amber { background: #78350f; color: #fde68a; }
</style>
@endpush

@section('content')

    {{-- Stat Cards --}}
    <div class="grid-4 page-section">
        <div class="stat-card">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                <div>
                    <div class="stat-label">Total Kandidat</div>
                    <div class="stat-value">{{ $stats['total_candidates'] }}</div>
                    <div style="font-size:12px; color:var(--color-text-muted); margin-top:6px;">Terdaftar di sistem</div>
                </div>
                <div class="stat-icon stat-icon-indigo">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                <div>
                    <div class="stat-label">Departments / Biro</div>
                    <div class="stat-value">{{ $stats['total_departments'] }}</div>
                    <div style="font-size:12px; color:var(--color-text-muted); margin-top:6px;">Unit organisasi aktif</div>
                </div>
                <div class="stat-icon stat-icon-violet">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                <div>
                    <div class="stat-label">Kriteria Evaluasi</div>
                    <div class="stat-value">{{ $stats['total_criteria'] }}</div>
                    <div style="font-size:12px; color:var(--color-text-muted); margin-top:6px;">Kriteria penilaian aktif</div>
                </div>
                <div class="stat-icon stat-icon-emerald">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                <div>
                    <div class="stat-label">Telah Dievaluasi</div>
                    <div class="stat-value">{{ $stats['total_evaluated'] }}</div>
                    <div style="font-size:12px; color:var(--color-text-muted); margin-top:6px;">Kandidat dengan skor</div>
                </div>
                <div class="stat-icon stat-icon-amber">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div style="display:grid; grid-template-columns: 1fr 320px; gap:20px; align-items:start;">

        {{-- Recent Candidates --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <div class="admin-card-title">Kandidat Terbaru</div>
                    <div style="font-size:12px; color:var(--color-text-muted); margin-top:2px;">5 pendaftar terakhir</div>
                </div>
                <a href="{{ route('admin.testing') }}" class="btn btn-sm btn-secondary">
                    Lihat Semua
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if($recentCandidates->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="empty-state-title">Belum Ada Kandidat</div>
                    <div class="empty-state-desc">Belum ada kandidat yang terdaftar di sistem ini.</div>
                </div>
            @else
                <div class="recent-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Kandidat</th>
                                <th>Program Studi</th>
                                <th>Pilihan 1</th>
                                <th>Pilihan 2</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentCandidates as $candidate)
                                <tr>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <div class="candidate-avatar">{{ strtoupper(substr($candidate->user->name ?? '?', 0, 1)) }}</div>
                                            <div>
                                                <div style="font-weight:600; font-size:13.5px;">{{ $candidate->user->name ?? '—' }}</div>
                                                <div class="mono" style="font-size:11px; color:var(--color-text-muted);">{{ $candidate->nim }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size:13px; color:var(--color-text-secondary);">{{ $candidate->prodi }}</div>
                                        <div style="font-size:11px; color:var(--color-text-muted);">{{ $candidate->kelas }}</div>
                                    </td>
                                    <td>
                                        @if($candidate->first_choice_department)
                                            <span class="badge badge-indigo">{{ $candidate->first_choice_department->name }}</span>
                                        @else
                                            <span style="color:var(--color-text-muted);">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($candidate->second_choice_department)
                                            <span class="badge badge-slate">{{ $candidate->second_choice_department->name }}</span>
                                        @else
                                            <span style="color:var(--color-text-muted);">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Right Column --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            {{-- Quick Actions --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">Aksi Cepat</div>
                </div>
                <div style="padding: 8px;">
                    <a href="{{ route('admin.testing') }}" class="quick-action-item">
                        <div class="quick-action-icon" style="background:#e0e7ff; color:#4338ca;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="quick-action-title">Mulai Profile Matching</div>
                            <div class="quick-action-desc">Evaluasi kandidat per department</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.schedules') }}" class="quick-action-item">
                        <div class="quick-action-icon" style="background:#d1fae5; color:#065f46;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="quick-action-title">Kelola Jadwal</div>
                            <div class="quick-action-desc">Buat dan atur slot interview</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.interviewers') }}" class="quick-action-item">
                        <div class="quick-action-icon" style="background:#fef3c7; color:#92400e;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="quick-action-title">Tambah Interviewer</div>
                            <div class="quick-action-desc">Kelola akun pewawancara</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Department Overview --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">Department Overview</div>
                </div>
                <div style="padding: 16px;">
                    @php
                        $maxCandidates = $departments->map(fn($d) => $d->first_choice_candidates_count + $d->second_choice_candidates_count)->max() ?: 1;
                    @endphp
                    @foreach($departments as $dept)
                        @php
                            $total = $dept->first_choice_candidates_count + $dept->second_choice_candidates_count;
                            $pct = $maxCandidates > 0 ? ($total / $maxCandidates) * 100 : 0;
                        @endphp
                        <div class="dept-item">
                            <div style="flex:1; min-width:0;">
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:3px;">
                                    <span style="font-size:12.5px; font-weight:600; color:var(--color-text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:140px;" title="{{ $dept->name }}">{{ $dept->name }}</span>
                                    <div style="display:flex; gap:5px; flex-shrink:0; margin-left:8px;">
                                        <span class="badge badge-indigo" style="font-size:10px;">P1: {{ $dept->first_choice_candidates_count }}</span>
                                        <span class="badge badge-slate" style="font-size:10px;">P2: {{ $dept->second_choice_candidates_count }}</span>
                                    </div>
                                </div>
                                <div class="dept-bar">
                                    <div class="dept-bar-fill" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if($departments->isEmpty())
                        <div style="text-align:center; padding:16px 0; color:var(--color-text-muted); font-size:13px;">Belum ada department.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Responsive grid adjustment
    const checkGrid = () => {
        const grid = document.querySelector('.grid-4');
        if (grid && window.innerWidth < 768) {
            grid.style.gridTemplateColumns = 'repeat(2, 1fr)';
        }
    };
    checkGrid();
    window.addEventListener('resize', checkGrid);
</script>
@endpush
