<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'sometimes|in:open,in_progress,resolved,closed',
            'agent_id' => 'sometimes|nullable|exists:users,id',
            'category_id' => 'sometimes|nullable|exists:categories,id',
        ];
    }
}
