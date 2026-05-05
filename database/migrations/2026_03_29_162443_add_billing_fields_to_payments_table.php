<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            // Stripe relations
            $table->string('stripe_customer_id')->nullable()->after('stripe_session_id');
            $table->string('stripe_charge_id')->nullable()->after('stripe_customer_id');

            // Customer info snapshot
            $table->string('customer_email')->nullable()->after('stripe_charge_id');
            $table->string('customer_name')->nullable()->after('customer_email');

            // Payment method details
            $table->string('payment_method')->nullable()->after('customer_name'); // card, oxxo, etc
            $table->string('card_brand')->nullable()->after('payment_method');   // visa, mastercard
            $table->string('card_last4', 4)->nullable()->after('card_brand');

            // Billing / invoice
            $table->string('receipt_url')->nullable()->after('card_last4');
            $table->string('invoice_url')->nullable()->after('receipt_url');
            $table->string('invoice_pdf')->nullable()->after('invoice_url'); // local file if you generate your own

            // Amount breakdown
            $table->decimal('amount_subtotal', 10, 2)->nullable()->after('invoice_pdf');
            $table->decimal('amount_total', 10, 2)->nullable()->after('amount_subtotal');
            $table->decimal('tax', 10, 2)->nullable()->after('amount_total');
            $table->string('currency_symbol', 5)->nullable()->after('tax');

            // Product context
            $table->string('product_name')->nullable()->after('currency_symbol');

            // Payment timing
            $table->timestamp('paid_at')->nullable()->after('product_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_customer_id',
                'stripe_charge_id',
                'customer_email',
                'customer_name',
                'payment_method',
                'card_brand',
                'card_last4',
                'receipt_url',
                'invoice_url',
                'invoice_pdf',
                'amount_subtotal',
                'amount_total',
                'tax',
                'currency_symbol',
                'product_name',
                'paid_at',
            ]);
        });
    }
};
