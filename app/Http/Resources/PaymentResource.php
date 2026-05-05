<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'confirmed' => ['text' => 'Pagado', 'style' => 'success'],
            'canceled' => ['text' => 'Cancelado', 'style' => 'danger'],
            'completed' => ['text' => 'Completado', 'style' => 'info'],
        ];

        return [
            'id' => $this->id,
            // Format the reservation ID into a user-friendly "Folio"
            'folio' => 'R-' . str_pad($this->id, 5, '0', STR_PAD_LEFT),
            'client_name' => $this->customer_name,
            'amount' => '$' . number_format($this->total_cost, 2),
            'date' => $this->created_at->format('Y-m-d'),
            'status' => $statusMap[$this->status] ?? ['text' => 'Unknown', 'style' => 'secondary'],
            'method' => 'Card', // Hardcoded for now, as this detail is on the Stripe object
            'urls' => [
                // URL to view the reservation detail page in your admin panel
                'view' => url('/admin/reservations/' . $this->id),
                // URL to the Stripe PaymentIntent dashboard for detailed info
                'stripe' => 'https://dashboard.stripe.com/payments/' . $this->stripe_payment_intent_id,
            ],
        ];
    }
}
