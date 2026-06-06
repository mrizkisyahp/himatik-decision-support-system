<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Departmentsbiro;
use App\Services\OpenRecruitmentService;
use Illuminate\Http\Request;

class LandingApiController extends Controller
{
    /**
     * Get Landing Page Departments
     *
     * Returns a database-driven list of departments/biros displayed on the public landing page.
     * Includes safe public agenda and program kerja fields for the landing profile section.
     * No authentication required.
     *
     * @group Public
     * @unauthenticated
     *
     * @response 200 [
     *   {
     *     "name": "Biro Kreatif",
     *     "description": "Biro Kreatif HIMATIK adalah biro yang dibentuk untuk menangani desain publikasi.",
     *     "work_programs": [],
     *     "agendas": [
     *       {
     *         "title": "Produksi Company Profile",
     *         "description": null,
     *         "start_date": null,
     *         "end_date": null,
     *         "location": null
     *       }
     *     ]
     *   }
     * ]
     */
    public function index(OpenRecruitmentService $openRecruitmentService)
    {
        $departments = Departmentsbiro::where('is_active', true)
            ->select('id', 'name', 'description')
            ->with([
                'agendas' => fn($query) => $query
                    ->where('is_active', true)
                    ->select('id', 'department_id', 'title', 'description', 'start_date', 'end_date', 'location')
                    ->orderBy('sort_order')
                    ->orderBy('title'),
                'workPrograms' => fn($query) => $query
                    ->where('is_active', true)
                    ->select('id', 'department_id', 'name', 'description', 'period')
                    ->orderBy('sort_order')
                    ->orderBy('name'),
            ])
            ->orderBy('name')
            ->get()
            ->map(fn($department) => [
                'name' => $department->name,
                'description' => $department->description,
                'work_programs' => $department->workPrograms->map(fn($program) => [
                    'name' => $program->name,
                    'description' => $program->description,
                    'period' => $program->period,
                ])->values(),
                'agendas' => $department->agendas->map(fn($agenda) => [
                    'title' => $agenda->title,
                    'description' => $agenda->description,
                    'start_date' => $agenda->start_date?->toDateString(),
                    'end_date' => $agenda->end_date?->toDateString(),
                    'location' => $agenda->location,
                ])->values(),
            ]);

        return response()->json([
            'departments' => $departments,
            'open_recruitments' => $openRecruitmentService->openPublicCards()->values(),
        ]);
    }
}
