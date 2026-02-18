<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateRequest;
use App\Models\Complaint;
use App\Models\Event;
use App\Models\Resident;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalResidents = Resident::count();
        $totalComplaints = Complaint::count();
        $pendingCertificates = CertificateRequest::where('status', 'pending')->count();
        $totalPwd = Resident::where('is_pwd', true)->count();
        $totalSeniors = Resident::whereDate('birthdate', '<=', Carbon::now()->subYears(60))->count();

        $today = Carbon::now()->format('m-d');
        $threeDaysLater = Carbon::now()->addDays(3)->format('m-d');

        $upcomingBirthdays = Resident::where(function ($q) {
            $q->where('is_pwd', true)
              ->orWhereDate('birthdate', '<=', Carbon::now()->subYears(60));
        })->get()->filter(function (Resident $r) {
            $birthday = Carbon::parse($r->birthdate)->setYear((int) date('Y'));
            $daysUntil = (int) Carbon::now()->diffInDays($birthday, false);
            return $daysUntil > 0 && $daysUntil <= 3;
        });

        $birthdayToday = Resident::where(function ($q) {
            $q->where('is_pwd', true)
              ->orWhereDate('birthdate', '<=', Carbon::now()->subYears(60));
        })->get()->filter(function (Resident $r) {
            return Carbon::parse($r->birthdate)->format('m-d') === date('m-d');
        });

        $upcomingEvents = Event::whereDate('event_date', '>=', Carbon::now())
            ->orderBy('event_date')
            ->take(5)
            ->get();

        $recentComplaints = Complaint::with('resident')->latest()->take(5)->get();
        $recentCertificates = CertificateRequest::with('resident')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalResidents', 'totalComplaints', 'pendingCertificates',
            'totalPwd', 'totalSeniors', 'upcomingBirthdays', 'birthdayToday',
            'upcomingEvents', 'recentComplaints', 'recentCertificates'
        ));
    }
}