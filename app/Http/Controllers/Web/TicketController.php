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

        $tickets = $user->isAgent()
            ? Ticket::with('user')->latest()->paginate(15)
            : $user->tickets()->latest()->paginate(15);

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
        ]);

        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Biletiniz oluşturuldu!');
    }

    /** Bilet detayı + yanıtlar. */
    public function show(Ticket $ticket)
    {
        $this->authorizeTicketAccess($ticket);

        $ticket->load('replies.user');

        return view('tickets.show', compact('ticket'));
    }

    /** Yanıt ekleme. */
    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorizeTicketAccess($ticket);

        $request->validate(['message' => 'required|string']);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Agent yanıt attıysa durumu "pending" olarak işaretle
        if (auth()->user()->isAgent()) {
            $ticket->update(['status' => 'pending']);
        }

        return back()->with('success', 'Yanıtınız eklendi.');
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
