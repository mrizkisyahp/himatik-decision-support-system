<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berkas Administratif - HIMATIK PNJ</title>
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
                Persyaratan Berkas Administratif Tambahan
            </h1>
            <p class="mt-4 text-sm leading-relaxed text-[#64748b] sm:text-base">
                Unggah berkas persyaratan pendukung yang dibutuhkan untuk memvalidasi pendaftaranmu.
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

            <form action="{{ route('candidate.documents.post') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf

                <!-- Foto 3x4 -->
                <div>
                    <label class="block text-sm font-bold text-[#333333] mb-2">Pas Foto Ukuran 3x4</label>
                    <div class="relative flex flex-col items-center justify-center rounded-xl border border-[#dce5f8] bg-[#FAFCFF] p-6 text-center transition hover:border-[#4A90E2] hover:bg-white cursor-pointer group" id="photo_card" onclick="if(!event.target.closest('button')) document.getElementById('photo').click()">
                        <svg class="h-8 w-8 text-[#929aaa]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="mt-2 text-sm font-medium text-[#929aaa]" id="photo_label">{{ $candidate->photo_path ? 'Foto sudah diunggah — klik untuk mengganti' : 'Tambah Pas Foto (JPG/PNG)' }}</span>
                        <button type="button" id="photo_preview_btn"
                            data-src="{{ $candidate->photo_path ? asset('storage/' . $candidate->photo_path) : '' }}"
                            data-type="image"
                            data-title="Pas Foto"
                            class="{{ $candidate->photo_path ? 'inline-flex' : 'hidden' }} mt-3 h-8 items-center gap-1.5 rounded-lg bg-[#e2e8f0] px-4 text-xs font-bold text-[#333333] hover:bg-[#cbd5e1]"
                            onclick="event.stopPropagation(); openPreview(this)">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Lihat Preview
                        </button>
                        <input type="file" name="photo" id="photo" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="handleFile(this, 'photo_preview_btn', 'photo_label', 'image', 'Foto terunggah ✓')">
                    </div>
                </div>

                <!-- Instagram Proof -->
                <div>
                    <label class="block text-sm font-bold text-[#333333]">Bukti Mengikuti Instagram HIMATIK PNJ</label>
                    <p class="text-xs text-[#929aaa] mb-2">Instagram: @himatikpnj</p>
                    <div class="relative flex flex-col items-center justify-center rounded-xl border border-[#dce5f8] bg-[#FAFCFF] p-6 text-center transition hover:border-[#4A90E2] hover:bg-white cursor-pointer group" id="ig_card" onclick="if(!event.target.closest('button')) document.getElementById('instagram_proof').click()">
                        <svg class="h-8 w-8 text-[#929aaa]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="mt-2 text-sm font-medium text-[#929aaa]" id="ig_label">{{ $candidate->instagram_proof_path ? 'Bukti Instagram sudah diunggah — klik untuk mengganti' : 'Tambah Bukti Mengikuti di Instagram (JPG/PNG)' }}</span>
                        <button type="button" id="ig_preview_btn"
                            data-src="{{ $candidate->instagram_proof_path ? asset('storage/' . $candidate->instagram_proof_path) : '' }}"
                            data-type="image"
                            data-title="Bukti Instagram"
                            class="{{ $candidate->instagram_proof_path ? 'inline-flex' : 'hidden' }} mt-3 h-8 items-center gap-1.5 rounded-lg bg-[#e2e8f0] px-4 text-xs font-bold text-[#333333] hover:bg-[#cbd5e1]"
                            onclick="event.stopPropagation(); openPreview(this)">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Lihat Preview
                        </button>
                        <input type="file" name="instagram_proof" id="instagram_proof" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="handleFile(this, 'ig_preview_btn', 'ig_label', 'image', 'Bukti Instagram terunggah ✓')">
                    </div>
                </div>

                <!-- Youtube Proof -->
                <div>
                    <label class="block text-sm font-bold text-[#333333]">Bukti Berlanggan ke Youtube HIMATIK PNJ</label>
                    <p class="text-xs text-[#929aaa] mb-2">Youtube: HIMATIK PNJ</p>
                    <div class="relative flex flex-col items-center justify-center rounded-xl border border-[#dce5f8] bg-[#FAFCFF] p-6 text-center transition hover:border-[#4A90E2] hover:bg-white cursor-pointer group" id="yt_card" onclick="if(!event.target.closest('button')) document.getElementById('youtube_proof').click()">
                        <svg class="h-8 w-8 text-[#929aaa]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="mt-2 text-sm font-medium text-[#929aaa]" id="yt_label">{{ $candidate->youtube_proof_path ? 'Bukti Youtube sudah diunggah — klik untuk mengganti' : 'Tambah Bukti Berlanggan di Youtube (JPG/PNG)' }}</span>
                        <button type="button" id="yt_preview_btn"
                            data-src="{{ $candidate->youtube_proof_path ? asset('storage/' . $candidate->youtube_proof_path) : '' }}"
                            data-type="image"
                            data-title="Bukti Youtube"
                            class="{{ $candidate->youtube_proof_path ? 'inline-flex' : 'hidden' }} mt-3 h-8 items-center gap-1.5 rounded-lg bg-[#e2e8f0] px-4 text-xs font-bold text-[#333333] hover:bg-[#cbd5e1]"
                            onclick="event.stopPropagation(); openPreview(this)">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Lihat Preview
                        </button>
                        <input type="file" name="youtube_proof" id="youtube_proof" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="handleFile(this, 'yt_preview_btn', 'yt_label', 'image', 'Bukti Youtube terunggah ✓')">
                    </div>
                </div>

                <!-- Political Statement -->
                <div>
                    <label class="block text-sm font-bold text-[#333333]">Surat Pernyataan Bukan Dari Ekstra Kampus dan Partai Politik</label>
                    <p class="text-xs text-[#929aaa] mb-2">Klik link ini untuk mendapatkan template-nya: <a href="#" class="font-bold text-[#223872] underline hover:text-[#4A90E2]">Link Template</a></p>
                    <div class="relative flex flex-col items-center justify-center rounded-xl border border-[#dce5f8] bg-[#FAFCFF] p-6 text-center transition hover:border-[#4A90E2] hover:bg-white cursor-pointer group" onclick="if(!event.target.closest('button')) document.getElementById('political_statement').click()">
                        <div id="pol_preview" class="{{ $candidate->political_statement_path ? 'flex' : 'hidden' }} mb-3 items-center gap-2 rounded-lg bg-red-50 px-4 py-3 text-red-600">
                            <svg class="h-6 w-6 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                            <span class="text-sm font-bold">Dokumen PDF Terunggah</span>
                        </div>
                        <svg id="pol_icon" class="{{ $candidate->political_statement_path ? 'hidden' : 'block' }} h-8 w-8 text-[#929aaa]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="mt-2 text-sm font-medium text-[#929aaa]" id="pol_label">{{ $candidate->political_statement_path ? 'Surat Pernyataan terunggah — klik untuk mengganti' : 'Tambah Surat Pernyataan (.PDF)' }}</span>
                        @if($candidate->political_statement_path)
                        <button type="button"
                            data-src="{{ asset('storage/' . $candidate->political_statement_path) }}"
                            data-type="pdf"
                            data-title="Surat Pernyataan"
                            class="inline-flex mt-3 h-8 items-center gap-1.5 rounded-lg bg-[#e2e8f0] px-4 text-xs font-bold text-[#333333] hover:bg-[#cbd5e1]"
                            id="pol_preview_btn"
                            onclick="event.stopPropagation(); openPreview(this)">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Lihat Preview
                        </button>
                        @endif
                        <input type="file" name="political_statement" id="political_statement" class="hidden" accept="application/pdf" onchange="handleFile(this, 'pol_preview_btn', 'pol_label', 'pdf', 'Surat Pernyataan terunggah ✓')">
                    </div>
                </div>

                <!-- Bottom Buttons -->
                <div class="flex flex-col gap-4 pt-6 sm:flex-row sm:items-center">
                    <a href="{{ route('candidate.skills.view') }}" class="inline-flex h-12 w-full flex-1 items-center justify-center gap-2 rounded-xl border-2 border-[#dce5f8] bg-transparent px-8 text-sm font-bold text-[#64748b] transition hover:border-[#223872] hover:text-[#223872] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20 sm:w-auto">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" class="inline-flex h-12 w-full flex-1 items-center justify-center gap-2 rounded-xl bg-[#223872] px-8 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1b2f60] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/20 sm:w-auto">
                        Berikutnya
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Preview Modal — small scrollable card -->
    <div id="previewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4" onclick="if(event.target === this) closePreview()">
        <div class="relative w-full max-w-sm rounded-2xl bg-white shadow-2xl overflow-hidden flex flex-col" style="max-height: 80vh;">
            <div class="flex items-center justify-between px-4 py-3 border-b border-[#dce5f8] shrink-0">
                <h3 id="previewTitle" class="text-sm font-black text-[#111827]">Preview</h3>
                <button type="button" onclick="closePreview()" class="text-red-500 hover:text-red-700 focus:outline-none">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="previewBody" class="overflow-y-auto">
                <!-- filled by JS -->
            </div>
        </div>
    </div>

    <script>
        // ── Core: when a file is picked ──────────────────────────────────
        const MAX_IMAGE_MB = 5;
        const MAX_PDF_MB   = 10;

        function handleFile(input, btnId, labelId, type, doneMsg) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];
            const label = document.getElementById(labelId);
            const maxMB = type === 'pdf' ? MAX_PDF_MB : MAX_IMAGE_MB;
            const maxBytes = maxMB * 1024 * 1024;

            if (file.size > maxBytes) {
                input.value = ''; // reset input
                label.textContent = `⚠ File terlalu besar (maks. ${maxMB}MB). Pilih file lain.`;
                label.classList.remove('text-green-600', 'text-[#929aaa]');
                label.classList.add('text-red-500', 'font-bold');
                // Hide preview button if visible
                const btn = document.getElementById(btnId);
                if (btn) { btn.classList.add('hidden'); btn.classList.remove('inline-flex'); }
                return;
            }

            if (type === 'pdf') {
                const blobUrl = URL.createObjectURL(file);
                const polPreview = document.getElementById('pol_preview');
                const polIcon = document.getElementById('pol_icon');
                if (polPreview) { polPreview.classList.remove('hidden'); polPreview.classList.add('flex'); }
                if (polIcon) polIcon.classList.add('hidden');

                let btn = document.getElementById(btnId);
                if (!btn) {
                    btn = document.createElement('button');
                    btn.type = 'button';
                    btn.id = btnId;
                    btn.className = 'inline-flex mt-3 h-8 items-center gap-1.5 rounded-lg bg-[#e2e8f0] px-4 text-xs font-bold text-[#333333] hover:bg-[#cbd5e1]';
                    btn.innerHTML = '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> Lihat Preview';
                    input.parentNode.insertBefore(btn, input);
                }
                btn.dataset.src = blobUrl;
                btn.dataset.type = 'pdf';
                btn.dataset.title = 'Surat Pernyataan';
                btn.classList.remove('hidden');
                btn.onclick = function(e) { e.stopPropagation(); openPreview(this); };

                label.textContent = doneMsg;
                label.classList.add('text-green-600', 'font-bold');
                label.classList.remove('text-[#929aaa]');

            } else {
                // Compress image client-side to max 1200px wide, quality 0.75
                compressImage(file, 1200, 0.75, function(compressedBlob) {
                    const dataUrl = URL.createObjectURL(compressedBlob);
                    let btn = document.getElementById(btnId);
                    btn.dataset.src = dataUrl;
                    btn.dataset.type = 'image';
                    btn.classList.remove('hidden');
                    btn.classList.add('inline-flex');

                    label.textContent = doneMsg;
                    label.classList.add('text-green-600', 'font-bold');
                    label.classList.remove('text-[#929aaa]');

                    // Replace the input's file with the compressed blob
                    replaceFileInput(input, compressedBlob, file.name);
                });
            }
        }

        /**
         * Compress an image File/Blob via canvas.
         * maxWidth: max pixel width. quality: JPEG quality 0-1.
         */
        function compressImage(file, maxWidth, quality, callback) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    let w = img.naturalWidth;
                    let h = img.naturalHeight;
                    if (w > maxWidth) {
                        h = Math.round(h * maxWidth / w);
                        w = maxWidth;
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = w;
                    canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, w, h);
                    canvas.toBlob(callback, 'image/jpeg', quality);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        /** Swap the file inside a file input with a new blob. */
        function replaceFileInput(input, blob, originalName) {
            const ext = originalName.split('.').pop();
            const newName = originalName.replace(/\.[^.]+$/, '') + '_compressed.jpg';
            const file = new File([blob], newName, { type: 'image/jpeg' });
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
        }

        // ── Open preview modal ───────────────────────────────────────────
        function openPreview(btn) {
            const src   = btn.dataset.src;
            const type  = btn.dataset.type;
            const title = btn.dataset.title;
            if (!src) return;
            document.getElementById('previewTitle').textContent = 'Preview: ' + title;
            const body = document.getElementById('previewBody');
            if (type === 'pdf') {
                body.innerHTML = `<iframe src="${src}" class="w-full border-0" style="height:60vh;"></iframe>`;
            } else {
                body.innerHTML = `<img src="${src}" alt="Preview" class="w-full object-contain p-3">`;
            }
            const modal = document.getElementById('previewModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePreview() {
            const modal = document.getElementById('previewModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('previewBody').innerHTML = '';
        }
    </script>
</body>
</html>
