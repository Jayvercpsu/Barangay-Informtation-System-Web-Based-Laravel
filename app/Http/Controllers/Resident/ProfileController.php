<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        /** @var Resident $resident */
        $resident = $user->resident()->with('block')->firstOrFail();

        $blocks = Block::orderBy('block_number')->get();

        return view('resident.profile.edit', compact('resident', 'blocks'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        /** @var Resident $resident */
        $resident = $user->resident;

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
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($resident->profile_photo) {
                Storage::disk('public')->delete($resident->profile_photo);
            }
            $resident->profile_photo = $request->file('profile_photo')->store('profiles', 'public');
        }

        $resident->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'contact_number_1' => $request->contact_number_1,
            'contact_number_2' => $request->contact_number_2,
            'birthdate' => $request->birthdate,
            'occupation' => $request->occupation,
            'block_id' => $request->block_id,
            'is_pwd' => $request->boolean('is_pwd'),
            'profile_photo' => $resident->profile_photo,
        ]);

        $user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }
}