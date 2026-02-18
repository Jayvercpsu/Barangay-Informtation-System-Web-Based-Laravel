<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        /** @var Resident $resident */
        $resident = $user->resident()->with('block')->firstOrFail();

        $complaints = $resident->complaints()->latest()->take(5)->get();
        $certRequests = $resident->certificateRequests()->latest()->take(5)->get();

        return view('resident.dashboard', compact('resident', 'complaints', 'certRequests'));
    }
}