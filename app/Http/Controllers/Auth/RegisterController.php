<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\Block;
use App\Models\Resident;
use App\Models\User;
use App\Services\ResidentIdService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function __construct(private ResidentIdService $residentIdService) {}

    public function showForm()
    {
        $blocks = Block::orderBy('block_number')->get();

        return view('auth.register', compact('blocks'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name'       => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:8|confirmed',
            'address'          => 'required|string|max:255',
            'contact_number_1' => 'required|string|max:20',
            'contact_number_2' => 'nullable|string|max:20',
            'birthdate'        => 'required|date|before:today',
            'occupation'       => 'nullable|string|max:100',
            'block_id'         => 'required|exists:blocks,id',
            'is_pwd'           => 'nullable|boolean',
        ]);

        $user = User::create([
            'name'     => $request->first_name . ' ' . $request->last_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'resident',
        ]);

        $resident = Resident::create([
            'resident_id'      => $this->residentIdService->generate(),
            'user_id'          => $user->id,
            'block_id'         => $request->block_id,
            'first_name'       => $request->first_name,
            'middle_name'      => $request->middle_name,
            'last_name'        => $request->last_name,
            'address'          => $request->address,
            'contact_number_1' => $request->contact_number_1,
            'contact_number_2' => $request->contact_number_2,
            'birthdate'        => $request->birthdate,
            'occupation'       => $request->occupation,
            'is_pwd'           => $request->boolean('is_pwd'),
        ]);

        Mail::to($user->email)->queue(new WelcomeMail($resident));

        Auth::login($user);

        return redirect()->route('resident.dashboard');
    }
}