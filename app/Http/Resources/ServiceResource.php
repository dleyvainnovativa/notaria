<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'cost' => $this->cost, // This is the base cost
            'icon' => $this->icon,
            'image' => $this->image,
            'is_active' => $this->is_active,

            // --- Important for Admin Panel ---
            'category_id' => $this->category_id, // For the edit form dropdown
            'category_name' => $this->category->name ?? 'Uncategorized', // For display in the table
            'branches_count' => $this->whenCounted('branches'), // Count of branches this service is on
        ];
    }
}
