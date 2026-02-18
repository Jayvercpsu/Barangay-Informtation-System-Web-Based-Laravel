<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    protected $fillable = [
        'complaint_number', 'resident_id', 'description',
        'image_path', 'status', 'admin_notes',
        'acknowledged_at', 'completed_at',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }
}