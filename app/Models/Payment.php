<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'memorial_id',

        // Stripe IDs
        'stripe_payment_intent_id',
        'stripe_session_id',
        'stripe_customer_id',
        'stripe_charge_id',

        // Amounts
        'amount',
        'amount_total',
        'amount_subtotal',
        'tax',
        'currency',
        'currency_symbol',

        // Status
        'status',
        'paid_at',

        // Customer snapshot
        'customer_email',
        'customer_name',

        // Payment method
        'payment_method',
        'card_brand',
        'card_last4',

        // Billing / invoice
        'receipt_url',
        'invoice_url',
        'invoice_pdf',

        // Product
        'product_name',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'date',
        'created_at' => 'datetime'
    ];
    protected $appends = ['folio', 'client', 'state'];

    /**
     * Get the user that made the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFolioAttribute()
    {
        return 'R-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
    public function getStateAttribute()
    {
        $state = $this->status;
        switch ($this->status) {
            case 'paid':
                $state = "Pagado";
                break;

            default:
                $state = "Pendiente";
                break;
        }
        return $state;
    }
    public function getClientAttribute()
    {
        return  $this->memorial->deceased_name ?? null;
    }
}
