<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class PublicAnnouncementController extends Controller
{
    public function showAcceptedList()
    {
        // Query only the accepted candidates, and only if published
        $announcements = Announcement::where('status', 'accepted')
            ->where('is_published', true)
            ->with(['candidate.user', 'assignedDepartment'])
            ->get();
        // Pass the variables to the Blade view
        return view('public.announcements', compact('announcements'));
    }
}
