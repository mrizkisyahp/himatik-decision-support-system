<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Departmentsbiro;
use App\Services\OpenRecruitmentService;
use Illuminate\Http\Request;

class LandingWebController extends Controller
{
    public function index(OpenRecruitmentService $openRecruitmentService)
    {
        // Fetch public department profile fields and public agenda/proker relations only.
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
            ->get();

        $openRecruitmentCards = $openRecruitmentService->openPublicCards();

        return view('landing', compact('departments', 'openRecruitmentCards'));
    }
}
