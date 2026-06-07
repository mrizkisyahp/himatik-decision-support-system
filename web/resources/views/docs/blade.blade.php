<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blade Docs — HIMATIK DSS</title>
    <meta name="description" content="Interactive documentation for all Blade web views in the HIMATIK Recruitment DSS system.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,600;0,700;0,900;1,400&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-bg: #1c1917;
            --sidebar-hover: #292524;
            --sidebar-border: #292524;
            --accent: #f59e0b;
            --accent-dark: #d97706;
            --accent-soft: #fef3c7;
            --stone-50: #fafaf9;
            --stone-100: #f5f5f4;
            --stone-200: #e7e5e4;
            --stone-300: #d6d3d1;
            --stone-400: #a8a29e;
            --stone-500: #78716c;
            --stone-600: #57534e;
            --stone-700: #44403c;
            --stone-800: #292524;
            --stone-900: #1c1917;

            --method-get-bg: #ccfbf1; --method-get-text: #0f766e; --method-get-border: #99f6e4;
            --method-post-bg: #dcfce7; --method-post-text: #15803d; --method-post-border: #bbf7d0;
            --method-put-bg: #fef3c7; --method-put-text: #b45309; --method-put-border: #fde68a;
            --method-del-bg: #fee2e2; --method-del-text: #b91c1c; --method-del-border: #fecaca;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--stone-50);
            color: var(--stone-900);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ─────────────────────────────────────────────── */
        #sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 272px;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 100;
            border-right: 1px solid #292524;
        }

        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-thumb { background: #44403c; border-radius: 4px; }

        .sidebar-header {
            padding: 24px 20px 20px;
            border-bottom: 1px solid #292524;
            position: sticky;
            top: 0;
            background: var(--sidebar-bg);
            z-index: 1;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .sidebar-logo-icon {
            width: 34px; height: 34px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sidebar-logo-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.01em;
            line-height: 1.2;
        }

        .sidebar-logo-sub {
            font-size: 0.7rem;
            font-weight: 400;
            color: var(--stone-400);
            letter-spacing: 0.03em;
        }

        .sidebar-meta {
            margin-top: 8px;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .sidebar-pill {
            background: #292524;
            color: var(--stone-400);
            font-size: 0.65rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 10px;
            font-family: 'JetBrains Mono', monospace;
        }

        .sidebar-api-link {
            display: block;
            margin-top: 12px;
            padding: 8px 12px;
            background: #292524;
            border: 1px solid #44403c;
            border-radius: 8px;
            color: var(--accent);
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.15s;
        }
        .sidebar-api-link:hover { background: #44403c; }

        .sidebar-nav { padding: 12px 0 24px; flex: 1; }

        .nav-group-label {
            padding: 16px 20px 6px;
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--stone-400);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 20px;
            text-decoration: none;
            color: var(--stone-400);
            font-size: 0.8rem;
            transition: all 0.15s;
            border-left: 2px solid transparent;
        }

        .nav-item:hover {
            background: var(--sidebar-hover);
            color: #fff;
            border-left-color: var(--accent);
        }

        .nav-method {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            flex-shrink: 0;
            width: 36px;
            text-align: center;
        }

        .nm-get  { background: #134e4a; color: #5eead4; }
        .nm-post { background: #14532d; color: #86efac; }

        /* ── MAIN CONTENT ────────────────────────────────────────── */
        #main {
            margin-left: 272px;
            flex: 1;
            min-width: 0;
        }

        .top-bar {
            position: sticky;
            top: 0;
            background: rgba(250, 250, 249, 0.9);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--stone-200);
            padding: 14px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 50;
        }

        .top-bar-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--stone-500);
        }

        .top-bar-title span { color: var(--stone-900); }

        .top-badge {
            background: var(--accent-soft);
            color: var(--accent-dark);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid #fde68a;
        }

        .content { padding: 40px; max-width: 920px; }

        /* Hero */
        .hero {
            margin-bottom: 48px;
            padding: 40px;
            background: linear-gradient(135deg, #1c1917 0%, #292524 60%, #3d2c0a 100%);
            border-radius: 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(245,158,11,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(245,158,11,0.15);
            border: 1px solid rgba(245,158,11,0.3);
            color: var(--accent);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 16px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .hero h1 {
            font-size: 2.2rem;
            font-weight: 900;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 12px;
        }

        .hero h1 span { color: var(--accent); }

        .hero p {
            font-size: 0.95rem;
            color: #a8a29e;
            line-height: 1.6;
            max-width: 560px;
            margin-bottom: 24px;
        }

        .hero-stats {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }

        .hero-stat {
            text-align: center;
        }

        .hero-stat-num {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--accent);
            line-height: 1;
        }

        .hero-stat-label {
            font-size: 0.7rem;
            color: var(--stone-400);
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Group header */
        .group-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 48px 0 24px;
        }

        .group-header:first-of-type { margin-top: 0; }

        .group-header h2 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--stone-900);
        }

        .group-line {
            flex: 1;
            height: 1px;
            background: var(--stone-200);
        }

        .group-count {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--stone-400);
            background: var(--stone-100);
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* Route Card */
        .route-card {
            background: #fff;
            border: 1px solid var(--stone-200);
            border-radius: 16px;
            margin-bottom: 24px;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }

        .route-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--stone-100);
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .method-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
            flex-shrink: 0;
            margin-top: 2px;
            border: 1px solid;
        }

        .mb-get  { background: var(--method-get-bg);  color: var(--method-get-text);  border-color: var(--method-get-border); }
        .mb-post { background: var(--method-post-bg); color: var(--method-post-text); border-color: var(--method-post-border); }
        .mb-put  { background: var(--method-put-bg);  color: var(--method-put-text);  border-color: var(--method-put-border); }
        .mb-del  { background: var(--method-del-bg);  color: var(--method-del-text);  border-color: var(--method-del-border); }

        .card-title-block { flex: 1; min-width: 0; }

        .card-uri {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1rem;
            font-weight: 600;
            color: var(--stone-900);
            word-break: break-all;
        }

        .card-uri .uri-param {
            color: var(--accent-dark);
            font-style: italic;
        }

        .card-name {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            color: var(--stone-400);
            margin-top: 3px;
        }

        .card-body { padding: 0 24px 24px; }

        .card-desc {
            padding: 16px 0 0;
            font-size: 0.88rem;
            color: var(--stone-600);
            line-height: 1.7;
            border-bottom: 1px solid var(--stone-100);
            padding-bottom: 16px;
            margin-bottom: 16px;
        }

        /* Info row */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 10px;
            margin-bottom: 16px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 14px;
            background: var(--stone-50);
            border: 1px solid var(--stone-100);
            border-radius: 8px;
        }

        .info-icon { font-size: 0.9rem; flex-shrink: 0; margin-top: 1px; }

        .info-content {}

        .info-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--stone-400);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 3px;
        }

        .info-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.78rem;
            color: var(--stone-700);
            font-weight: 600;
            word-break: break-all;
        }

        /* Middleware badges */
        .mw-badges { display: flex; gap: 6px; flex-wrap: wrap; }

        .mw-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            border: 1px solid;
        }

        .mw-public { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }
        .mw-guest  { background: #fef3c7; color: #b45309; border-color: #fde68a; }
        .mw-auth   { background: #dbeafe; color: #1d4ed8; border-color: #bfdbfe; }
        .mw-role   { background: #f3e8ff; color: #7c3aed; border-color: #e9d5ff; }

        /* Section */
        .section {
            margin-top: 16px;
            border: 1px solid var(--stone-200);
            border-radius: 10px;
            overflow: hidden;
        }

        .section-header {
            padding: 10px 16px;
            background: var(--stone-50);
            border-bottom: 1px solid var(--stone-200);
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--stone-500);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        thead tr { background: var(--stone-50); }

        th {
            padding: 9px 14px;
            text-align: left;
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--stone-400);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            border-bottom: 1px solid var(--stone-200);
        }

        td {
            padding: 10px 14px;
            color: var(--stone-700);
            border-bottom: 1px solid var(--stone-100);
            vertical-align: top;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--stone-50); }

        .td-name {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.78rem;
            font-weight: 600;
            color: #7c3aed;
            white-space: nowrap;
        }

        .td-type {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            color: #0f766e;
        }

        .td-rules {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            color: var(--stone-500);
        }

        .td-req {
            background: #fee2e2;
            color: #b91c1c;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            font-family: 'JetBrains Mono', monospace;
        }

        .td-cond {
            background: #fef3c7;
            color: #b45309;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            font-family: 'JetBrains Mono', monospace;
        }

        /* Model pills */
        .model-pills { display: flex; gap: 6px; flex-wrap: wrap; padding: 12px 16px; }

        .model-pill {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .service-pill {
            background: #fdf4ff;
            color: #7c3aed;
            border: 1px solid #e9d5ff;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
        }

        /* Post route label */
        .post-route-label {
            padding: 10px 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            color: var(--stone-500);
            background: var(--stone-50);
            border-bottom: 1px solid var(--stone-200);
        }

        .post-route-label strong { color: var(--method-post-text); }

        /* Empty state */
        .empty-note {
            padding: 14px 16px;
            font-size: 0.82rem;
            color: var(--stone-400);
            font-style: italic;
        }

        /* Anchor offset for sticky top bar */
        .anchor-target {
            scroll-margin-top: 64px;
        }
    </style>
</head>
<body>

<!-- ── SIDEBAR ──────────────────────────────────────────────────── -->
<aside id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">🏛️</div>
            <div>
                <div class="sidebar-logo-text">HIMATIK DSS</div>
                <div class="sidebar-logo-sub">Blade View Documentation</div>
            </div>
        </div>
        <div class="sidebar-meta">
            <span class="sidebar-pill">Laravel 11</span>
            <span class="sidebar-pill">Blade</span>
            <span class="sidebar-pill">Sanctum</span>
        </div>
        <a href="/docs" class="sidebar-api-link">⚡ API Docs (Scribe) →</a>
    </div>

    <nav class="sidebar-nav">
        @foreach($groups as $groupName => $routes)
        <div class="nav-group-label">{{ $groupName }}</div>
        @foreach($routes as $route)
        <a href="#{{ $route['id'] }}" class="nav-item">
            <span class="nav-method nm-{{ strtolower($route['method']) }}">{{ $route['method'] }}</span>
            <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $route['uri'] }}</span>
        </a>
        @endforeach
        @endforeach
    </nav>
</aside>

<!-- ── MAIN ─────────────────────────────────────────────────────── -->
<main id="main">
    <div class="top-bar">
        <div class="top-bar-title">HIMATIK DSS / <span>Blade Docs</span></div>
        <span class="top-badge">
            {{ collect($groups)->flatten(1)->count() }} Views
        </span>
    </div>

    <div class="content">

        <!-- Hero -->
        <div class="hero">
            <div class="hero-tag">📖 Blade Documentation</div>
            <h1>Web View <span>Reference</span></h1>
            <p>Complete documentation for every Blade view in the HIMATIK Recruitment DSS — routes, middleware guardrails, variables passed, connected controllers, models, and POST field schemas.</p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-num">{{ collect($groups)->flatten(1)->count() }}</div>
                    <div class="hero-stat-label">Views</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">{{ count($groups) }}</div>
                    <div class="hero-stat-label">Groups</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">{{ collect($groups)->flatten(1)->filter(fn($route) => !empty($route['post_fields']) || !empty($route['also_posts']))->count() }}</div>
                    <div class="hero-stat-label">Forms</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">4</div>
                    <div class="hero-stat-label">Roles</div>
                </div>
            </div>
        </div>

        <!-- Route Cards -->
        @foreach($groups as $groupName => $routes)

        @php
            $groupIcons = [
                'Public'               => '🌐',
                'Guest Only'           => '👤',
                'Candidate (auth)'     => '🎓',
                'Interviewer (auth)'   => '📋',
                'Admin (auth)'         => '🔑',
            ];
            $icon = $groupIcons[$groupName] ?? '📄';
        @endphp

        <div class="group-header">
            <h2>{{ $icon }} {{ $groupName }}</h2>
            <div class="group-line"></div>
            <span class="group-count">{{ count($routes) }} view{{ count($routes) > 1 ? 's' : '' }}</span>
        </div>

        @foreach($routes as $route)
        <div class="route-card anchor-target" id="{{ $route['id'] }}">

            {{-- Card Header --}}
            <div class="card-header">
                <span class="method-badge mb-{{ strtolower($route['method']) }}">{{ $route['method'] }}</span>
                <div class="card-title-block">
                    <div class="card-uri">
                        @php
                            $uri = $route['uri'];
                            $uri = preg_replace('/\{(\w+)\}/', '<span class="uri-param">{$1}</span>', $uri);
                        @endphp
                        {!! $uri !!}
                    </div>
                    <div class="card-name">route('{{ $route['name'] }}')</div>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="card-body">
                <div class="card-desc">{{ $route['description'] }}</div>

                {{-- Info Grid --}}
                <div class="info-grid">
                    {{-- View --}}
                    <div class="info-item">
                        <span class="info-icon">📄</span>
                        <div class="info-content">
                            <div class="info-label">Blade View</div>
                            <div class="info-value">resources/views/{{ $route['view'] }}</div>
                        </div>
                    </div>
                    {{-- Controller --}}
                    <div class="info-item">
                        <span class="info-icon">⚙️</span>
                        <div class="info-content">
                            <div class="info-label">Controller</div>
                            <div class="info-value">{{ $route['controller'] }}@{{ $route['action'] }}</div>
                        </div>
                    </div>
                    {{-- Middleware --}}
                    <div class="info-item">
                        <span class="info-icon">🔒</span>
                        <div class="info-content">
                            <div class="info-label">Middleware</div>
                            <div class="mw-badges" style="margin-top:4px;">
                                @foreach($route['middleware'] as $mw)
                                    @php
                                        $mwClass = match($mw) {
                                            'public'        => 'mw-public',
                                            'guest'         => 'mw-guest',
                                            'auth'          => 'mw-auth',
                                            default         => str_starts_with($mw, 'role') ? 'mw-role' : 'mw-auth',
                                        };
                                    @endphp
                                    <span class="mw-badge {{ $mwClass }}">{{ $mw }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Variables --}}
                @if(!empty($route['variables']))
                <div class="section">
                    <div class="section-header">📦 Variables Passed to View</div>
                    <table>
                        <thead>
                            <tr><th>Variable</th><th>Type</th><th>Description</th></tr>
                        </thead>
                        <tbody>
                            @foreach($route['variables'] as $var)
                            <tr>
                                <td class="td-name">{{ $var['name'] }}</td>
                                <td class="td-type">{{ $var['type'] }}</td>
                                <td>{{ $var['description'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="section">
                    <div class="section-header">📦 Variables Passed to View</div>
                    <div class="empty-note">No variables passed — view renders with no data.</div>
                </div>
                @endif

                {{-- Models & Services --}}
                @if(!empty($route['models']) || !empty($route['services']))
                <div class="section" style="margin-top:10px;">
                    <div class="section-header">🗄️ Models & Services Used</div>
                    <div class="model-pills">
                        @foreach($route['models'] as $model)
                            <span class="model-pill">{{ $model }}</span>
                        @endforeach
                        @if(!empty($route['services']))
                            @foreach($route['services'] as $svc)
                                <span class="service-pill">{{ $svc }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif

                {{-- POST Fields --}}
                @if(!empty($route['post_fields']))
                <div class="section" style="margin-top:10px;">
                    @if(!empty($route['post_route']))
                    <div class="post-route-label">
                        <strong>POST</strong> {{ Str::after($route['post_route'], 'POST ') }}
                    </div>
                    @endif
                    <div class="section-header">📝 Form POST Fields</div>
                    <table>
                        <thead>
                            <tr><th>Field</th><th>Type</th><th>Validation Rules</th><th>Description</th></tr>
                        </thead>
                        <tbody>
                            @foreach($route['post_fields'] as $field)
                            <tr>
                                <td class="td-name">{{ $field['name'] }}</td>
                                <td class="td-type">{{ $field['type'] }}</td>
                                <td class="td-rules">{{ $field['rules'] }}</td>
                                <td>{{ $field['description'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Also POSTs (for views with multiple forms) --}}
                @if(!empty($route['also_posts']))
                    @foreach($route['also_posts'] as $extra)
                    <div class="section" style="margin-top:10px;">
                        <div class="post-route-label">
                            <strong>POST</strong> {{ Str::after($extra['post_route'], 'POST ') }}
                        </div>
                        <div class="section-header">📝 {{ $extra['label'] }} Fields</div>
                        <table>
                            <thead>
                                <tr><th>Field</th><th>Type</th><th>Validation Rules</th><th>Description</th></tr>
                            </thead>
                            <tbody>
                                @foreach($extra['post_fields'] as $field)
                                <tr>
                                    <td class="td-name">{{ $field['name'] }}</td>
                                    <td class="td-type">{{ $field['type'] }}</td>
                                    <td class="td-rules">{{ $field['rules'] }}</td>
                                    <td>{{ $field['description'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                @endif

            </div>
        </div>
        @endforeach
        @endforeach

        <div style="margin-top:48px;padding:24px;text-align:center;color:var(--stone-400);font-size:0.8rem;border-top:1px solid var(--stone-200);">
            HIMATIK DSS · Blade Docs · {{ now()->format('Y') }} ·
            <a href="/docs" style="color:var(--accent-dark);text-decoration:none;">API Docs (Scribe) →</a>
        </div>

    </div>
</main>

</body>
</html>
