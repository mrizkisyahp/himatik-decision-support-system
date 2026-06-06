<?php

namespace App\Services;

use App\Models\OpenRecruitment;
use App\Models\OpenRecruitmentQuota;
use Illuminate\Support\Collection;

class OpenRecruitmentService
{
    public function currentByType(): Collection
    {
        return OpenRecruitment::query()
            ->get()
            ->keyBy('candidate_type');
    }

    public function isOpenFor(string $candidateType): bool
    {
        $openRecruitment = OpenRecruitment::where('candidate_type', $candidateType)->first();

        return $openRecruitment?->isCurrentlyOpen() ?? false;
    }

    public function messageFor(?OpenRecruitment $openRecruitment): string
    {
        if (!$openRecruitment) {
            return 'Open recruitment belum tersedia saat ini.';
        }

        if ($openRecruitment->status === 'closed') {
            return 'Open recruitment ditutup.';
        }

        if (!$openRecruitment->starts_at || !$openRecruitment->ends_at) {
            return 'Jadwal open recruitment belum lengkap.';
        }

        if (now()->lt($openRecruitment->starts_at)) {
            return 'Open recruitment belum dibuka.';
        }

        if (now()->gt($openRecruitment->ends_at)) {
            return 'Open recruitment sudah lewat.';
        }

        return 'Open recruitment sedang dibuka.';
    }

    public function publicCards(): Collection
    {
        $rows = $this->currentByType();

        return collect(['bph', 'staff'])->map(function (string $type) use ($rows) {
            $row = $rows->get($type);

            return [
                'candidate_type' => $type,
                'title' => $type === 'bph' ? 'Daftar sebagai BPH' : 'Daftar sebagai Staff',
                'date_text' => $this->dateText($row),
                'is_open' => $row?->isCurrentlyOpen() ?? false,
                'status_raw' => $row?->status ?? 'closed',
                'is_upcoming' => $row?->starts_at && now()->lt($row->starts_at),
                'message' => $this->messageFor($row),
                'quota_total' => OpenRecruitmentQuota::where('candidate_type', $type)->sum('quota'),
            ];
        });
    }

    public function openPublicCards(): Collection
    {
        return $this->publicCards()
            ->filter(fn(array $card) => $card['status_raw'] === 'open')
            ->values();
    }

    public function dateText(?OpenRecruitment $openRecruitment): string
    {
        if (!$openRecruitment?->starts_at || !$openRecruitment?->ends_at) {
            return 'Jadwal belum tersedia';
        }

        return $openRecruitment->starts_at->locale('id')->translatedFormat('d F Y H:i')
            . ' s.d. '
            . $openRecruitment->ends_at->locale('id')->translatedFormat('d F Y H:i');
    }
}
