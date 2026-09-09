<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TicketAIService
{
    public function classify(string $subject, string $description): ?array
    {
        $prompt = <<<PROMPT
        You are a support ticket triage assistant. Given a ticket, respond ONLY with valid JSON, no other text, in this exact shape:
        {"priority": "low|medium|high", "category": "Billing|Technical|General|Booking Issue|Account", "summary": "one short sentence summarizing the issue"}

        Subject: {$subject}
        Description: {$description}
        PROMPT;

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->timeout(15)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => config('services.openai.model'),
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.2,
                ]);

            if (! $response->successful()) {
                Log::warning('AI classification failed', ['body' => $response->body()]);
                return null;
            }

            $content = $response->json('choices.0.message.content');

            // Strip accidental markdown fences before decoding
            $clean = trim(str_replace(['```json', '```'], '', $content));

            $data = json_decode($clean, true);

            if (! is_array($data) || ! isset($data['priority'], $data['category'], $data['summary'])) {
                Log::warning('AI classification returned unexpected shape', ['raw' => $content]);
                return null;
            }

            return $data;
        } catch (\Throwable $e) {
            Log::error('AI classification exception: ' . $e->getMessage());
            return null;
        }
    }
}
