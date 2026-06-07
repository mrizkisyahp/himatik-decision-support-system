<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pernyataan & Persetujuan - HIMATIK PNJ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4F7FF] font-sans text-[#333333] antialiased flex flex-col relative">
    <!-- Navbar -->
    <nav class="border-b border-[#dce5f8] bg-white">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-5 sm:px-8">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" alt="Logo HIMATIK" class="h-8 w-auto">
                <span class="text-lg font-black tracking-tight text-[#223872] hidden sm:block">HIMATIK PNJ</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="mx-auto flex w-full max-w-3xl flex-1 flex-col px-5 py-8 sm:px-8 sm:py-12">
        <div class="w-full">
            <h1 class="text-3xl font-black leading-tight tracking-tight text-[#111827] sm:text-4xl">
                Pernyataan & Persetujuan
            </h1>
            <p class="mt-4 text-sm leading-relaxed text-[#64748b] sm:text-base">
                Bubuhkan tanda tangan sebagai persetujuan bahwa seluruh data yang diisi adalah benar.
            </p>

            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('candidate.signatures.post') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-8">
                @csrf

                <!-- Candidate Signature -->
                <div>
                    <label class="block text-sm font-bold text-[#333333] mb-2">Tanda Tangan Calon</label>

                    @if($candidate->candidate_signature_path)
                    <div class="mb-3 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3">
                        <img src="{{ asset('storage/' . $candidate->candidate_signature_path) }}" alt="Tanda Tangan Calon" class="h-10 w-auto object-contain">
                        <span class="text-sm font-medium text-green-700">Tanda tangan sudah tersimpan</span>
                    </div>
                    @endif

                    <!-- Signature Canvas -->
                    <div class="rounded-xl border border-[#dce5f8] bg-white p-4">
                        <canvas id="candidate_canvas" width="600" height="150"
                            class="w-full cursor-crosshair touch-none rounded-lg bg-[#FAFCFF] border border-dashed border-[#dce5f8]"
                            style="height: 150px;">
                        </canvas>
                        <div class="mt-3 flex items-center justify-between">
                            <button type="button" onclick="clearCanvas('candidate_canvas', 'candidate_signature_data', 'candidate_sig_label')" class="text-xs font-bold text-red-500 hover:text-red-700 focus:outline-none">
                                × Hapus
                            </button>
                            <span class="text-xs text-[#929aaa]" id="candidate_sig_label">Tanda tangani di kotak di atas</span>
                        </div>
                        <input type="hidden" name="candidate_signature_data" id="candidate_signature_data">
                    </div>

                    {{-- Hidden file input for actual upload (filled from canvas) --}}
                    <input type="file" name="candidate_signature" id="candidate_signature" class="hidden">
                </div>

                <!-- Parent Signature -->
                <div>
                    <label class="block text-sm font-bold text-[#333333] mb-2">Tanda Tangan Orang Tua Calon</label>

                    @if($candidate->parent_signature_path)
                    <div class="mb-3 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3">
                        <img src="{{ asset('storage/' . $candidate->parent_signature_path) }}" alt="Tanda Tangan Orang Tua" class="h-10 w-auto object-contain">
                        <span class="text-sm font-medium text-green-700">Tanda tangan orang tua sudah tersimpan</span>
                    </div>
                    @endif

                    <div class="rounded-xl border border-[#dce5f8] bg-white p-4">
                        <canvas id="parent_canvas" width="600" height="150"
                            class="w-full cursor-crosshair touch-none rounded-lg bg-[#FAFCFF] border border-dashed border-[#dce5f8]"
                            style="height: 150px;">
                        </canvas>
                        <div class="mt-3 flex items-center justify-between">
                            <button type="button" onclick="clearCanvas('parent_canvas', 'parent_signature_data', 'parent_sig_label')" class="text-xs font-bold text-red-500 hover:text-red-700 focus:outline-none">
                                × Hapus
                            </button>
                            <span class="text-xs text-[#929aaa]" id="parent_sig_label">Tanda tangani di kotak di atas</span>
                        </div>
                        <input type="hidden" name="parent_signature_data" id="parent_signature_data">
                    </div>

                    <input type="file" name="parent_signature" id="parent_signature" class="hidden">
                </div>

                <!-- Bottom Buttons -->
                <div class="flex flex-col gap-4 pt-2 sm:flex-row sm:items-center">
                    <a href="{{ route('candidate.documents.view') }}" class="inline-flex h-12 w-full flex-1 items-center justify-center gap-2 rounded-xl border-2 border-[#dce5f8] bg-transparent px-8 text-sm font-bold text-[#64748b] transition hover:border-[#223872] hover:text-[#223872] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20 sm:w-auto">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" id="submitBtn" class="inline-flex h-12 w-full flex-1 items-center justify-center gap-2 rounded-xl bg-[#223872] px-8 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20 sm:w-auto">
                        Kirim
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // ── Canvas Signature ─────────────────────────────────────────────
        function initCanvas(canvasId, dataInputId, labelId) {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');
            const dataInput = document.getElementById(dataInputId);
            const label = document.getElementById(labelId);

            let drawing = false;

            // Scale canvas for hi-dpi
            function resize() {
                const rect = canvas.getBoundingClientRect();
                canvas.width = rect.width * window.devicePixelRatio;
                canvas.height = rect.height * window.devicePixelRatio;
                ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
                ctx.strokeStyle = '#223872';
                ctx.lineWidth = 2.5;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
            }
            resize();

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                const src = e.touches ? e.touches[0] : e;
                return {
                    x: (src.clientX - rect.left),
                    y: (src.clientY - rect.top)
                };
            }

            function startDraw(e) {
                drawing = true;
                const pos = getPos(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
                e.preventDefault();
            }

            function draw(e) {
                if (!drawing) return;
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
                e.preventDefault();

                // Save data url
                dataInput.value = canvas.toDataURL('image/png');
                label.textContent = 'Tanda tangan tersimpan ✓';
                label.classList.add('text-green-600', 'font-bold');
                label.classList.remove('text-[#929aaa]');
            }

            function stopDraw() { drawing = false; }

            canvas.addEventListener('mousedown', startDraw);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDraw);
            canvas.addEventListener('mouseleave', stopDraw);
            canvas.addEventListener('touchstart', startDraw, { passive: false });
            canvas.addEventListener('touchmove', draw, { passive: false });
            canvas.addEventListener('touchend', stopDraw);
        }

        function clearCanvas(canvasId, dataInputId, labelId) {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');
            const rect = canvas.getBoundingClientRect();
            ctx.clearRect(0, 0, rect.width * window.devicePixelRatio, rect.height * window.devicePixelRatio);
            document.getElementById(dataInputId).value = '';
            const label = document.getElementById(labelId);
            label.textContent = 'Tanda tangani di kotak di atas';
            label.classList.remove('text-green-600', 'font-bold');
            label.classList.add('text-[#929aaa]');
        }

        initCanvas('candidate_canvas', 'candidate_signature_data', 'candidate_sig_label');
        initCanvas('parent_canvas', 'parent_signature_data', 'parent_sig_label');

        // ── Before Submit: convert canvas data URLs to file objects ─────
        document.querySelector('form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = this;

            async function dataUrlToFile(dataUrl, fileName) {
                const res = await fetch(dataUrl);
                const blob = await res.blob();
                return new File([blob], fileName, { type: 'image/png' });
            }

            const candidateData = document.getElementById('candidate_signature_data').value;
            const parentData    = document.getElementById('parent_signature_data').value;

            const dt = new DataTransfer();

            if (candidateData) {
                const file = await dataUrlToFile(candidateData, 'candidate_signature.png');
                const inputEl = document.getElementById('candidate_signature');
                dt.items.add(file);
                inputEl.files = dt.files;
            }

            const dt2 = new DataTransfer();
            if (parentData) {
                const file = await dataUrlToFile(parentData, 'parent_signature.png');
                const inputEl = document.getElementById('parent_signature');
                dt2.items.add(file);
                inputEl.files = dt2.files;
            }

            form.submit();
        });
    </script>
</body>
</html>
