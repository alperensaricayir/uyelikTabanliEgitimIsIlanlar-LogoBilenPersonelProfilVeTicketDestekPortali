<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    protected static function booted()
    {
        static::created(function (TicketReply $reply) {
            $ticket = $reply->ticket;
            $user = $reply->user;

            $status = $ticket->status;
            if ($status !== 'closed') {
                if ($user && ($user->isAdmin() || $user->isEditor() || $user->isAgent())) {
                    $status = 'answered';
                }
                // Normal kullanici yaniti atildiginda 'answered' kalmasi isteniyor, 
                // kapatilmadigi surece yanitlandi veya acik kalir.
            }

            $ticket->update([
                'status' => $status,
                'last_reply_at' => $reply->created_at,
                'last_reply_by' => $reply->user_id,
            ]);
        });
    }
}
