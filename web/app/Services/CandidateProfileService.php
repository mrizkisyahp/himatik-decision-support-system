<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CandidateProfileService
{
    public function createFor(User $user, array $data): Candidate
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'nickname' => $data['nickname'],
                'nim' => $data['nim'],
                'prodi' => $data['prodi'],
                'kelas' => $data['kelas'],
                'phone' => $data['phone'],
                'address' => $data['address'],
            ]);

            $candidate = Candidate::create([
                'user_id' => $user->id,
                'candidate_type' => $data['candidate_type'],
                'department_choice_reason' => $data['department_choice_reason'],
                'weakness_description' => $data['weakness_description'],
                'contribution_plan' => $data['contribution_plan'],
                'photo_path' => $this->storeFile($data['photo'], 'photos'),
                'instagram_proof_path' => $this->storeFile($data['instagram_proof'], 'instagram_proofs'),
                'youtube_proof_path' => $this->storeFile($data['youtube_proof'], 'youtube_proofs'),
                'political_statement_path' => $this->storeFile($data['political_statement'], 'political_statements'),
                'candidate_signature_path' => $this->storeFile($data['candidate_signature'], 'candidate_signatures'),
                'parent_signature_path' => $this->storeFile($data['parent_signature'], 'parent_signatures'),
                'status' => 'registered',
            ]);

            $candidate->departmentChoices()->create([
                'departmentsbiro_id' => $data['first_choice_id'],
                'choice_order' => 1,
            ]);

            if (!empty($data['second_choice_id'])) {
                $candidate->departmentChoices()->create([
                    'departmentsbiro_id' => $data['second_choice_id'],
                    'choice_order' => 2,
                ]);
            }

            $candidate->educations()->createMany($data['educations'] ?? []);
            $candidate->organizations()->createMany($data['organizations'] ?? []);
            $candidate->committees()->createMany($data['committees'] ?? []);
            $candidate->skills()->createMany($data['skills'] ?? []);
            $candidate->facilities()->createMany($data['facilities'] ?? []);

            Announcement::firstOrCreate(
                ['candidate_id' => $candidate->id],
                ['status' => 'pending', 'is_published' => false]
            );

            return $candidate->load([
                'user',
                'departmentChoices.department',
                'educations',
                'organizations',
                'committees',
                'skills',
                'facilities',
                'announcement',
            ]);
        });
    }

    private function storeFile(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'local');
    }
}
