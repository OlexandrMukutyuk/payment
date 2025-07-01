<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutgoingPaymentResource extends JsonResource
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
            'card_user_name' => $this->card_user_name,
            'bank' => $this->bank,
            'card' => $this->card,
            'fee' => $this->fee,
            'incoming_sum' => $this->incoming_sum,
            'status' => $this->status,
            'recipient_name' => $this->recipient_name,
            'recipient_bank' => $this->recipient_bank,
            'recipient_card' => $this->recipient_card,
            'recipient_iban' => $this->recipient_iban,
            'sum' => $this->sum,

        ];
    }
}
