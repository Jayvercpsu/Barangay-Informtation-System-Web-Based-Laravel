<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateRequest extends Model
{
    protected $fillable = [
        'request_number', 'resident_id', 'certificate_type',
        'purpose', 'status', 'admin_notes', 'ready_at', 'completed_at',
    ];

    protected $casts = [
        'ready_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function getCertificateLabelAttribute(): string
    {
        return match($this->certificate_type) {
            'barangay_clearance' => 'Barangay Clearance',
            'proof_of_residency' => 'Proof of Residency',
            'certificate_of_indigency' => 'Certificate of Indigency',
            'barangay_permit' => 'Barangay Permit',
            default => ucfirst($this->certificate_type),
        };
    }
}