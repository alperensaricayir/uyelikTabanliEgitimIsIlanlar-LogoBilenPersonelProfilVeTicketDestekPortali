<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTicketReplyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_reply_to_ticket_with_body_mapped_to_message()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'member']);

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'subject' => 'Test Ticket',
            'status' => 'open'
        ]);

        $response = $this->actingAs($admin)->post(route('cms.tickets.reply', $ticket), [
            'body' => 'test reply message'
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('ticket_replies', [
            'ticket_id' => $ticket->id,
            'message' => 'test reply message',
            'user_id' => $admin->id
        ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'answered',
            'last_reply_by' => $admin->id
        ]);
    }
}
