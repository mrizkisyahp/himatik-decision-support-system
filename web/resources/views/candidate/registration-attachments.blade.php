@extends('candidate.layout', ['title' => 'Lampiran Pendaftaran'])

@section('content')
<div class="mx-auto max-w-3xl space-y-8 pb-12">
    <div class="mb-6">
        <a href="{{ route('candidate.registration.form') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#223872] hover:text-[#1b2f60] transition group">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <div>
        <h1 class="text-2xl md:text-3xl font-black tracking-tight text-[#0F172A] mb-8">Lampiran Pendaftaran</h1>

        @php
            $attachments = [
                ['label' => 'Pas Foto Ukuran 3x4', 'path' => $candidate->photo_path, 'field' => 'photo_path'],
                ['label' => 'Bukti Mengikuti Instagram HIMATIK PNJ', 'path' => $candidate->instagram_proof_path, 'field' => 'instagram_proof_path'],
                ['label' => 'Bukti Berlanggan ke Youtube HIMATIK PNJ', 'path' => $candidate->youtube_proof_path, 'field' => 'youtube_proof_path'],
                ['label' => 'Surat Pernyataan Bukan Dari Ekstra Kampus dan Partai Politik', 'path' => $candidate->political_statement_path, 'field' => 'political_statement_path'],
                ['label' => 'Tanda Tangan Calon', 'path' => $candidate->candidate_signature_path, 'field' => 'candidate_signature_path'],
                ['label' => 'Tanda Tangan Orang Tua Calon', 'path' => $candidate->parent_signature_path, 'field' => 'parent_signature_path'],
            ];
        @endphp

        <div class="space-y-4">
            @foreach($attachments as $attachment)
                @if($attachment['path'])
                    <a href="{{ route('documents.download', [$candidate->id, $attachment['field']]) }}" target="_blank" class="flex items-center justify-between p-5 rounded-2xl bg-white border border-[#dce5f8] shadow-sm hover:shadow-md hover:border-[#4A90E2] transition group">
                        <span class="text-sm font-medium text-[#0F172A] pr-4">{{ $attachment['label'] }}</span>
                        <svg class="h-4 w-4 text-[#64748B] group-hover:text-[#4A90E2] transition-colors shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @else
                    <div class="flex items-center justify-between p-5 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] opacity-60">
                        <span class="text-sm font-medium text-[#64748B] pr-4">{{ $attachment['label'] }} (Belum Ada)</span>
                        <svg class="h-4 w-4 text-[#94A3B8] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection
