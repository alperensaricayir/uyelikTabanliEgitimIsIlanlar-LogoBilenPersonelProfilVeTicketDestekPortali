<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_premium',
        'social_links',
        'featured_links',
        'likes_count',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_premium' => 'boolean',
            'social_links' => 'array',
            'featured_links' => 'array',
        ];
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    /** Premium erişimi: yöneticiler her zaman premium sayılır. */
    public function isPremium(): bool
    {
        return $this->is_premium || $this->role === 'admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAgent(): bool
    {
        return in_array($this->role, ['agent', 'admin']);
    }

    public function isEditor(): bool
    {
        return in_array($this->role, ['editor', 'admin']);
    }

    public function isAdminOrEditor(): bool
    {
        return in_array($this->role, ['admin', 'editor']);
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketReplies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'agent_id');
    }

    public function jobAlerts(): HasMany
    {
        return $this->hasMany(JobAlert::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
