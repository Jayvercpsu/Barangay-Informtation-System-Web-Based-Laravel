<?php

use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ResidentController as AdminResidentController;
use App\Http\Controllers\Admin\SmsController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Resident\CertificateController;
use App\Http\Controllers\Resident\ComplaintController;
use App\Http\Controllers\Resident\DashboardController;
use App\Http\Controllers\Resident\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('landing'))->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

require __DIR__.'/auth.php';

Route::get('/home', function () {
    if (Auth::check()) {
        /** @var User $user */
        $user = Auth::user();

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('resident.dashboard');
    }
    return redirect()->route('landing');
})->name('home');

Route::middleware(['auth', 'resident'])->prefix('resident')->name('resident.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');

    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
    Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/residents', [AdminResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/{resident}', [AdminResidentController::class, 'show'])->name('residents.show');
    Route::get('/residents/{resident}/edit', [AdminResidentController::class, 'edit'])->name('residents.edit');
    Route::put('/residents/{resident}', [AdminResidentController::class, 'update'])->name('residents.update');

    Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::put('/complaints/{complaint}/status', [AdminComplaintController::class, 'updateStatus'])->name('complaints.status');

    Route::get('/certificates', [AdminCertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificateRequest}', [AdminCertificateController::class, 'show'])->name('certificates.show');
    Route::put('/certificates/{certificateRequest}/status', [AdminCertificateController::class, 'updateStatus'])->name('certificates.status');

    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::get('/sms', [SmsController::class, 'index'])->name('sms.index');
    Route::post('/sms/send', [SmsController::class, 'send'])->name('sms.send');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('profile.password');

});