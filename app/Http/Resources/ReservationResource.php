<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'table' => [
                'id' => $this->table->id,
                'number' => $this->table->number,
                'seating_capacity' => $this->table->seating_capacity,
                'status' => $this->table->is_available ? 'vacant' : 'reserved',
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'reservation_time' => $this->reservation_time,
            'status' => $this->status,
        ];
    }
}
