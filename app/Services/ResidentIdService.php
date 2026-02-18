<?php

namespace App\Services;

use App\Models\Resident;

class ResidentIdService
{
    public function generate(): string
    {
        $year = date('Y');
        $lastResident = Resident::where('resident_id', 'like', "{$year}-%")
            ->orderByDesc('id')
            ->first();

        if ($lastResident) {
            $lastNumber = (int) substr($lastResident->resident_id, 5);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$year}-{$newNumber}";
    }
}