<?php

namespace App\Models;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Training extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'meeting_url',
        'resources_url',
        'is_premium_only',
        'published_at',
        'status',
        'thumbnail',
        'updated_by',
    ];

    protected $casts = [
        'is_premium_only' => 'boolean',
        'published_at' => 'datetime',
        'status' => ContentStatus::class,
    ];

    // ── Boot ────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Training $t) {
            if (empty($t->slug)) {
                $t->slug = Str::slug($t->title);
            }
        });
    }

    // ── Scopes ──────────────────────────────────────────────
    public function scopePublished($query)
    {
        return $query->where('status', ContentStatus::Published);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', ContentStatus::Draft);
    }

    public function scopeArchived($query)
    {
        return $query->where('status', ContentStatus::Archived);
    }

    // ── Relationships ───────────────────────────────────────
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order');
    }

    public function publishedLessons(): HasMany
    {
        return $this->hasMany(Lesson::class)
            ->where('status', ContentStatus::Published)
            ->orderBy('sort_order');
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

    public function thumbnailUrl(): ?string
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : null;
    }
}
