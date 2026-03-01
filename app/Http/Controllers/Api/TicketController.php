<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $tickets = $user->isAgent()
            ? Ticket::with('user:id,name,email')->latest()->paginate(20)
            : $user->tickets()->latest()->paginate(20);

        return response()->json($tickets);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'subject' => $validated['subject'],
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
        ]);

        return response()->json([
            'message' => 'Bilet oluşturuldu.',
            'ticket' => $ticket->load('replies'),
        ], 201);
    }

    public function show(Request $request, Ticket $ticket): JsonResponse
    {
        $user = $request->user();

        if (!$user->isAgent() && $ticket->user_id !== $user->id) {
            return response()->json(['message' => 'Yetkisiz erişim.'], 403);
        }

        return response()->json($ticket->load('replies.user:id,name'));
    }
}
