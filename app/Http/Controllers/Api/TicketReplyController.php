<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate(['message' => 'required|string|min:2']);

        $reply = $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'message' => $request->message,
        ]);

        return response()->json($reply->load('user'), 201);
    }
}
