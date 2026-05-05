<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ClientResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'total_bookings' => $this->total_bookings,
            'total_spent' => '$' . number_format($this->total_spent, 2),
            // Use Carbon to format the date
            'last_booking' => Carbon::parse($this->last_booking_date)->format('Y-m-d'),
        ];
    }
}
