<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'keywords',
        'is_active',
    ];

    protected $casts = [
        'keywords' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check whether this alert matches the given job posting tags.
     */
    public function matchesJob(JobPosting $job): bool
    {
        if (!$job->tags || !$this->keywords) {
            return false;
        }

        $jobTags = array_map('strtolower', $job->tags);
        $alertKeys = array_map('strtolower', $this->keywords);

        return !empty(array_intersect($alertKeys, $jobTags));
    }
}
