<?php

namespace App\Models;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_id',
        'title',
        'slug',
        'content',
        'video_url',
        'status',
        'sort_order',
        'is_preview',
        'published_at',
        'updated_by',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
        'published_at' => 'datetime',
        'status' => ContentStatus::class,
        'sort_order' => 'integer',
    ];

    // ── Boot ────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Lesson $l) {
            if (empty($l->slug)) {
                $l->slug = Str::slug($l->title);
            }
        });
    }

    // ── Scopes ──────────────────────────────────────────────
    public function scopePublished($query)
    {
        return $query->where('status', ContentStatus::Published);
    }

    public function scopeVisible($query)
    {
        // Published OR is_preview (visible on course page without enrollment)
        return $query->where(function ($q) {
            $q->where('status', ContentStatus::Published)
                ->orWhere('is_preview', true);
        });
    }

    // ── Relationships ───────────────────────────────────────
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function revisions(): MorphMany
    {
        return $this->morphMany(ContentRevision::class, 'revisionable')
            ->orderByDesc('created_at');
    }

    // ── Helpers ─────────────────────────────────────────────
    public function isPublished(): bool
    {
        return $this->status === ContentStatus::Published;
    }
}
