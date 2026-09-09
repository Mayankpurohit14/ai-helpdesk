<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Jobs\ClassifyTicketJob;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Ticket::with(['category', 'customer', 'agent'])->withCount('replies');

        if ($user->hasRole('customer')) {
            $query->where('customer_id', $user->id);
        } elseif ($user->hasRole('agent')) {
            $query->where(function ($q) use ($user) {
                $q->where('agent_id', $user->id)->orWhereNull('agent_id');
            });
        }
        // admin sees everything, no filter

        $tickets = $query->latest()->paginate(10);

        return TicketResource::collection($tickets);
    }

 public function store(StoreTicketRequest $request)
{
    $ticket = Ticket::create([
        ...$request->validated(),
        'customer_id' => $request->user()->id,
        'status' => 'open',
    ]);

    ClassifyTicketJob::dispatch($ticket);

    return new TicketResource($ticket->load(['category', 'customer']));
}

    public function show(Request $request, Ticket $ticket)
    {
        $this->authorizeAccess($request, $ticket);

        return new TicketResource($ticket->load(['category', 'customer', 'agent', 'replies.user']));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $user = $request->user();

        if ($user->hasRole('customer')) {
            abort(403, 'Customers cannot update tickets directly.');
        }

        $ticket->update($request->validated());

        return new TicketResource($ticket->load(['category', 'customer', 'agent']));
    }

    public function destroy(Request $request, Ticket $ticket)
    {
        if (! $request->user()->hasRole('admin')) {
            abort(403, 'Only admins can delete tickets.');
        }

        $ticket->delete();

        return response()->json(['message' => 'Ticket deleted']);
    }

    private function authorizeAccess(Request $request, Ticket $ticket): void
    {
        $user = $request->user();

        if ($user->hasRole('customer') && $ticket->customer_id !== $user->id) {
            abort(403, 'You cannot view this ticket.');
        }

        if ($user->hasRole('agent') && $ticket->agent_id && $ticket->agent_id !== $user->id) {
            abort(403, 'This ticket is assigned to another agent.');
        }
    }
}
