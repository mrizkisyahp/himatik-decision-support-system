<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Criteria — HIMATIK DSS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    
    <!-- Laravel Vite Asset Bundler for Tailwind CSS v4 -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-[#fafaf9] font-sans antialiased">

    <!-- Top Border Strip -->
    <div class="fixed top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-amber-500 via-amber-600 to-amber-800"></div>

    <!-- Floating Top Right Button -->
    <div class="fixed top-4 right-4">
        <a href="{{ route('docs.blade') }}" class="bg-stone-900 text-amber-400 px-4 py-2 rounded-lg text-xs font-semibold hover:bg-black transition-all">
            📖 Blade Docs
        </a>
    </div>

    <!-- Center Card Content -->
    <div class="text-center px-6 py-10 max-w-[640px] flex flex-col items-center">
        <!-- File Indicator -->
        <code class="text-[0.78rem] text-stone-400 bg-stone-100 px-3 py-1.5 rounded-md border border-stone-200 font-mono">
            resources/views/admin/criteria.blade.php
        </code>

        <!-- Title -->
        <h1 class="my-6 text-5xl sm:text-7xl font-black text-stone-900 tracking-tighter leading-none">
            Evaluation Criteria
        </h1>

        <!-- Description -->
        <p class="text-base text-stone-500 leading-relaxed mb-8">
            Manage Profile Matching criteria per department — Core and Secondary factor dimensions with target scores.
        </p>

        <!-- Route & Auth Badges -->
        <div class="flex gap-2 justify-center flex-wrap mb-10">
            <span class="bg-teal-100 text-teal-700 border border-teal-200 px-3 py-1 rounded-full text-[0.75rem] font-bold font-mono">
                GET /admin/criteria/{department}
            </span>
            <span class="bg-blue-100 text-blue-700 border border-blue-200 px-3 py-1 rounded-full text-[0.75rem] font-semibold">
                auth
            </span>
            <span class="bg-purple-100 text-purple-700 border border-purple-200 px-3 py-1 rounded-full text-[0.75rem] font-semibold">
                role:admin
            </span>
        </div>

        <!-- Documentation Link Button -->
        <a href="{{ route('docs.blade') }}#admin-criteria" class="inline-flex items-center gap-2 bg-amber-500 text-stone-900 px-6 py-3 rounded-xl font-bold text-[0.9rem] hover:bg-amber-600 transition-all">
            📖 View Documentation →
        </a>
    </div>

</body>
</html>