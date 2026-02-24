<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Resident;
use App\Models\User;
use App\Notifications\ResidentActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        /** @var Resident $resident */
        $resident = $user->resident;

        $complaints = $resident->complaints()->latest()->paginate(10);

        return view('resident.complaints.index', compact('complaints'));
    }

    public function create()
    {
        return view('resident.complaints.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|min:20',
            'image' => 'nullable|image|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        /** @var Resident $resident */
        $resident = $user->resident;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('complaints', 'public');
        }

        $year = date('Y');
        $count = Complaint::whereYear('created_at', $year)->count() + 1;
        $complaintNumber = 'CMP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $complaint = Complaint::create([
            'complaint_number' => $complaintNumber,
            'resident_id' => $resident->id,
            'description' => $request->description,
            'image_path' => $imagePath,
            'status' => 'submitted',
        ]);

        $admins = User::where('role', 'admin')->get();
        $hasUpload = !empty($imagePath);

        foreach ($admins as $admin) {
            $admin->notify(new ResidentActivityNotification([
                'title' => $hasUpload ? 'New complaint with upload' : 'New complaint submitted',
                'message' => $hasUpload
                    ? "{$resident->full_name} submitted {$complaint->complaint_number} with an image upload."
                    : "{$resident->full_name} submitted {$complaint->complaint_number}.",
                'link' => route('admin.complaints.show', $complaint),
                'category' => 'complaint',
            ]));
        }

        return redirect()->route('resident.complaints.index')
            ->with('success', 'Complaint submitted successfully.');
    }

    public function show(Complaint $complaint)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        /** @var Resident $resident */
        $resident = $user->resident;

        if ($resident->id !== $complaint->resident_id) {
            abort(403, 'Unauthorized');
        }

        return view('resident.complaints.show', compact('complaint'));
    }
}
