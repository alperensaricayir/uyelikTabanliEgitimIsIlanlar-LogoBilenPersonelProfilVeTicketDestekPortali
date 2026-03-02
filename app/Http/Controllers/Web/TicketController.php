<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    /** Kullanıcının kendi biletleri (Agent/Admin hepsini görür). */
    public function index()
    {
        $user = auth()->user();

        $otherReplyQuery = function ($q) {
            $q->whereColumn('ticket_replies.user_id', '!=', 'tickets.user_id');
        };

        $tickets = $user->isAgent()
            ? Ticket::with(['user', 'lastReplyBy'])->withCount(['replies as other_reply_count' => $otherReplyQuery])->recent()->paginate(15)
            : $user->tickets()->withCount(['replies as other_reply_count' => $otherReplyQuery])->recent()->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    /** Bilet oluşturma formu. */
    public function create()
    {
        return view('tickets.create');
    }

    /** Bilet kaydetme. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'nullable|string|in:low,medium,high',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'priority' => $validated['priority'] ?? 'medium',
            'last_reply_at' => now(),
            'last_reply_by' => auth()->id(),
        ]);

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('tickets', 'public');
                $reply->attachments()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Destek Ticket\'ınız oluşturuldu!');
    }

    /** Bilet detayı + yanıtlar. */
    public function show(Ticket $ticket)
    {
        $this->authorizeTicketAccess($ticket);

        $ticket->load('replies.user', 'replies.attachments');

        return view('tickets.show', compact('ticket'));
    }

    /** Yanıt ekleme. */
    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorizeTicketAccess($ticket);

        $request->validate([
            'message' => 'required|string',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('tickets', 'public');
                $reply->attachments()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Model boot event handles status and last_reply update.

        return back()->with('success', 'Yanıtınız eklendi.');
    }

    /** Bileti kapatma */
    public function close(Ticket $ticket)
    {
        $this->authorizeTicketAccess($ticket);

        $ticket->update(['status' => 'closed']);

        return back()->with('success', 'Destek Ticket\'ı kapatıldı.');
    }

    /** Bilete erişim kontrolü (sadece sahibi veya agent/admin). */
    private function authorizeTicketAccess(Ticket $ticket): void
    {
        $user = auth()->user();

        if (!$user->isAgent() && $ticket->user_id !== $user->id) {
            abort(403, 'Bu bilete erişim yetkiniz yok.');
        }
    }
}
