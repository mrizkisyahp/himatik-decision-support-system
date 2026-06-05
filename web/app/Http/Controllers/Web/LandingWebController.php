<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Departmentsbiro;
use Illuminate\Http\Request;

class LandingWebController extends Controller
{
    public function index()
    {
        // Fetch all departments for the landing page, selecting ONLY name and description to prevent data leakage
        $departments = Departmentsbiro::where('is_active', true)
            ->select('name', 'description')
            ->orderBy('name')
            ->get();
        return view('landing', compact('departments'));
    }
}
