<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('manage-users');

        $query = Ticket::with(['user', 'lastReplyBy'])->recent();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(15)->withQueryString();

        return view('cms.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        Gate::authorize('manage-users');
        $ticket->load(['user', 'replies.user', 'replies.attachments', 'agent']);

        return view('cms.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'body' => 'required|string',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $reply = $ticket->replies()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('tickets', 'public');
                $reply->attachments()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return back()->with('success', 'Yanıt başarıyla eklendi.');
    }

    public function close(Ticket $ticket)
    {
        Gate::authorize('manage-users');
        $ticket->update(['status' => 'closed']);

        return back()->with('success', 'Ticket kapatıldı.');
    }

    public function destroy(Ticket $ticket)
    {
        Gate::authorize('manage-users');
        $ticket->delete();

        return redirect()->route('cms.tickets.index')
            ->with('success', 'Ticket başarıyla silindi.');
    }
}
