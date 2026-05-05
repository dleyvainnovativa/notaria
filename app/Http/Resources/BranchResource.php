<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'address' => $this->address_line_1 . ', ' . $this->city . ', ' . $this->state,
            'phone_number' => $this->phone_number,
            'is_active' => $this->is_active,
            // Use 'whenLoaded' to only include counts when they are loaded in the controller
            'services_count' => $this->whenCounted('services'),
            'reservations_count' => $this->whenCounted('reservations'),
            // We need the full address details for the edit form
            'full_address' => [
                'address_line_1' => $this->address_line_1,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
            ],
        ];
    }
}
