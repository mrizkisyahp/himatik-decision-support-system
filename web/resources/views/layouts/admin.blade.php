<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HIMATIK DSS Admin Panel — Sistem Pendukung Keputusan Rekrutmen">
    <title>@yield('title', 'Admin Dashboard') — HIMATIK DSS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --color-brand: #4f46e5;
            --color-brand-dark: #3730a3;
            --color-brand-light: #e0e7ff;
            --color-surface: #ffffff;
            --color-surface-alt: #f8fafc;
            --color-border: #e2e8f0;
            --color-text-primary: #0f172a;
            --color-text-secondary: #64748b;
            --color-text-muted: #94a3b8;
            --color-success: #059669;
            --color-error: #dc2626;
            --color-warning: #d97706;
            --color-info: #0284c7;
        }

        body.admin-theme-enabled[data-theme="dark"] {
            --color-brand-light: #312e81;
            --color-surface: #111827;
            --color-surface-alt: #0f172a;
            --color-border: #243244;
            --color-text-primary: #e5e7eb;
            --color-text-secondary: #cbd5e1;
            --color-text-muted: #94a3b8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        /* Critical: override any display property from component classes */
        .hidden { display: none !important; }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: var(--color-surface-alt);
            color: var(--color-text-primary);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
        }

        .mono { font-family: 'JetBrains Mono', monospace; }

        /* ===== SIDEBAR ===== */
        #admin-sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            height: 100vh;
            background: #fff;
            border-right: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 200;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
        }

        #admin-sidebar::-webkit-scrollbar { width: 4px; }
        #admin-sidebar::-webkit-scrollbar-track { background: transparent; }
        #admin-sidebar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--color-border);
        }

        .sidebar-logo-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 900;
            font-size: 16px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .sidebar-logo-text { line-height: 1; }
        .sidebar-logo-title { font-size: 15px; font-weight: 800; color: var(--color-text-primary); letter-spacing: -0.3px; }
        .sidebar-logo-sub { font-size: 10px; font-weight: 600; color: var(--color-brand); text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }

        .sidebar-nav { flex: 1; padding: 12px 10px; }

        .sidebar-section-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 10px;
            margin: 16px 0 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--color-text-secondary);
            text-decoration: none;
            transition: all 0.15s ease;
            position: relative;
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background: #f8fafc;
            color: var(--color-text-primary);
        }

        .sidebar-link.active {
            background: var(--color-brand-light);
            color: var(--color-brand);
            font-weight: 600;
        }

        .sidebar-link.active .sidebar-icon { color: var(--color-brand); }

        .sidebar-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .sidebar-link.active .sidebar-icon { opacity: 1; }

        .sidebar-user {
            padding: 14px 16px;
            border-top: 1px solid var(--color-border);
        }

        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .sidebar-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .sidebar-user-name { font-size: 13px; font-weight: 600; color: var(--color-text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-email { font-size: 11px; color: var(--color-text-muted); font-family: 'JetBrains Mono', monospace; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .btn-logout {
            display: block;
            width: 100%;
            padding: 8px;
            background: #f8fafc;
            border: 1px solid var(--color-border);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-secondary);
            cursor: pointer;
            text-align: center;
            transition: all 0.15s ease;
        }

        .btn-logout:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
        }

        /* ===== MAIN CONTENT ===== */
        #admin-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== TOPBAR ===== */
        #admin-topbar {
            height: var(--topbar-height);
            background: white;
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; }

        #mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            color: var(--color-text-secondary);
            transition: all 0.15s;
        }

        #mobile-menu-btn:hover { background: var(--color-surface-alt); color: var(--color-text-primary); }

        .topbar-breadcrumb { display: flex; flex-direction: column; }
        .topbar-title { font-size: 16px; font-weight: 700; color: var(--color-text-primary); line-height: 1.2; }
        .topbar-subtitle { font-size: 12px; color: var(--color-text-muted); margin-top: 1px; }

        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .topbar-actions-slot { display: flex; align-items: center; gap: 8px; }

        .theme-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid var(--color-border);
            background: var(--color-surface);
            color: var(--color-text-secondary);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .theme-toggle:hover {
            color: var(--color-text-primary);
            border-color: #a5b4fc;
            background: var(--color-surface-alt);
        }
        .theme-toggle .theme-icon-dark { display: none; }
        body.admin-theme-enabled[data-theme="dark"] .theme-toggle .theme-icon-light { display: none; }
        body.admin-theme-enabled[data-theme="dark"] .theme-toggle .theme-icon-dark { display: block; }

        .topbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ede9fe;
            color: #5b21b6;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid #c4b5fd;
        }

        /* ===== PAGE CONTENT ===== */
        #admin-page-content {
            flex: 1;
            padding: 28px 28px 40px;
        }

        /* ===== FLASH MESSAGES ===== */
        .flash-message {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13.5px;
            animation: flashIn 0.4s ease;
        }

        @keyframes flashIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

        .flash-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 900;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .flash-success .flash-icon { background: #dcfce7; color: #16a34a; }

        .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        .flash-error .flash-icon { background: #fee2e2; color: #dc2626; }

        .flash-info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; }
        .flash-info .flash-icon { background: #dbeafe; color: #2563eb; }

        .flash-warning { background: #fffbeb; border: 1px solid #fde68a; color: #b45309; }
        .flash-warning .flash-icon { background: #fef3c7; color: #d97706; }

        /* ===== FOOTER ===== */
        #admin-footer {
            padding: 16px 28px;
            border-top: 1px solid var(--color-border);
            font-size: 12px;
            color: var(--color-text-muted);
            background: white;
        }

        /* ===== OVERLAY ===== */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 150;
            backdrop-filter: blur(2px);
        }

        /* ===== SHARED COMPONENT STYLES ===== */

        /* Cards */
        .admin-card {
            background: white;
            border: 1px solid var(--color-border);
            border-radius: 12px;
            overflow: hidden;
        }

        .admin-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--color-border);
            background: #fafafa;
        }

        .admin-card-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--color-text-primary);
        }

        .admin-card-body { padding: 20px; }

        /* Stat Cards */
        .stat-card {
            background: white;
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 20px;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .stat-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            transform: translateY(-1px);
        }

        .stat-label { font-size: 11px; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; }
        .stat-value { font-size: 28px; font-weight: 800; color: var(--color-text-primary); line-height: 1; }
        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Tables */
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--color-text-muted);
            background: #f8fafc;
            padding: 10px 16px;
            text-align: left;
            border-bottom: 1px solid var(--color-border);
            white-space: nowrap;
        }
        .admin-table td {
            padding: 12px 16px;
            font-size: 13.5px;
            color: var(--color-text-primary);
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .admin-table tbody tr:last-child td { border-bottom: none; }
        .admin-table tbody tr:hover td { background: #f8fafc; }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-indigo { background: #e0e7ff; color: #4338ca; }
        .badge-violet { background: #ede9fe; color: #6d28d9; }
        .badge-emerald { background: #d1fae5; color: #065f46; }
        .badge-amber { background: #fef3c7; color: #92400e; }
        .badge-rose { background: #fee2e2; color: #9f1239; }
        .badge-slate { background: #f1f5f9; color: #475569; }
        .badge-blue { background: #dbeafe; color: #1e40af; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.15s ease;
            line-height: 1;
            white-space: nowrap;
        }

        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; }
        .btn-xs { padding: 4px 9px; font-size: 11px; border-radius: 5px; }

        .btn-primary { background: var(--color-brand); color: white; }
        .btn-primary:hover { background: var(--color-brand-dark); box-shadow: 0 4px 12px rgba(79,70,229,0.35); }

        .btn-secondary { background: #f1f5f9; color: var(--color-text-secondary); border: 1px solid var(--color-border); }
        .btn-secondary:hover { background: #e2e8f0; color: var(--color-text-primary); }

        .btn-success { background: #059669; color: white; }
        .btn-success:hover { background: #047857; box-shadow: 0 4px 12px rgba(5,150,105,0.3); }

        .btn-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .btn-danger:hover { background: #fecaca; color: #b91c1c; }

        .btn-ghost { background: transparent; color: var(--color-text-secondary); border: 1px solid var(--color-border); }
        .btn-ghost:hover { background: #f1f5f9; color: var(--color-text-primary); }

        /* Form elements */
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-secondary);
            margin-bottom: 5px;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--color-border);
            border-radius: 8px;
            font-size: 13.5px;
            color: var(--color-text-primary);
            background: white;
            transition: all 0.15s ease;
            outline: none;
            font-family: inherit;
        }

        body.admin-theme-enabled[data-theme="dark"] #admin-sidebar,
        body.admin-theme-enabled[data-theme="dark"] #admin-topbar,
        body.admin-theme-enabled[data-theme="dark"] #admin-footer,
        body.admin-theme-enabled[data-theme="dark"] .admin-card,
        body.admin-theme-enabled[data-theme="dark"] .stat-card {
            background: var(--color-surface);
        }
        body.admin-theme-enabled[data-theme="dark"] .admin-card-header,
        body.admin-theme-enabled[data-theme="dark"] .admin-table th,
        body.admin-theme-enabled[data-theme="dark"] .btn-secondary,
        body.admin-theme-enabled[data-theme="dark"] .btn-logout,
        body.admin-theme-enabled[data-theme="dark"] .empty-state-icon {
            background: #1e293b;
        }
        body.admin-theme-enabled[data-theme="dark"] .sidebar-link:hover,
        body.admin-theme-enabled[data-theme="dark"] .admin-table tbody tr:hover td,
        body.admin-theme-enabled[data-theme="dark"] .btn-ghost:hover {
            background: #1e293b;
        }
        body.admin-theme-enabled[data-theme="dark"] .sidebar-link.active,
        body.admin-theme-enabled[data-theme="dark"] .topbar-badge {
            background: #312e81;
            border-color: #4338ca;
            color: #c7d2fe;
        }
        body.admin-theme-enabled[data-theme="dark"] .form-input,
        body.admin-theme-enabled[data-theme="dark"] .form-select,
        body.admin-theme-enabled[data-theme="dark"] .form-textarea {
            background: #0f172a;
        }
        body.admin-theme-enabled[data-theme="dark"] .admin-table td {
            border-bottom-color: #1e293b;
        }
        body.admin-theme-enabled[data-theme="dark"] .stat-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.22);
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--color-brand);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='%2394a3b8'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' d='m19.5 8.25-7.5 7.5-7.5-7.5'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 36px;
        }

        .form-group { margin-bottom: 16px; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Empty state */
        .empty-state {
            padding: 48px 24px;
            text-align: center;
        }
        .empty-state-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 16px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
        }
        .empty-state-title { font-size: 15px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px; }
        .empty-state-desc { font-size: 13px; color: var(--color-text-muted); max-width: 300px; margin: 0 auto; }

        /* Page section */
        .page-section { margin-bottom: 24px; }
        .page-section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; gap: 12px; flex-wrap: wrap; }
        .page-section-title { font-size: 15px; font-weight: 700; color: var(--color-text-primary); }

        /* Grid utilities */
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }

        /* Divider */
        .divider { border: none; border-top: 1px solid var(--color-border); margin: 16px 0; }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-3 { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            #admin-sidebar {
                transform: translateX(-100%);
            }
            #admin-sidebar.open {
                transform: translateX(0);
            }
            #admin-main {
                margin-left: 0;
            }
            #mobile-menu-btn { display: flex; }
            #admin-sidebar.open + #admin-main #sidebar-overlay { display: block; }
            #admin-topbar { padding: 0 16px; }
            #admin-page-content { padding: 20px 16px 32px; }
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-3 { grid-template-columns: 1fr; }
            .grid-2 { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .grid-4 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
    <style>
        body.admin-theme-enabled[data-theme="dark"] :is(
            .admin-card,
            .stat-card,
            .step-card,
            .score-card,
            .dept-card,
            .dept-tab,
            .interviewer-card,
            .modal-box
        ) {
            background: var(--color-surface) !important;
            border-color: var(--color-border) !important;
        }
        body.admin-theme-enabled[data-theme="dark"] :is(
            .admin-card-header,
            .step-header,
            .candidate-summary,
            .dept-info-strip,
            .modal-header,
            .modal-footer
        ) {
            background: #1e293b !important;
            border-color: var(--color-border) !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .dept-info-name {
            color: #c7d2fe !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .dept-info-description {
            color: #a5b4fc !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .dept-info-value {
            color: #818cf8 !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .dept-info-label {
            color: #a78bfa !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .dept-info-strip + div[style*="background:#fafbff"],
        body.admin-theme-enabled[data-theme="dark"] .dept-info-strip + div[style*="background: #fafbff"] {
            background: #0f172a !important;
            border-top-color: var(--color-border) !important;
        }
        body.admin-theme-enabled[data-theme="dark"] :is(
            .dept-card:hover,
            .dept-tab:hover,
            .interviewer-card:hover,
            .candidate-summary:hover,
            .score-card:hover,
            .quick-action-item:hover,
            .admin-table tbody tr:hover td
        ) {
            background: #243244 !important;
        }
        body.admin-theme-enabled[data-theme="dark"] :is(
            .dept-tab.active,
            .dept-card.selected
        ) {
            background: var(--color-brand) !important;
            border-color: var(--color-brand) !important;
            color: white !important;
        }
        body.admin-theme-enabled[data-theme="dark"] :is(
            .form-input,
            .form-select,
            .form-textarea,
            .score-select
        ) {
            background-color: #0f172a !important;
            color: var(--color-text-primary) !important;
            border-color: var(--color-border) !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .score-select.has-score {
            background: #312e81 !important;
            border-color: #6366f1 !important;
            color: #c7d2fe !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .admin-table th {
            background: #1e293b !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .admin-table td {
            border-bottom-color: #1e293b !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .empty-state-icon {
            background: #1e293b !important;
            color: var(--color-text-muted) !important;
        }
        body.admin-theme-enabled[data-theme="dark"] :is(
            .interviewer-avatar,
            .candidate-avatar
        ) {
            background: linear-gradient(135deg, #312e81, #581c87) !important;
            color: #c7d2fe !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .modal-close {
            background: #334155 !important;
            color: var(--color-text-secondary) !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .modal-close:hover {
            background: #7f1d1d !important;
            color: #fecaca !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .modal-body [style*="background:#f8fafc"],
        body.admin-theme-enabled[data-theme="dark"] .modal-body [style*="background: #f8fafc"] {
            background: #0f172a !important;
            border-color: var(--color-border) !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .score-bar {
            background: #334155 !important;
        }
        body.admin-theme-enabled[data-theme="dark"] :is(
            .criteria-code-badge,
            .chip-core,
            .criteria-chip-core
        ) {
            background: #312e81 !important;
            border-color: #4f46e5 !important;
            color: #c7d2fe !important;
        }
        body.admin-theme-enabled[data-theme="dark"] :is(
            .chip-secondary,
            .criteria-chip-secondary
        ) {
            background: #78350f !important;
            border-color: #b45309 !important;
            color: #fde68a !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .criteria-chip-personal {
            background: #1e3a8a !important;
            color: #bfdbfe !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .criteria-chip-organizational {
            background: #064e3b !important;
            color: #a7f3d0 !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .chip[style*="background:#dbeafe"],
        body.admin-theme-enabled[data-theme="dark"] .score-card-type[style*="background:#dbeafe"] {
            background: #1e3a8a !important;
            color: #bfdbfe !important;
        }
        body.admin-theme-enabled[data-theme="dark"] .chip[style*="background:#dcfce7"],
        body.admin-theme-enabled[data-theme="dark"] .score-card-type[style*="background:#dcfce7"] {
            background: #064e3b !important;
            color: #a7f3d0 !important;
        }
    </style>
</head>
<body class="admin-theme-enabled">

    <!-- Sidebar -->
    <aside id="admin-sidebar">
        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">H</div>
            <div class="sidebar-logo-text">
                <div class="sidebar-logo-title">HIMATIK</div>
                <div class="sidebar-logo-sub">DSS Admin</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Overview</div>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <div class="sidebar-section-label">Rekrutmen</div>

            <a href="{{ route('admin.schedules') }}"
               class="sidebar-link {{ request()->routeIs('admin.schedules') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Jadwal Interview
            </a>

            <a href="{{ route('admin.interviewers') }}"
               class="sidebar-link {{ request()->routeIs('admin.interviewers') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Interviewer
            </a>

            <div class="sidebar-section-label">Sistem SPK</div>

            <a href="{{ route('admin.testing') }}"
               class="sidebar-link {{ request()->routeIs('admin.testing') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                Profile Matching
            </a>

            <div class="sidebar-section-label">Laporan</div>

            @php
                $first_dept_for_nav = \App\Models\Departmentsbiro::orderBy('name')->first();
            @endphp
            @if($first_dept_for_nav)
            <a href="{{ route('admin.rankings', $first_dept_for_nav) }}"
               class="sidebar-link {{ request()->routeIs('admin.rankings') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Rankings
            </a>
            @endif

            <div class="sidebar-section-label">Lainnya</div>

            <a href="{{ route('docs.blade') }}"
               class="sidebar-link">
                <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Dokumentasi
            </a>

            <a href="{{ route('landing') }}"
               class="sidebar-link">
                <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Web
            </a>
        </nav>

        <!-- User info -->
        <div class="sidebar-user">
            <div class="sidebar-user-info">
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div style="min-width:0; flex:1;">
                    <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="sidebar-user-email">{{ auth()->user()->email ?? 'admin@himatik.com' }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </aside>

    <!-- Overlay -->
    <div id="sidebar-overlay"></div>

    <!-- Main -->
    <div id="admin-main">

        <!-- Topbar -->
        <header id="admin-topbar">
            <div class="topbar-left">
                <button id="mobile-menu-btn" aria-label="Toggle sidebar">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="topbar-breadcrumb">
                    <span class="topbar-title">@yield('title', 'Dashboard')</span>
                    @hasSection('subtitle')
                        <span class="topbar-subtitle">@yield('subtitle')</span>
                    @endif
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-actions-slot">@yield('topbar-actions')</div>
                <button type="button" id="admin-theme-toggle" class="theme-toggle" aria-label="Toggle admin theme" title="Toggle theme">
                    <svg class="theme-icon-light" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364-.707-.707M6.343 6.343l-.707-.707m12.728 0-.707.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <svg class="theme-icon-dark" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
                    </svg>
                </button>
                <div class="topbar-badge">
                    <svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                    </svg>
                    Admin
                </div>
            </div>
        </header>

        <!-- Content -->
        <main id="admin-page-content">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="flash-message flash-success" role="alert">
                    <div class="flash-icon">✓</div>
                    <span style="font-weight:500;">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flash-message flash-error" role="alert">
                    <div class="flash-icon">✕</div>
                    <span style="font-weight:500;">{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="flash-message flash-info" role="alert">
                    <div class="flash-icon">ℹ</div>
                    <span style="font-weight:500;">{{ session('info') }}</span>
                </div>
            @endif
            @if(session('warning'))
                <div class="flash-message flash-warning" role="alert">
                    <div class="flash-icon">!</div>
                    <span style="font-weight:500;">{{ session('warning') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="flash-message flash-error" role="alert" style="flex-direction:column; align-items:flex-start; gap:6px;">
                    <div style="display:flex; align-items:center; gap:10px; width:100%;">
                        <div class="flash-icon">✕</div>
                        <span style="font-weight:600;">Terdapat {{ $errors->count() }} kesalahan:</span>
                    </div>
                    <ul style="margin-left: 30px; font-size: 12.5px; list-style: disc; color: #b91c1c;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <footer id="admin-footer">
            &copy; {{ date('Y') }} HIMATIK DSS — Sistem Pendukung Keputusan Rekrutmen
        </footer>
    </div>

    <script>
        const sidebarEl = document.getElementById('admin-sidebar');
        const overlayEl = document.getElementById('sidebar-overlay');
        const mobileBtn = document.getElementById('mobile-menu-btn');

        if (mobileBtn && sidebarEl && overlayEl) {
            mobileBtn.addEventListener('click', () => {
                sidebarEl.classList.toggle('open');
                overlayEl.style.display = sidebarEl.classList.contains('open') ? 'block' : 'none';
            });
            overlayEl.addEventListener('click', () => {
                sidebarEl.classList.remove('open');
                overlayEl.style.display = 'none';
            });
        }

        const adminThemeBtn = document.getElementById('admin-theme-toggle');
        const adminThemeKey = 'himatik-admin-theme';
        const adminThemeBody = document.body.classList.contains('admin-theme-enabled') ? document.body : null;

        if (adminThemeBody) {
            const savedTheme = localStorage.getItem(adminThemeKey) || 'light';
            adminThemeBody.dataset.theme = savedTheme === 'dark' ? 'dark' : 'light';
        }

        if (adminThemeBtn && adminThemeBody) {
            adminThemeBtn.addEventListener('click', () => {
                const nextTheme = adminThemeBody.dataset.theme === 'dark' ? 'light' : 'dark';
                adminThemeBody.dataset.theme = nextTheme;
                localStorage.setItem(adminThemeKey, nextTheme);
            });
        }

        // Auto-dismiss flash messages after 6s
        document.querySelectorAll('.flash-message').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity 0.4s, transform 0.4s';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-6px)';
                setTimeout(() => el.remove(), 400);
            }, 6000);
        });
    </script>

    @stack('scripts')
</body>
</html>
