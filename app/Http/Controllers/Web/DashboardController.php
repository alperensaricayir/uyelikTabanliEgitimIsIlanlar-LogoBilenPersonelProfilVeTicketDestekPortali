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

        $profile = $user->profile; // assumes relation
        $profileCompleteness = $this->calculateProfileCompleteness($profile);

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

    private function calculateProfileCompleteness($profile)
    {
        if (!$profile)
            return 0;
        $fields = [
            $profile->full_name,
            $profile->title,
            $profile->bio,
            $profile->location,
        ];
        $filled = collect($fields)->filter()->count();
        $total = count($fields);
        $socialLinksCount = $profile->socialLinks()->count();
        $total++;
        $filled += $socialLinksCount > 0 ? 1 : 0;
        return round(($filled / $total) * 100);
    }
}
?>