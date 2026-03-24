<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Notifications\ResidentActivityNotification;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with('resident.block');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('complaint_number', 'like', "%{$request->search}%")
                ->orWhereHas('resident', fn($q) => $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%"));
        }

        $complaints = $query->latest()->get();

        return view('admin.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('resident.block');
        return view('admin.complaints.show', compact('complaint'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:submitted,acknowledged,completed',
            'admin_notes' => 'nullable|string',
        ]);

        $previousStatus = $complaint->status;
        $previousAdminNotes = $complaint->admin_notes;

        $data = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ];

        if ($request->status === 'acknowledged' && !$complaint->acknowledged_at) {
            $data['acknowledged_at'] = now();
        }

        if ($request->status === 'completed' && !$complaint->completed_at) {
            $data['completed_at'] = now();
        }

        $complaint->update($data);
        $complaint->loadMissing('resident.user');

        $statusChanged = $previousStatus !== $request->status;
        $notesChanged = (string) $previousAdminNotes !== (string) $request->admin_notes;

        $residentUser = $complaint->resident?->user;

        if (($statusChanged || $notesChanged) && $residentUser) {
            $message = $statusChanged
                ? "Your complaint ({$complaint->complaint_number}) is now {$this->formatStatusLabel($request->status)}."
                : "Admin added an update to your complaint ({$complaint->complaint_number}).";

            $residentUser->notify(new ResidentActivityNotification([
                'title' => 'Complaint Update',
                'message' => $message,
                'link' => route('resident.complaints.show', $complaint),
                'category' => 'complaint_update',
            ]));
        }

        return back()->with('success', 'Complaint status updated.');
    }

    private function formatStatusLabel(string $status): string
    {
        return match ($status) {
            'submitted' => 'Submitted',
            'acknowledged' => 'Acknowledged',
            'completed' => 'Completed',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }
}
