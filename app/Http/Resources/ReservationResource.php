<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Map your internal status to user-friendly text and Bootstrap badge styles
        $statusMap = [
            'pending' => ['text' => 'Pendiente', 'style' => 'warning'],
            'confirmed' => ['text' => 'Confirmado', 'style' => 'success'],
            'canceled' => ['text' => 'Cancelado', 'style' => 'danger'],
            'completed' => ['text' => 'Completado', 'style' => 'info'],
        ];

        return [
            'id' => $this->id,
            'folio' => 'R-' . str_pad($this->id, 5, '0', STR_PAD_LEFT),
            'client' => [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
            ],
            'branch' => $this->whenLoaded('branch', $this->branch->name ?? 'N/A'),
            'services' => $this->whenLoaded('services', $this->services->pluck('title')->join(', ')),
            'date' => $this->start_time->format('Y-m-d'),
            'time' => $this->start_time->format('g:i A'),
            'amount' => '$' . number_format($this->total_cost, 2),
            'status' => $statusMap[$this->status] ?? ['text' => 'Unknown', 'style' => 'secondary'],
            'start_time' => $this->start_time, // Pass the raw Carbon instance
            'date_time' => [
                'date_formatted' => $this->start_time->format('l, F j, Y'),
                'time_range' => $this->start_time->format('g:i A') . ' - ' . $this->end_time->format('g:i A'),
                'created_ago' => $this->created_at->diffForHumans(),
            ],
            'services_summary' => $this->whenLoaded('services', $this->services->pluck('title')->join(', ')),
            'services_detailed' => $this->when(
                $this->relationLoaded('services'),
                $this->services->map(function ($service) {
                    return [
                        'title' => $service->title,
                        'duration' => $service->duration . ' min',
                        'price' => '$' . number_format($service->pivot->price_at_booking, 2),
                    ];
                })
            ),
            'urls' => [
                // URL to view the reservation detail page in your admin panel
                'view' => url('/admin/reservations/' . $this->id),
            ],
        ];
    }
}
