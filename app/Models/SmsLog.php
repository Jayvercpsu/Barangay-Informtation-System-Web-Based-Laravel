<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsLog extends Model
{
    protected $fillable = [
        'user_id', 'recipient_number', 'message',
        'status', 'filter_type', 'filter_value',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}