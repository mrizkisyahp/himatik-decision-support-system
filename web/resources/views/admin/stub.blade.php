@extends('admin.layout', ['title' => $title])

@section('content')
    <div class="mx-auto max-w-7xl">
        <section class="rounded-[1.75rem] border border-[#dce5f8] bg-white p-6 shadow-[0_18px_44px_rgba(34,56,114,0.08)]">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-3xl">
                    <span class="inline-flex rounded-full border border-[#4A90E2]/25 bg-[#4A90E2]/10 px-3 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#223872]">
                        Stub UI - belum terhubung ke database/backend
                    </span>
                    <h2 class="mt-4 text-3xl font-black tracking-tight text-[#111827] sm:text-4xl">{{ $title }}</h2>
                    <p class="mt-3 text-sm leading-7 text-[#64748b] sm:text-base">{{ $description }}</p>
                </div>

                <div class="rounded-2xl border border-[#dce5f8] bg-[#F4F7FF] px-4 py-3 text-sm font-bold text-[#223872]">
                    Non-functional preview
                </div>
            </div>
        </section>

        <section class="mt-6 grid gap-4 md:grid-cols-3">
            @foreach ($cards as $card)
                <article class="rounded-[1.35rem] border border-[#dce5f8] bg-white p-5 shadow-[0_12px_30px_rgba(34,56,114,0.06)]">
                    <p class="text-xs font-black uppercase tracking-[0.16em] text-[#4A90E2]">{{ $card['label'] }}</p>
                    <h3 class="mt-3 text-xl font-black text-[#111827]">{{ $card['title'] }}</h3>
                    <p class="mt-2 text-sm leading-6 text-[#64748b]">{{ $card['text'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="mt-6 rounded-[1.5rem] border border-[#dce5f8] bg-white p-5 shadow-[0_12px_30px_rgba(34,56,114,0.06)]">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-black text-[#111827]">{{ $tableTitle ?? 'Rencana Tampilan Data' }}</h3>
                    <p class="mt-1 text-sm text-[#64748b]">Placeholder layout untuk struktur konten yang akan dihubungkan nanti.</p>
                </div>
                <button type="button" disabled class="rounded-2xl bg-[#223872]/45 px-4 py-2 text-sm font-black text-white">
                    Aksi Stub
                </button>
            </div>

            <div class="mt-5 overflow-hidden rounded-2xl border border-[#dce5f8]">
                <div class="grid grid-cols-4 bg-[#F4F7FF] px-4 py-3 text-xs font-black uppercase tracking-[0.14em] text-[#64748b]">
                    <span>Kolom 1</span>
                    <span>Kolom 2</span>
                    <span>Kolom 3</span>
                    <span>Status</span>
                </div>

                @for ($row = 1; $row <= 4; $row++)
                    <div class="grid grid-cols-4 border-t border-[#dce5f8] px-4 py-4 text-sm text-[#929aaa]">
                        <span class="font-bold text-[#64748b]">Placeholder {{ $row }}</span>
                        <span>Konten rencana</span>
                        <span>Belum aktif</span>
                        <span>
                            <span class="rounded-full bg-[#F4F7FF] px-3 py-1 text-xs font-black text-[#64748b]">Stub</span>
                        </span>
                    </div>
                @endfor
            </div>
        </section>
    </div>
@endsection
