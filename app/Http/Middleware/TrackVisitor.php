<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Visitor;
use App\Models\VisitorCount;

class TrackVisitor
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');
        $today = Carbon::now()->toDateString();

        // Find or create the visitor
        $visitor = Visitor::firstOrCreate(
            ['ip_address' => $ip],
        );

        // Check if there's a record for today
        $visitToday = VisitorCount::where('visitor_id', $visitor->id)
            ->where('visit_date', $today)
            ->first();

        // If no record for today, create one
        if (!$visitToday) {
            VisitorCount::create([
                'visitor_id' => $visitor->id,
                'visit_date' => $today,
                'user_agent' => $userAgent,
            ]);
        }

        return $next($request);
    }
}
