<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\CertificateRequest;
use App\Models\User;
use App\Notifications\ResidentActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $resident = Auth::user()->resident;
        $requests = $resident->certificateRequests()->latest()->get();
        return view('resident.certificates.index', compact('requests'));
    }

    public function create()
    {
        return view('resident.certificates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'certificate_type' => 'required|in:barangay_clearance,proof_of_residency,certificate_of_indigency,barangay_permit',
            'purpose' => 'required|string|min:10',
        ]);

        $resident = Auth::user()->resident;

        $year = date('Y');
        $count = CertificateRequest::whereYear('created_at', $year)->count() + 1;
        $requestNumber = 'REQ-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $certificateRequest = CertificateRequest::create([
            'request_number' => $requestNumber,
            'resident_id' => $resident->id,
            'certificate_type' => $request->certificate_type,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new ResidentActivityNotification([
                'title' => 'New certificate request',
                'message' => "{$resident->full_name} requested {$certificateRequest->certificate_label} ({$certificateRequest->request_number}).",
                'link' => route('admin.certificates.show', $certificateRequest),
                'category' => 'certificate',
            ]));
        }

        return redirect()->route('resident.certificates.index')
            ->with('success', 'Certificate request submitted successfully.');
    }
}
