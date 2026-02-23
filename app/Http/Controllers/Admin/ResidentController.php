<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Resident::with(['block', 'user']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('resident_id', 'like', "%{$request->search}%");
            });
        }

        if ($request->block_id) {
            $query->where('block_id', $request->block_id);
        }

        if ($request->filter === 'pwd') {
            $query->where('is_pwd', true);
        } elseif ($request->filter === 'senior') {
            $query->whereDate('birthdate', '<=', now()->subYears(60));
        } elseif ($request->filter === 'no_occupation') {
            $query->whereNull('occupation');
        }

        $residents = $query->latest()->paginate(20)->withQueryString();
        $blocks = Block::orderBy('block_number')->get();

        return view('admin.residents.index', compact('residents', 'blocks'));
    }

    public function show(Resident $resident)
    {
        $resident->load(['block', 'user', 'complaints', 'certificateRequests']);
        return view('admin.residents.show', compact('resident'));
    }

    public function edit(Resident $resident)
    {
        $blocks = Block::orderBy('block_number')->get();
        return view('admin.residents.edit', compact('resident', 'blocks'));
    }

    public function destroy(Resident $resident)
    {
        if ($resident->user) {
            $resident->user->delete();
        }
        $resident->delete();
        return redirect()->route('admin.residents.index')->with('success', 'Resident deleted successfully.');
    }

    public function update(Request $request, Resident $resident)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'contact_number_1' => 'required|string|max:20',
            'contact_number_2' => 'nullable|string|max:20',
            'birthdate' => 'required|date',
            'occupation' => 'nullable|string|max:100',
            'block_id' => 'required|exists:blocks,id',
            'is_pwd' => 'nullable|boolean',
            'email' => 'required|email|unique:users,email,' . $resident->user_id,
        ]);

        $resident->update($request->only([
            'first_name',
            'middle_name',
            'last_name',
            'address',
            'contact_number_1',
            'contact_number_2',
            'birthdate',
            'occupation',
            'block_id',
        ]));

        $resident->update(['is_pwd' => $request->boolean('is_pwd')]);

        $resident->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.residents.show', $resident)
            ->with('success', 'Resident updated successfully.');
    }
}
