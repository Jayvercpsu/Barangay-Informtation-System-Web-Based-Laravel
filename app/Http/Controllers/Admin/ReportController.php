<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $blocks = Block::orderBy('block_number')->get();

        $query = Resident::with('block');

        if ($request->block_id) {
            $query->where('block_id', $request->block_id);
        }

        if ($request->filter === 'senior') {
            $query->whereDate('birthdate', '<=', Carbon::now()->subYears(60));
        } elseif ($request->filter === 'pwd') {
            $query->where('is_pwd', true);
        } elseif ($request->filter === 'no_occupation') {
            $query->whereNull('occupation');
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, Resident> $residents */
        $residents = $query->orderBy('last_name')->get();

        $stats = [
            'total' => $residents->count(),
            'senior' => $residents->filter(fn(Resident $r) => $r->isSenior())->count(),
            'pwd' => $residents->where('is_pwd', true)->count(),
            'no_occupation' => $residents->filter(fn(Resident $r) => empty($r->occupation))->count(),
        ];

        return view('admin.reports.index', compact('residents', 'blocks', 'stats'));
    }
}