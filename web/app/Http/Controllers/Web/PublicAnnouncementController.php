<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class PublicAnnouncementController extends Controller
{
    public function showAcceptedList(Request $request)
    {
        $isPublished = Announcement::where('is_published', true)->exists();

        // Query only the accepted candidates, and only if published
        $announcements = Announcement::where('announcements.status', 'accepted')
            ->where('announcements.is_published', true)
            ->with(['candidate.user', 'assignedDepartment'])
            ->leftJoin('departmentsbiro', 'announcements.assigned_department_id', '=', 'departmentsbiro.id')
            ->select('announcements.*')
            ->orderBy('departmentsbiro.name', 'asc')
            ->paginate(15);
            
        // Pass the variables to the Blade view
        return view('public.announcements', compact('announcements', 'isPublished'));
    }
}
