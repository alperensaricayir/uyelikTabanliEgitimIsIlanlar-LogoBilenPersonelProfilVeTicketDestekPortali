<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\Ticket;
use App\Models\Notification;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // KPI calculations
        $upcomingTrainingsCount = Training::where('starts_at', '>=', Carbon::now())
            ->where('starts_at', '<=', Carbon::now()->addDays(7))
            ->when(!$user->isPremium(), fn($q) => $q->where('is_premium_only', false))
            ->count();

        $openTicketsCount = Ticket::where('user_id', $user->id)
            ->openStatus()
            ->count();

        $newNotificationsCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        $profileCompleteness = $this->calculateProfileCompleteness($user);

        // Lists for sections
        $upcomingTrainings = Training::where('starts_at', '>=', Carbon::now())
            ->when(!$user->isPremium(), fn($q) => $q->where('is_premium_only', false))
            ->orderBy('starts_at')
            ->limit(5)
            ->get();

        $openTickets = Ticket::where('user_id', $user->id)
            ->openStatus()
            ->recent()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'upcomingTrainingsCount',
            'openTicketsCount',
            'newNotificationsCount',
            'profileCompleteness',
            'upcomingTrainings',
            'openTickets'
        ));
    }

    private function calculateProfileCompleteness($user)
    {
        $fields = [
            $user->name,
            $user->email,
            $user->headline,
            $user->bio,
            $user->city,
            $user->country,
            $user->profile_photo_path,
        ];

        $filled = collect($fields)->filter(fn($field) => !empty($field))->count();
        $total = count($fields);

        // Add points for having at least one skill
        $total++;
        if (!empty($user->skills) && count($user->skills) > 0) {
            $filled++;
        }

        // Add points for having at least one social link
        $total++;
        if (!empty($user->social_links) && count((array) $user->social_links) > 0) {
            $filled++;
        }

        // Also check specific URLs
        $urls = [
            $user->website_url,
            $user->linkedin_url,
            $user->github_url,
        ];

        $filledUrls = collect($urls)->filter(fn($url) => !empty($url))->count();
        if ($filledUrls > 0) {
            // Give 1 point if any url is filled, but don't penalize too much if not all are filled
            // Actually, let's just count them as a single group point to make it easier to reach 100%
            $total++;
            $filled++;
        } else {
            $total++; // Not filled, so denominator increases
        }

        // Added +1 total and +1 filled if they verified email (bonus point essentially)
        // Or we just calculate percentage from filled/total
        $percentage = round(($filled / $total) * 100);

        return $percentage;
    }
}
?>