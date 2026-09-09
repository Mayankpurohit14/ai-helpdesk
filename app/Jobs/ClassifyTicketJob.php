<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Ticket;
use App\Services\TicketAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClassifyTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(public Ticket $ticket)
    {
    }

    public function handle(TicketAIService $ai): void
    {
        $result = $ai->classify($this->ticket->subject, $this->ticket->description);

        if (! $result) {
            return; // fail gracefully — ticket just stays unclassified, agent handles manually
        }

        $category = Category::where('name', $result['category'])->first();

        $this->ticket->update([
            'priority' => $result['priority'],
            'category_id' => $category?->id ?? $this->ticket->category_id,
            'ai_summary' => $result['summary'],
        ]);
    }
}
