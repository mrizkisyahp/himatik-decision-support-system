<?php

namespace App\Support;

class SpkCriteriaDefaults
{
    public static function criteria(): array
    {
        return [
            [
                'code' => 'K1',
                'name' => 'Kepercayaan Diri dan Komunikasi',
                'description' => 'Kelancaran komunikasi menempati posisi krusial dalam pertukaran instruksi organisasi.',
                'type' => 'core',
                'aspect' => 'personal',
                'target_score' => 4,
            ],
            [
                'code' => 'K2',
                'name' => 'Problem Solve (Pemecahan Masalah)',
                'description' => 'Kemampuan adaptif kandidat dalam menghadapi dinamika organisasi yang penuh ketidakpastian.',
                'type' => 'core',
                'aspect' => 'personal',
                'target_score' => 4,
            ],
            [
                'code' => 'K3',
                'name' => 'Manajemen Waktu',
                'description' => 'Kemampuan mengelola jadwal antara kuliah dan organisasi secara proporsional.',
                'type' => 'secondary',
                'aspect' => 'personal',
                'target_score' => 3,
            ],
            [
                'code' => 'K4',
                'name' => 'Konsistensi Jawaban',
                'description' => 'Stabilitas pendirian, kedewasaan berpikir, dan konsistensi logika kandidat saat wawancara.',
                'type' => 'secondary',
                'aspect' => 'personal',
                'target_score' => 4,
            ],
            [
                'code' => 'K5',
                'name' => 'Komitmen',
                'description' => 'Loyalitas dan kemauan berkorban tanpa imbalan material sebagai fondasi utama fungsionaris.',
                'type' => 'core',
                'aspect' => 'organizational',
                'target_score' => 4,
            ],
            [
                'code' => 'K6',
                'name' => 'Pengalaman Organisasi/Kepanitiaan',
                'description' => 'Rekam jejak kandidat pada organisasi atau kepanitiaan terdahulu.',
                'type' => 'secondary',
                'aspect' => 'organizational',
                'target_score' => 4,
            ],
            [
                'code' => 'K7',
                'name' => 'Pengetahuan Organisasi',
                'description' => 'Pemahaman kandidat terkait GBHO, sejarah, hirarki, dan budaya kerja HIMATIK.',
                'type' => 'secondary',
                'aspect' => 'organizational',
                'target_score' => 4,
            ],
        ];
    }

    public static function targetScoreFor(string $departmentName, string $criteriaCode, int $fallback): int
    {
        return self::departmentTargets()[$departmentName][$criteriaCode] ?? $fallback;
    }

    public static function departmentTargets(): array
    {
        return [
            'Biro Kesekretariatan' => ['K1' => 3, 'K2' => 3, 'K3' => 5, 'K4' => 5, 'K5' => 4, 'K6' => 3, 'K7' => 4],
            'Biro Bendahara Umum' => ['K1' => 3, 'K2' => 4, 'K3' => 4, 'K4' => 5, 'K5' => 5, 'K6' => 3, 'K7' => 3],
            'Biro Kreatif' => ['K1' => 4, 'K2' => 5, 'K3' => 4, 'K4' => 3, 'K5' => 4, 'K6' => 3, 'K7' => 2],
            'Departemen Pendidikan dan Teknologi' => ['K1' => 4, 'K2' => 5, 'K3' => 4, 'K4' => 4, 'K5' => 4, 'K6' => 3, 'K7' => 4],
            'Departemen Komunikasi dan Informasi' => ['K1' => 5, 'K2' => 4, 'K3' => 4, 'K4' => 4, 'K5' => 4, 'K6' => 3, 'K7' => 3],
            'Departemen Bisnis dan Kemitraan' => ['K1' => 5, 'K2' => 4, 'K3' => 4, 'K4' => 3, 'K5' => 4, 'K6' => 4, 'K7' => 3],
            'Departemen Sosial Politik' => ['K1' => 5, 'K2' => 5, 'K3' => 3, 'K4' => 4, 'K5' => 4, 'K6' => 4, 'K7' => 4],
            'Departemen Sosial Mahasiswa' => ['K1' => 4, 'K2' => 4, 'K3' => 3, 'K4' => 3, 'K5' => 5, 'K6' => 4, 'K7' => 3],
            'Departemen Kesehatan Mahasiswa' => ['K1' => 4, 'K2' => 4, 'K3' => 4, 'K4' => 3, 'K5' => 5, 'K6' => 4, 'K7' => 3],
            'Departemen Kerohanian' => ['K1' => 4, 'K2' => 3, 'K3' => 4, 'K4' => 5, 'K5' => 5, 'K6' => 3, 'K7' => 3],
        ];
    }
}
