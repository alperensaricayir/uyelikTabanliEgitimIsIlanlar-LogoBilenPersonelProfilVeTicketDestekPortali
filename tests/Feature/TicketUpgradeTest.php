<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketUpgradeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_ticket_with_priority()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tickets', [
            'subject' => 'Test Subject',
            'message' => 'Initial message',
            'priority' => 'high',
        ]);

        $this->assertDatabaseHas('tickets', [
            'user_id' => $user->id,
            'subject' => 'Test Subject',
            'priority' => 'high',
            'status' => 'open', // Should default to open due to the reply boot event
        ]);

        $ticket = Ticket::first();
        $this->assertEquals($user->id, $ticket->last_reply_by);
        $this->assertNotNull($ticket->last_reply_at);

        $this->assertDatabaseHas('ticket_replies', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => 'Initial message',
        ]);
    }

    public function test_agent_reply_changes_status_to_answered()
    {
        $user = User::factory()->create();
        $agent = User::factory()->create(['role' => 'admin']);

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'subject' => 'Need help',
            'status' => 'open',
        ]);

        $this->actingAs($agent)->post("/tickets/{$ticket->id}/reply", [
            'message' => 'Here is your answer',
        ]);

        $ticket->refresh();

        $this->assertEquals('answered', $ticket->status);
        $this->assertEquals($agent->id, $ticket->last_reply_by);
    }

    public function test_user_reply_changes_status_to_open()
    {
        $user = User::factory()->create();

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'subject' => 'Need help',
            'status' => 'answered',
        ]);

        $this->actingAs($user)->post("/tickets/{$ticket->id}/reply", [
            'message' => 'I still need help',
        ]);

        $ticket->refresh();

        $this->assertEquals('open', $ticket->status);
        $this->assertEquals($user->id, $ticket->last_reply_by);
    }
}
