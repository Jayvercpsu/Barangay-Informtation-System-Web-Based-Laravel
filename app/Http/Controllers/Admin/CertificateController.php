<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CertificateReadyMail;
use App\Models\CertificateRequest;
use App\Notifications\ResidentActivityNotification;
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

        $requests = $query->latest()->get();

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

        $previousStatus = $certificateRequest->status;
        $previousAdminNotes = $certificateRequest->admin_notes;

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
        $certificateRequest->loadMissing('resident.user');

        $statusChanged = $previousStatus !== $request->status;
        $notesChanged = (string) $previousAdminNotes !== (string) $request->admin_notes;

        $resident = $certificateRequest->resident;

        if (($statusChanged || $notesChanged) && $resident?->user) {
            $message = $statusChanged
                ? "Your {$certificateRequest->certificate_label} request ({$certificateRequest->request_number}) is now {$this->formatStatusLabel($request->status)}."
                : "Admin added an update to your {$certificateRequest->certificate_label} request ({$certificateRequest->request_number}).";

            $resident->user->notify(new ResidentActivityNotification([
                'title' => 'Certificate Request Update',
                'message' => $message,
                'link' => route('resident.certificates.index'),
                'category' => 'certificate_update',
            ]));
        }

        if ($request->status === 'ready_for_pickup' && $statusChanged && $resident?->user) {
            if (!empty($resident->user->email)) {
                Mail::to($resident->user->email)->queue(new CertificateReadyMail($certificateRequest));
            }

            $adminNote = trim((string) $request->admin_notes);
            $smsMessage = "Hello {$resident->first_name}, your {$certificateRequest->certificate_label} ({$certificateRequest->request_number}) is ready for pickup at the Barangay Hall.";

            if ($adminNote !== '') {
                $smsMessage .= " Admin note: {$adminNote}.";
            }

            $smsMessage .= " - Community Service Desk";

            $this->smsService->send(
                $resident->contact_number_1,
                $smsMessage,
                [
                    'user_id' => $resident->user->id,
                    'filter_type' => 'certificate_ready',
                    'filter_value' => $certificateRequest->request_number,
                ]
            );
        }

        return back()->with('success', 'Certificate request status updated.');
    }

    private function formatStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'ready_for_pickup' => 'Ready for Pickup',
            'completed' => 'Completed',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }
}
