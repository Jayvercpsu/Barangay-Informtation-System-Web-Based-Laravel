<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CertificateReadyMail;
use App\Models\CertificateRequest;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CertificateController extends Controller
{
    public function __construct(private SmsService $smsService) {}

    public function index(Request $request)
    {
        $query = CertificateRequest::with('resident.block');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('certificate_type', $request->type);
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        return view('admin.certificates.index', compact('requests'));
    }

    public function show(CertificateRequest $certificateRequest)
    {
        $certificateRequest->load('resident.block');
        return view('admin.certificates.show', compact('certificateRequest'));
    }

    public function updateStatus(Request $request, CertificateRequest $certificateRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,ready_for_pickup,completed',
            'admin_notes' => 'nullable|string',
        ]);

        $data = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ];

        if ($request->status === 'ready_for_pickup' && !$certificateRequest->ready_at) {
            $data['ready_at'] = now();
        }

        if ($request->status === 'completed' && !$certificateRequest->completed_at) {
            $data['completed_at'] = now();
        }

        $certificateRequest->update($data);

        if ($request->status === 'ready_for_pickup') {
            $resident = $certificateRequest->resident;
            Mail::to($resident->user->email)->queue(new CertificateReadyMail($certificateRequest));

            $this->smsService->send(
                $resident->contact_number_1,
                "Hello {$resident->first_name}, your {$certificateRequest->certificate_label} (#{$certificateRequest->request_number}) is ready for pickup at the Barangay Hall. - Community Service Desk",
                ['filter_type' => 'certificate_ready', 'filter_value' => $certificateRequest->request_number]
            );
        }

        return back()->with('success', 'Certificate request status updated.');
    }
}