<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Landing Page — HIMATIK DSS</title><link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet"><style>*{box-sizing:border-box;margin:0;padding:0}body{min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;background:#fafaf9;font-family:'Inter',system-ui}</style></head>
<body>
<div style="position:fixed;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#f59e0b,#d97706,#92400e)"></div>
<div style="position:fixed;top:16px;right:16px"><a href="{{ route('docs.blade') }}" style="background:#1c1917;color:#fbbf24;padding:8px 16px;border-radius:8px;font-size:0.8rem;font-weight:600;text-decoration:none">📖 Blade Docs</a></div>
<div style="text-align:center;padding:40px 24px;max-width:640px">
    <code style="font-size:0.78rem;color:#a8a29e;background:#f5f5f4;padding:4px 12px;border-radius:6px;border:1px solid #e7e5e4">resources/views/landing.blade.php</code>
    <h1 style="margin:24px 0 16px;font-size:clamp(2.5rem,8vw,5rem);font-weight:900;color:#1c1917;letter-spacing:-0.04em;line-height:1">Landing Page</h1>
    <p style="font-size:1rem;color:#78716c;line-height:1.6;margin-bottom:32px">Public entry point for the HIMATIK Open Recruitment DSS.</p>
    <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-bottom:40px">
        <span style="background:#ccfbf1;color:#0f766e;border:1px solid #99f6e4;padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;font-family:monospace">GET /</span>
        <span style="background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:600">public</span>
    </div>
    <a href="{{ route('docs.blade') }}#landing" style="display:inline-flex;align-items:center;gap:8px;background:#f59e0b;color:#1c1917;padding:12px 24px;border-radius:10px;font-weight:700;font-size:0.9rem;text-decoration:none">📖 View Documentation →</a>
</div>
</body></html>