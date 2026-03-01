<?php

namespace App\Notifications;

use App\Models\JobPosting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class JobMatchNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly JobPosting $job)
    {
    }

    /**
     * In-app (database) bildirim + mail flag etkinse mail de gider.
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        // Özellik bayrağı: EMAIL_NOTIFICATIONS=true ise mail de gönder
        if (config('features.email_notifications', false)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'job_id' => $this->job->id,
            'job_title' => $this->job->title,
            'company_name' => $this->job->company_name,
            'message' => "Uyarılarınızla eşleşen yeni bir ilan: {$this->job->title} – {$this->job->company_name}",
        ];
    }
}
