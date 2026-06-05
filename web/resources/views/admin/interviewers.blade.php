@extends('layouts.admin')

@section('title', 'Manajemen Interviewer')
@section('subtitle', 'Kelola akun pewawancara yang bertugas di proses rekrutmen')

@section('topbar-actions')
    <button onclick="document.getElementById('add-interviewer-modal').classList.remove('hidden')"
            class="btn btn-primary btn-sm">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Interviewer
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
        background: white; border-radius: 14px; width: 100%; max-width: 480px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden;
        animation: modalSlide 0.25s cubic-bezier(0.34,1.56,0.64,1);
    }
    @keyframes modalSlide { from { transform:translateY(20px) scale(0.97); opacity:0; } to { transform:translateY(0) scale(1); opacity:1; } }
    .modal-header { display:flex; align-items:center; justify-content:space-between; padding:18px 22px; border-bottom:1px solid var(--color-border); }
    .modal-title { font-size:15px; font-weight:700; color:var(--color-text-primary); }
    .modal-close { width:28px; height:28px; border-radius:6px; background:#f1f5f9; border:none; cursor:pointer; color:var(--color-text-secondary); display:flex; align-items:center; justify-content:center; transition:all 0.15s; font-size:16px; }
    .modal-close:hover { background:#fee2e2; color:#dc2626; }
    .modal-body { padding:22px; }
    .modal-footer { display:flex; justify-content:flex-end; gap:8px; padding:14px 22px; border-top:1px solid var(--color-border); background:#fafafa; }

    .interviewer-card {
        background: white; border:1px solid var(--color-border); border-radius:12px;
        padding:16px 20px; display:flex; align-items:center; gap:16px;
        transition:box-shadow 0.2s, transform 0.2s;
    }
    .interviewer-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.07); transform: translateY(-1px); }
    .interviewer-avatar {
        width:46px; height:46px; border-radius:50%;
        background: linear-gradient(135deg, #ede9fe, #ddd6fe);
        color:#5b21b6; font-weight:700; font-size:18px;
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .interviewer-info { flex:1; min-width:0; }
    .interviewer-name { font-size:14px; font-weight:700; color:var(--color-text-primary); }
    .interviewer-email { font-size:12px; color:var(--color-text-muted); margin-top:2px; font-family:'JetBrains Mono', monospace; }
    .interviewer-actions { display:flex; gap:6px; flex-shrink:0; }

    .hint-text { font-size:11.5px; color:var(--color-text-muted); margin-top:4px; }
</style>
@endpush

@section('content')

    {{-- Stats --}}
    <div style="display:flex; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
        <div class="stat-card" style="padding:16px; flex:1; min-width:160px; max-width:200px;">
            <div class="stat-label">Total Interviewer</div>
            <div class="stat-value" style="font-size:24px;">{{ $interviewers->count() }}</div>
        </div>
    </div>

    {{-- Cards Grid --}}
    @if($interviewers->isEmpty())
        <div class="admin-card">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="empty-state-title">Belum Ada Interviewer</div>
                <div class="empty-state-desc">Tambahkan akun interviewer untuk mulai menugaskan mereka pada jadwal interview.</div>
                <div style="margin-top:16px;">
                    <button onclick="document.getElementById('add-interviewer-modal').classList.remove('hidden')" class="btn btn-primary">
                        + Tambah Interviewer Pertama
                    </button>
                </div>
            </div>
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:10px;">
            @foreach($interviewers as $iv)
                <div class="interviewer-card">
                    <div class="interviewer-avatar">
                        {{ strtoupper(substr($iv->name, 0, 1)) }}
                    </div>
                    <div class="interviewer-info">
                        <div class="interviewer-name">{{ $iv->name }}</div>
                        <div class="interviewer-email">{{ $iv->email }}</div>
                    </div>
                    <span class="badge badge-violet" style="flex-shrink:0;">Interviewer</span>
                    <div class="interviewer-actions">
                        <button onclick="openEditIvModal({{ $iv->id }}, {{ json_encode($iv->name) }}, {{ json_encode($iv->email) }})"
                                class="btn btn-ghost btn-sm">
                            Edit
                        </button>
                        <form action="{{ route('admin.interviewers.destroy', $iv) }}" method="POST"
                              onsubmit="return confirm('Hapus akun interviewer {{ $iv->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ===== ADD MODAL ===== --}}
    <div id="add-interviewer-modal" class="modal-backdrop hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Tambah Interviewer Baru</div>
                <button class="modal-close" onclick="document.getElementById('add-interviewer-modal').classList.add('hidden')">×</button>
            </div>
            <form action="{{ route('admin.interviewers.post') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="name" class="form-input" placeholder="Nama pewawancara" required value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Email <span style="color:#dc2626;">*</span></label>
                        <input type="email" name="email" class="form-input" placeholder="email@example.com" required value="{{ old('email') }}">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Password <span style="color:#dc2626;">*</span></label>
                        <input type="password" name="password" class="form-input" placeholder="Min. 8 karakter" required minlength="8">
                        <div class="hint-text">Password harus minimal 8 karakter.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="document.getElementById('add-interviewer-modal').classList.add('hidden')" class="btn btn-ghost btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Tambah Interviewer</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== EDIT MODAL ===== --}}
    <div id="edit-interviewer-modal" class="modal-backdrop hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Edit Interviewer</div>
                <button class="modal-close" onclick="document.getElementById('edit-interviewer-modal').classList.add('hidden')">×</button>
            </div>
            <form id="edit-iv-form" action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="name" id="edit_iv_name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Email <span style="color:#dc2626;">*</span></label>
                        <input type="email" name="email" id="edit_iv_email" class="form-input" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Password Baru <span style="color:var(--color-text-muted);">(Opsional)</span></label>
                        <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak ingin mengubah" minlength="8">
                        <div class="hint-text">Isi hanya jika ingin mengganti password.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="document.getElementById('edit-interviewer-modal').classList.add('hidden')" class="btn btn-ghost btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
function openEditIvModal(id, name, email) {
    document.getElementById('edit-iv-form').action = '/admin/interviewers/' + id;
    document.getElementById('edit_iv_name').value = name;
    document.getElementById('edit_iv_email').value = email;
    document.getElementById('edit-interviewer-modal').classList.remove('hidden');
}

['add-interviewer-modal', 'edit-interviewer-modal'].forEach(id => {
    const modal = document.getElementById(id);
    if (modal) {
        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.add('hidden');
        });
    }
});

@if($errors->any() && old('name') !== null && old('email') !== null)
    document.getElementById('add-interviewer-modal').classList.remove('hidden');
@endif
</script>
@endpush