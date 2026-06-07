<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\OpenRecruitment;

class CheckOprecActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) return $next($request);

        $oprec = null;

        // If they have a candidate profile, use that candidate_type
        if ($user->candidate) {
            $oprec = OpenRecruitment::where('candidate_type', $user->candidate->candidate_type)
                ->where('status', 'open')
                ->first();
        } 
        // If they don't have a profile yet but are starting an application, check the passed oprec
        else {
            $oprecId = $request->route('openRecruitment') ?? $request->input('open_recruitment_id') ?? $request->query('open_recruitment_id');
            if ($oprecId) {
                // route('openRecruitment') could be an object if implicit model binding is used
                if ($oprecId instanceof OpenRecruitment) {
                    $oprec = $oprecId;
                } else {
                    $oprec = OpenRecruitment::find($oprecId);
                }
            }
        }

        if (!$oprec || !$oprec->isCurrentlyOpen()) {
            return redirect()->route('candidate.dashboard')->with('error', 'Maaf, periode pendaftaran untuk posisi ini sedang ditutup atau sudah lewat batas waktu.');
        }

        return $next($request);
    }
}
