<?php

namespace App\Http\Resources\Api;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomingPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'agent_id' => $this->agent_id,
            'chat_id' => $this->chat_id,
            'group_id' => $this->group_id,
            'sender_name' => $this->sender_name,
            'sender_bank' => $this->sender_bank,
            'sender_card' => $this->sender_card,
            'sum' => $this->sum,
            'status' => $this->status,
            'status_translation' => __($this->status),
            'recipient_name' => $this->recipient_name,
            'recipient_bank' => $this->recipient_bank,
            'recipient_card' => $this->recipient_card,
            'recipient_iban' => $this->recipient_iban,
            'incoming_sum' => $this->incoming_sum,
            'group_ids' => Agent::active()->whereHas('cards', function ($query) {
                $query->where('active', true);
            })->pluck('group_id')->unique()->values()->toArray(),
            'agent' => Agent::active()->whereHas('cards', function ($query) {
                $query->where('active', true);
            })->select('chat_id', 'group_id')->get()->toArray()
        ];
    }
}
