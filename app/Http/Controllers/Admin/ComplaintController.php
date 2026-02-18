<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
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

        $complaints = $query->latest()->paginate(20)->withQueryString();

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

        return back()->with('success', 'Complaint status updated.');
    }
}