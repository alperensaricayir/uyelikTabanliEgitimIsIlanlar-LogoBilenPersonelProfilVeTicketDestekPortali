<?php

namespace App\Providers;

use App\Models\JobPosting;
use App\Models\Lesson;
use App\Models\Training;
use App\Observers\JobPostingObserver;
use App\Policies\LessonPolicy;
use App\Policies\TrainingPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        JobPosting::observe(JobPostingObserver::class);

        Gate::policy(Training::class, TrainingPolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);
    }
}
