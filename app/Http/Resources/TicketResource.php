<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'ai_summary' => $this->ai_summary,
            'category' => $this->category?->name,
            'customer' => $this->customer?->only('id', 'name', 'email'),
            'agent' => $this->agent?->only('id', 'name', 'email'),
            'replies_count' => $this->whenCounted('replies'),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
