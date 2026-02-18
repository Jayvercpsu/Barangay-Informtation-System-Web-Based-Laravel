<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    public function view(User $user, Complaint $complaint): bool
    {
        return $user->isAdmin() || $user->resident?->id === $complaint->resident_id;
    }
}