<?php

namespace App\Observers;

use App\Jobs\MatchJobAlerts;
use App\Models\JobPosting;

class JobPostingObserver
{
    /**
     * Yeni ilan eklendiğinde eşleşen alert sahiplerine bildirim gönder.
     */
    public function created(JobPosting $jobPosting): void
    {
        MatchJobAlerts::dispatch($jobPosting);
    }

    /**
     * Handle the JobPosting "updated" event.
     */
    public function updated(JobPosting $jobPosting): void
    {
        //
    }

    /**
     * Handle the JobPosting "deleted" event.
     */
    public function deleted(JobPosting $jobPosting): void
    {
        //
    }

    /**
     * Handle the JobPosting "restored" event.
     */
    public function restored(JobPosting $jobPosting): void
    {
        //
    }

    /**
     * Handle the JobPosting "force deleted" event.
     */
    public function forceDeleted(JobPosting $jobPosting): void
    {
        //
    }
}
