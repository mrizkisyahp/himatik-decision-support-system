<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Departmentsbiro;
use Illuminate\Http\Request;

class LandingApiController extends Controller
{
    /**
     * Get Landing Page Departments
     *
     * Returns a database-driven list of departments/biros displayed on the Flutter app landing page.
     * No authentication required.
     *
     * @group Public
     * @unauthenticated
     *
     * @response 200 [
     *   {
     *     "name": "Biro Humas",
     *     "description": "Biro Hubungan Masyarakat bertanggung jawab atas komunikasi eksternal HIMATIK."
     *   },
     *   {
     *     "name": "Biro Akademik",
     *     "description": "Mengelola kegiatan akademik dan pengembangan ilmu anggota HIMATIK."
     *   }
     * ]
     */
    public function index()
    {
        // Return only the dynamic database-driven departments list (safe columns only) as pure raw JSON response
        return response()->json(Departmentsbiro::where('is_active', true)
            ->select('name', 'description')
            ->orderBy('name')
            ->get());
    }
}
