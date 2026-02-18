<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Resident;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    public function __construct(private SmsService $smsService) {}

    public function index()
    {
        $blocks = Block::orderBy('block_number')->get();
        return view('admin.sms.index', compact('blocks'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:160',
            'filter_type' => 'required|in:all,block,pwd,senior',
            'block_id' => 'required_if:filter_type,block|exists:blocks,id',
        ]);

        $query = Resident::query();

        switch ($request->filter_type) {
            case 'block':
                $query->where('block_id', $request->block_id);
                break;
            case 'pwd':
                $query->where('is_pwd', true);
                break;
            case 'senior':
                $query->whereDate('birthdate', '<=', Carbon::now()->subYears(60));
                break;
        }

        $residents = $query->get();
        $sentCount = 0;

        foreach ($residents as $resident) {
            $success = $this->smsService->send(
                $resident->contact_number_1,
                $request->message,
                [
                    'user_id' => Auth::id(),
                    'filter_type' => $request->filter_type,
                    'filter_value' => $request->block_id ?? $request->filter_type,
                ]
            );
            if ($success) $sentCount++;
        }

        return back()->with('success', "SMS sent to {$sentCount} recipients.");
    }
}