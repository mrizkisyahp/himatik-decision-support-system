<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class PublicAnnouncementApiController extends Controller
{
    /**
     * Get Accepted Candidates List
     *
     * Returns a public list of accepted candidates with their assigned department.
     * Only returns data when announcements have been published by the admin.
     *
     * @group Public
     * @unauthenticated
     *
     * @response 200 {
     *   "success": true,
     *   "is_published": true,
     *   "data": [
     *     {
     *       "nama": "Ahmad Rizki",
     *       "nim": "2211501234",
     *       "prodi": "Teknik Informatika",
     *       "assigned_department": "Biro Humas"
     *     }
     *   ]
     * }
     * @response 200 scenario="Not yet published" {
     *   "success": true,
     *   "is_published": false,
     *   "data": []
     * }
     */
    public function getAcceptedList()
    {
        $announcements = Announcement::where('status', 'accepted')
            ->where('is_published', true)
            ->with(['candidate.user', 'assignedDepartment'])
            ->get()
            ->map(function ($announcement) {
                return [
                    'nama' => $announcement->candidate->user->name,
                    'nim' => $announcement->candidate->nim,
                    'prodi' => $announcement->candidate->prodi,
                    'assigned_department' => $announcement->assignedDepartment->name ?? 'N/A',
                ];
            });
        return response()->json([
            'success' => true,
            'is_published' => Announcement::where('is_published', true)->exists(),
            'data' => $announcements
        ]);
    }
}