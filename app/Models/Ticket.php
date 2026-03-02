<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_id',
        'subject',
        'status',
        'priority',
        'last_reply_at',
        'last_reply_by',
    ];

    protected $casts = [
        'last_reply_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function lastReplyBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_reply_by');
    }

    // --- Scopes ---

    public function scopeRecent($query)
    {
        return $query->orderBy('last_reply_at', 'desc')->orderBy('updated_at', 'desc');
    }

    public function scopeOpenStatus($query)
    {
        return $query->whereIn('status', ['open', 'pending', 'answered']);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // --- Status Helpers ---

    public function getComputedStatusAttribute(): string
    {
        if ($this->status === 'closed') {
            return 'closed';
        }

        if ($this->last_reply_by) {
            return $this->last_reply_by === $this->user_id ? 'open' : 'answered';
        }

        return 'open';
    }

    public function statusLabel(): string
    {
        $status = $this->computed_status;
        if ($status === 'closed')
            return 'Kapalı';
        if ($status === 'answered')
            return 'Yanıtlandı';
        return 'Açık';
    }

    public function statusBadgeClass(): string
    {
        $status = $this->computed_status;
        if ($status === 'closed')
            return 'bg-neutral-100 text-neutral-600';
        if ($status === 'answered')
            return 'bg-violet-100 text-violet-700';
        return 'bg-emerald-100 text-emerald-700';
    }
}
