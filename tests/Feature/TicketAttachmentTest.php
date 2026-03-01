<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketAttachmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_images_when_creating_ticket()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file1 = UploadedFile::fake()->create('error1.jpg', 100, 'image/jpeg');
        $file2 = UploadedFile::fake()->create('error2.png', 100, 'image/png');

        $response = $this->actingAs($user)->post('/tickets', [
            'subject' => 'Image Test',
            'message' => 'Here are some images',
            'images' => [$file1, $file2],
        ]);

        $response->assertRedirect();

        $ticket = Ticket::first();
        $reply = $ticket->replies()->first();

        $this->assertCount(2, $reply->attachments);

        Storage::disk('public')->assertExists($reply->attachments[0]->file_path);
        Storage::disk('public')->assertExists($reply->attachments[1]->file_path);
    }

    public function test_user_can_upload_images_when_replying()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $ticket = Ticket::create([
            'user_id' => $user->id,
            'subject' => 'Need help',
            'status' => 'open',
        ]);

        $file = UploadedFile::fake()->create('fix.png', 100, 'image/png');

        $response = $this->actingAs($user)->post("/tickets/{$ticket->id}/reply", [
            'message' => 'I found a fix here is the image',
            'images' => [$file],
        ]);

        $response->assertRedirect();

        $reply = $ticket->replies()->first();
        $this->assertCount(1, $reply->attachments);
        Storage::disk('public')->assertExists($reply->attachments->first()->file_path);
    }

    public function test_attachment_validation_fails_on_non_image()
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->post('/tickets', [
            'subject' => 'PDF Test',
            'message' => 'Here is a PDF',
            'images' => [$file],
        ]);

        $response->assertSessionHasErrors('images.0');
        $this->assertEquals(0, Ticket::count());
    }

    public function test_attachment_validation_fails_on_large_image()
    {
        $user = User::factory()->create();
        // Create a file larger than 5120 KB (5MB), e.g., 6MB
        $file = UploadedFile::fake()->create('large.jpg', 6000, 'image/jpeg');

        $response = $this->actingAs($user)->post('/tickets', [
            'subject' => 'Large Image Test',
            'message' => 'Here is a large image',
            'images' => [$file],
        ]);

        $response->assertSessionHasErrors('images.0');
        $this->assertEquals(0, Ticket::count());
    }
}
