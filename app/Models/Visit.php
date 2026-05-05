<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code_id',
        'ip_address',
        'user_agent',
    ];

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }
}
