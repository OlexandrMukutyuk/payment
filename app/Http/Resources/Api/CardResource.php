<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => intval($this->id),
            'bank_id' => intval($this->bank_id),
            'agent_id' => intval($this->agent_id),
            'limit' => floatval($this->limit),
            'status' => $this->status,
            'iban' => $this->iban,
            'date_end' => $this->date_end,
            'number' => $this->number,
            'active' => $this->active,
            'file' => $this->getFirstMediaUrl('files'),
        ];
    }
}
