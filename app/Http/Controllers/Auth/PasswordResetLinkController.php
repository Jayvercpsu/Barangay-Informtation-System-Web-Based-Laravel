<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordOtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    private const OTP_EXPIRY_MINUTES = 10;
    private const SESSION_RESET_EMAIL = 'password_reset_email';
    private const SESSION_VERIFIED_EMAIL = 'password_otp_verified_email';
    private const SESSION_VERIFIED_UNTIL = 'password_otp_verified_until';

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP for password reset.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $otp = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $validated['email']],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        try {
            Mail::to($validated['email'])->send(new PasswordOtpMail($otp));
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Unable to send OTP right now. Please try again.']);
        }

        $request->session()->put(self::SESSION_RESET_EMAIL, $validated['email']);
        $request->session()->forget([self::SESSION_VERIFIED_EMAIL, self::SESSION_VERIFIED_UNTIL]);

        return redirect()
            ->route('password.otp', ['email' => $validated['email']])
            ->with('status', 'A 6-digit OTP has been sent to your email.');
    }

    /**
     * Display OTP reset form.
     */
    public function showOtpForm(Request $request): View
    {
        $email = (string) ($request->query('email') ?? old('email', $request->session()->get(self::SESSION_RESET_EMAIL, '')));

        if ($email !== '') {
            $request->session()->put(self::SESSION_RESET_EMAIL, $email);
        }

        $otpVerified = $this->isOtpVerifiedForEmail($request, $email);

        return view('auth.reset-password-otp', [
            'email' => $email,
            'otpVerified' => $otpVerified,
        ]);
    }

    /**
     * Verify OTP first.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'digits:6'],
        ]);

        $otpRecord = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->first();

        if (!$otpRecord) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'Invalid or expired OTP. Please request a new one.']);
        }

        $expiresAt = Carbon::parse($otpRecord->created_at)->addMinutes(10);

        if ($expiresAt->isPast()) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'OTP expired. Please request a new one.']);
        }

        if (!Hash::check($validated['otp'], $otpRecord->token)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'Incorrect OTP. Please try again.']);
        }

        $request->session()->put(self::SESSION_RESET_EMAIL, $validated['email']);
        $request->session()->put(self::SESSION_VERIFIED_EMAIL, $validated['email']);
        $request->session()->put(self::SESSION_VERIFIED_UNTIL, now()->addMinutes(self::OTP_EXPIRY_MINUTES)->timestamp);

        return redirect()
            ->route('password.otp', ['email' => $validated['email']])
            ->with('status', 'OTP verified. You can now set your new password.');
    }

    /**
     * Reset password after OTP verification.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resetAfterOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!$this->isOtpVerifiedForEmail($request, $validated['email'])) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'Please verify OTP first.']);
        }

        $otpRecord = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->first();

        if (!$otpRecord) {
            $request->session()->forget([self::SESSION_VERIFIED_EMAIL, self::SESSION_VERIFIED_UNTIL]);

            return redirect()
                ->route('password.otp', ['email' => $validated['email']])
                ->withErrors(['otp' => 'OTP expired. Please request a new one.']);
        }

        $expiresAt = Carbon::parse($otpRecord->created_at)->addMinutes(self::OTP_EXPIRY_MINUTES);

        if ($expiresAt->isPast()) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
            $request->session()->forget([self::SESSION_VERIFIED_EMAIL, self::SESSION_VERIFIED_UNTIL]);

            return redirect()
                ->route('password.otp', ['email' => $validated['email']])
                ->withErrors(['otp' => 'OTP expired. Please request a new one.']);
        }

        $user = User::where('email', $validated['email'])->firstOrFail();

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'remember_token' => Str::random(60),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
        $request->session()->forget([
            self::SESSION_RESET_EMAIL,
            self::SESSION_VERIFIED_EMAIL,
            self::SESSION_VERIFIED_UNTIL,
        ]);

        event(new PasswordReset($user));

        return redirect()
            ->route('login')
            ->with('status', 'Password reset successful. You can now sign in.');
    }

    private function isOtpVerifiedForEmail(Request $request, string $email): bool
    {
        if ($email === '') {
            return false;
        }

        $verifiedEmail = (string) $request->session()->get(self::SESSION_VERIFIED_EMAIL, '');
        $verifiedUntil = (int) $request->session()->get(self::SESSION_VERIFIED_UNTIL, 0);

        if ($verifiedEmail !== $email || $verifiedUntil < now()->timestamp) {
            return false;
        }

        return true;
    }
}
