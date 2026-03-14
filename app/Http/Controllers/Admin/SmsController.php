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
            'message'     => 'required|string|max:160',
            'filter_type' => 'required|in:all,block,pwd,senior',
            'block_id'    => 'nullable|required_if:filter_type,block|exists:blocks,id',
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

        $targets = collect();
        foreach ($residents as $resident) {
            foreach ([$resident->contact_number_1, $resident->contact_number_2] as $number) {
                $trimmed = trim((string) $number);
                if ($trimmed !== '') {
                    $targets->push([
                        'resident_id' => $resident->id,
                        'number' => $trimmed,
                    ]);
                }
            }
        }

        $targets = $targets->unique('number')->values();

        if ($targets->isEmpty()) {
            return back()->withErrors([
                'filter_type' => 'No cellphone numbers found for the selected filter.',
            ])->withInput();
        }

        $sentCount = 0;
        $debugLogs = [];

        foreach ($targets as $target) {
            $result = $this->smsService->send(
                $target['number'],
                $request->message,
                [
                    'user_id'      => Auth::id(),
                    'filter_type'  => $request->filter_type,
                    'filter_value' => $request->block_id ?? $request->filter_type,
                ]
            );

            $debugLogs[] = [
                'number'           => $target['number'],
                'normalized'       => $result['normalized_number'] ?? null,
                'success'          => $result['success'],
                'http_status'      => $result['status_code'],
                'gateway_response' => $result['response'],
            ];

            if ($result['success']) $sentCount++;
        }

        $totalTargets = $targets->count();
        $failedCount = $totalTargets - $sentCount;
        $debugPayload = [
                'residents_found' => $residents->count(),
                'targets_found'   => $targets->count(),
                'sent_count'      => $sentCount,
                'failed_count'    => $failedCount,
                'numbers'         => $targets->pluck('number')->toArray(),
                'logs'            => $debugLogs,
            ];

        if ($sentCount === 0) {
            return back()
                ->withErrors([
                    'filter_type' => "SMS sending failed for all {$totalTargets} recipients. Please verify gateway settings and recipient numbers.",
                ])
                ->with('sms_debug', $debugPayload);
        }

        return back()
            ->with('success', "SMS sent to {$sentCount} of {$totalTargets} recipients.")
            ->with('sms_debug', $debugPayload);
    }
}
