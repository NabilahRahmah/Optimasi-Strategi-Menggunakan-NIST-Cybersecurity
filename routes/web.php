<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\HasilController;

//Super Admin
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\FrameworkController as SuperAdminFrameworkController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;

//Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DokpendukungController as AdminDokpendukungController;
use App\Http\Controllers\Admin\AssessmentController as AdminAssessmentController;


//Approver
use App\Http\Controllers\Approver\DashboardController as ApproverDashboard;
use App\Http\Controllers\Approver\VerifikasiController;
use App\Http\Controllers\Approver\DokpendukungController as ApproverDokpendukungController;

//User
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\DokpendukungController as UserDokpendukungController;
use App\Http\Controllers\User\HasilController as UserHasilController;
use App\Http\Controllers\User\AssessmentController as UserAssessmentController;

use Illuminate\Support\Facades\Route;

// -------   ROOT   --------- //

Route::get('/', function () {
    return view('auth.login');
});

//DASHBOARD
Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    if ($role == 'admin_super') {
        return redirect()->route('superadmin.dashboard');
    } elseif ($role == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role == 'approver') {
        return redirect()->route('approver.dashboard');
    } else {
        // Default lari ke user biasa
        return redirect()->route('user.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute Profile Bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// GRUP SUPERADMIN
// ==========================================
// ── Super Admin ───────────────────────────────────────
Route::middleware(['auth', 'role:admin_super'])
    ->prefix('superadmin')
    ->group(function () {

        Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])
            ->name('superadmin.dashboard');

        // Framework — pakai alias yang sudah diimport
        Route::resource('framework', SuperAdminFrameworkController::class)
            ->names([
                'index' => 'superadmin.frameworks.index',
                'create' => 'superadmin.frameworks.create',
                'store' => 'superadmin.frameworks.store',
                'show' => 'superadmin.frameworks.show',
                'edit' => 'superadmin.frameworks.edit',
                'update' => 'superadmin.frameworks.update',
                'destroy' => 'superadmin.frameworks.destroy',
            ]);

        Route::resource('users', SuperAdminUserController::class)
            ->names('superadmin.users');
        // Domain management nested di framework
        Route::get('/framework/{id}/domains', [SuperAdminFrameworkController::class, 'domains'])
            ->name('superadmin.frameworks.domains');
        Route::post('/framework/{id}/domains', [SuperAdminFrameworkController::class, 'storeDomain'])
            ->name('superadmin.frameworks.domains.store');
        Route::delete('/framework/{id}/domains/{domainId}', [SuperAdminFrameworkController::class, 'destroyDomain'])
            ->name('superadmin.frameworks.domains.destroy');
    });
// Route::middleware(['auth', 'role:admin_super'])->prefix('superadmin')->name('superadmin.')->group(function () {
//     Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
//     Route::resource('framework', \App\Http\Controllers\SuperAdmin\FrameworkController::class);
//     Route::resource('users', \App\Http\Controllers\SuperAdmin\UserController::class);
// });

// ==========================================
// GRUP ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');

        // ── Assessment (kategori + pertanyaan + assign akses) ──
        Route::get('/assessment', [AdminAssessmentController::class, 'index'])->name('admin.assessment.index');
        Route::get('/assessment/create', [AdminAssessmentController::class, 'create'])->name('admin.assessment.create');
        Route::post('/assessment', [AdminAssessmentController::class, 'store'])->name('admin.assessment.store');
        Route::get('/assessment/{id}/edit', [AdminAssessmentController::class, 'edit'])->name('admin.assessment.edit');
        Route::put('/assessment/{id}', [AdminAssessmentController::class, 'update'])->name('admin.assessment.update');
        Route::delete('/assessment/{id}', [AdminAssessmentController::class, 'destroy'])->name('admin.assessment.destroy');

        // Assign user & approver
        Route::put('/assessment/{framework_id}/assign', [AdminAssessmentController::class, 'assign'])->name('admin.assessment.assign');

        // Pertanyaan (route statis SEBELUM {id})
        Route::get('/assessment/pertanyaan/create', [AdminAssessmentController::class, 'createPertanyaan'])->name('admin.pertanyaan.create');
        Route::post('/assessment/pertanyaan', [AdminAssessmentController::class, 'storePertanyaan'])->name('admin.pertanyaan.store');
        Route::get('/assessment/pertanyaan/{id}/edit', [AdminAssessmentController::class, 'editPertanyaan'])->name('admin.pertanyaan.edit');
        Route::put('/assessment/pertanyaan/{id}', [AdminAssessmentController::class, 'updatePertanyaan'])->name('admin.pertanyaan.update');
        Route::delete('/assessment/pertanyaan/{id}', [AdminAssessmentController::class, 'destroyPertanyaan'])->name('admin.pertanyaan.destroy');
        Route::post('/assessment/{framework_id}/jawaban', [AdminAssessmentController::class, 'saveJawaban'])->name('admin.assessment.saveJawaban');

        // Dokumen Pendukung → folder admin/dokpendukung
        Route::get('/dokpendukung', [AdminDokpendukungController::class, 'index'])->name('admin.dokpendukung.index');
        Route::get('/dokpendukung/create', [AdminDokpendukungController::class, 'create'])->name('admin.dokpendukung.create');
        Route::post('/dokpendukung', [AdminDokpendukungController::class, 'store'])->name('admin.dokpendukung.store');

        // ← Route dengan {id} SELALU paling bawah!
        Route::get('/dokpendukung/{id}/preview', [AdminDokpendukungController::class, 'preview'])->name('admin.dokpendukung.preview');
        Route::get('/dokpendukung/{id}/download', [AdminDokpendukungController::class, 'download'])->name('admin.dokpendukung.download');
        Route::delete('/dokpendukung/{id}', [AdminDokpendukungController::class, 'destroy'])->name('admin.dokpendukung.destroy');
    });

// ==========================================
// GRUP APPROVER
// ==========================================
Route::middleware(['auth', 'role:approver'])
    ->prefix('approver')
    ->group(function () {
        Route::get('/dashboard', [ApproverDashboard::class, 'index'])
            ->name('approver.dashboard');

        // Verifikasi Assessment
        Route::get('/verifikasi', [VerifikasiController::class, 'index'])
            ->name('approver.verifikasi.index');
        Route::get('/verifikasi/approved', [VerifikasiController::class, 'approved'])
            ->name('approver.verifikasi.approved');
        Route::post('/verifikasi/{assessment}/finalisasi', [VerifikasiController::class, 'finalisasi'])
            ->name('approver.verifikasi.finalisasi');
        Route::get('/verifikasi/{assessment}', [VerifikasiController::class, 'show'])
            ->name('approver.verifikasi.show');
        Route::post('/verifikasi/{jawaban}/item', [VerifikasiController::class, 'verifikasiItem'])
            ->name('approver.verifikasi.item');

        // Rekomendasi Manual
        Route::get('/rekomendasi', [\App\Http\Controllers\Approver\RekomendasiController::class, 'index'])
            ->name('approver.rekomendasi.index');
        Route::get('/rekomendasi/{assessment}/create', [\App\Http\Controllers\Approver\RekomendasiController::class, 'create'])
            ->name('approver.rekomendasi.create');
        Route::post('/rekomendasi/{assessment}', [\App\Http\Controllers\Approver\RekomendasiController::class, 'store'])
            ->name('approver.rekomendasi.store');
        Route::delete('/rekomendasi/{rekomendasi}', [\App\Http\Controllers\Approver\RekomendasiController::class, 'destroy'])
            ->name('approver.rekomendasi.destroy');

        // Hasil Assessment
        Route::get('/hasil', [\App\Http\Controllers\Approver\HasilController::class, 'index'])
            ->name('approver.hasil.index');
        Route::get('/hasil/{assessment}', [\App\Http\Controllers\Approver\HasilController::class, 'show'])
            ->name('approver.hasil.show');
    });

// ==========================================
// GRUP USER
// ==========================================
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->group(function () {
        Route::get('/dashboard', [UserDashboard::class, 'index'])
            ->name('user.dashboard');

        // Self Assessment
        Route::get('/assessment/create', [UserAssessmentController::class, 'create'])
            ->name('assessment.create');
        Route::post('/assessment/store', [UserAssessmentController::class, 'store'])
            ->name('assessment.store');
        Route::get('/assessment/{assessment}/revisi', [UserAssessmentController::class, 'revisi'])
            ->name('assessment.revisi');
        Route::post('/assessment/{assessment}/revisi', [UserAssessmentController::class, 'simpanRevisi'])
            ->name('assessment.simpanRevisi');

        // Dokumen Pendukung → folder user/dokpendukung
        Route::get('/dokpendukung', [UserDokpendukungController::class, 'index'])
            ->name('user.dokpendukung.index');
        Route::get('/dokpendukung/create', [UserDokpendukungController::class, 'create'])
            ->name('user.dokpendukung.create');
        Route::post('/dokpendukung', [UserDokpendukungController::class, 'store'])
            ->name('user.dokpendukung.store');
        Route::delete('/dokpendukung/{id}', [UserDokpendukungController::class, 'destroy'])
            ->name('user.dokpendukung.destroy');
        Route::get('/dokpendukung/{id}/download', [UserDokpendukungController::class, 'download'])
            ->name('user.dokpendukung.download');
        Route::get('dokpendukung/{id}/preview', [UserDokpendukungController::class, 'preview'])
            ->name('user.dokpendukung.preview');


        // Hasil
        Route::get('/hasil', [UserHasilController::class, 'index'])
            ->name('user.hasil.index');
        Route::get('/hasil/{assessment}', [UserHasilController::class, 'show'])
            ->name('user.hasil.show');
        Route::get('/hasil/{assessment}/pdf', [UserHasilController::class, 'cetakPDF'])
            ->name('user.hasil.cetakPDF');
    });

require __DIR__ . '/auth.php';