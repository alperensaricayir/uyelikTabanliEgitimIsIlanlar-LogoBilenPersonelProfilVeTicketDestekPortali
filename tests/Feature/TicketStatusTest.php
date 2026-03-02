<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_status_logic_with_staff_and_user_replies()
    {
        // 1. Create a normal user and staff user
        $user = User::factory()->create(['role' => 'member']);
        $staff = User::factory()->create(['role' => 'admin']);

        // 2. Normal user creates ticket
        $ticket = Ticket::create([
            'user_id' => $user->id,
            'subject' => 'Test Ticket',
            'status' => 'open', // DB status
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => 'First message',
        ]);

        $ticket->refresh();
        $this->assertEquals('open', $ticket->status);
        $this->assertFalse($ticket->is_answered);
        $this->assertEquals('Açık', $ticket->statusLabel());

        // 3. User adds another message (should remain Open)
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => 'Follow up message',
        ]);

        $ticket->refresh();
        $this->assertEquals('open', $ticket->status);
        $this->assertFalse($ticket->is_answered);
        $this->assertEquals('Açık', $ticket->statusLabel());

        // 4. Staff replies (should become Answered)
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $staff->id,
            'message' => 'Admin response',
        ]);

        $ticket->refresh();
        // Eager load other replies to test the query logic from controller
        $ticketWithCount = Ticket::withCount([
            'replies as other_reply_count' => function ($q) {
                $q->whereColumn('ticket_replies.user_id', '!=', 'tickets.user_id');
            }
        ])->find($ticket->id);

        $this->assertTrue($ticketWithCount->is_answered);
        $this->assertEquals('Yanıtlandı', $ticketWithCount->statusLabel());

        // 5. User replies AGAIN (should KEEP 'answered' status per requirement, but show Yanıtlandı because staff has replied)
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => 'Thanks admin!',
        ]);

        $ticketWithCount->refresh();
        $ticketWithCount = Ticket::withCount([
            'replies as other_reply_count' => function ($q) {
                $q->whereColumn('ticket_replies.user_id', '!=', 'tickets.user_id');
            }
        ])->find($ticket->id);

        // Even though user replied, it still has other replies, so UI shows 'Yanıtlandı'
        $this->assertTrue($ticketWithCount->is_answered);
        $this->assertEquals('Yanıtlandı', $ticketWithCount->statusLabel());

        // 6. Staff closes ticket
        $ticketWithCount->update(['status' => 'closed']);

        $this->assertEquals('Kapalı', $ticketWithCount->statusLabel());
    }
}
