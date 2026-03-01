<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_reply_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    public function ticketReply(): BelongsTo
    {
        return $this->belongsTo(TicketReply::class);
    }
}
