<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
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
            'group_id' => $this->group_id,
            'chat_id' => $this->chat_id,
            'chat_name' => $this->chat_name,
            'phone' => $this->phone,
            'name' => $this->name,
            'is_one_day' => $this->is_one_day,
            'active' => $this->active,
            'schedule' => $this->schedule,
            'inn' => $this->inn,
            'cards' => CardResource::collection($this->cards),
        ];
    }
}
