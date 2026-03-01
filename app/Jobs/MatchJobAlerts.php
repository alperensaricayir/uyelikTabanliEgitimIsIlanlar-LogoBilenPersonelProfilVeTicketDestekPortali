<?php

namespace App\Jobs;

use App\Models\JobAlert;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\JobMatchNotification;

class MatchJobAlerts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly JobPosting $job)
    {
    }

    /**
     * Yeni bir ilan eklendiğinde, anahtar kelimeleri eşleşen
     * tüm aktif uyarı sahiplerine bildirim gönderir.
     */
    public function handle(): void
    {
        JobAlert::where('is_active', true)
            ->with('user')
            ->chunkById(100, function (\Illuminate\Support\Collection $alerts) {
                /** @var \App\Models\JobAlert $alert */
                foreach ($alerts as $alert) {
                    if ($alert->matchesJob($this->job)) {
                        $alert->user->notify(new JobMatchNotification($this->job));
                    }
                }
            });
    }
}
