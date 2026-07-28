<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\AdminReservasiController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatbotController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth', 'role:member,member_private'])->group(function () {
    Route::post('/chatbot', [ChatbotController::class, 'chat'])->name('chatbot');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/paket', [PaketController::class, 'index'])->name('paket');

    Route::get('/pembayaran/{id}', [PaymentController::class, 'show'])->name('pembayaran.show');

    Route::post('/pembayaran/{id}', [PaymentController::class, 'store'])->name('pembayaran.store');

    Route::post('/paket/batal', [PaketController::class, 'batal']) ->name('paket.batal');

    Route::middleware(['role:member,member_private'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi');

        Route::post('/reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');

        Route::get('/get-slot', [ReservasiController::class, 'getSlot'])->name('get.slot');

        Route::delete('/reservasi/{id}/batal', [ReservasiController::class, 'batal']) ->name('reservasi.batal');
    });

    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil');

    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profil.update');
});

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

        Route::get('/data-member', [AdminMemberController::class, 'dataMember'])->name('data-member');

        Route::get('/detail-member/{id}', [AdminMemberController::class, 'detailMember'])->name('detail-member');

        Route::post('/nonaktifkan/{id}', [AdminMemberController::class, 'nonaktifkan'])->name('nonaktifkan-member');

        Route::delete('/hapus-member/{id}', [AdminMemberController::class, 'hapusMember'])->name('hapus-member');

        Route::get('/data-reservasi', [AdminReservasiController::class, 'dataReservasi'])->name('data-reservasi');

        Route::get('/laporan-transaksi', [AdminReportController::class, 'laporanTransaksi'])->name('laporan-transaksi');

        Route::get('/export-pdf', [AdminReportController::class, 'exportPDF'])->name('export-pdf');

        Route::get('/pembayaran', [AdminPaymentController::class, 'index'])->name('pembayaran.index');

        Route::get('/pembayaran/{id}/bukti', [AdminPaymentController::class, 'showBukti'])->name('pembayaran.bukti');

        Route::post('/pembayaran/{id}/accept', [AdminPaymentController::class, 'accept'])->name('pembayaran.accept');
        
        Route::post('/pembayaran/{id}/reject', [AdminPaymentController::class, 'reject'])->name('pembayaran.reject');
        
        Route::delete('/pembayaran/{id}', [AdminPaymentController::class, 'destroy'])->name('pembayaran.destroy');
    });

Route::post('/demo-register', function () {
    // Simulasi paket
    $paket = \App\Models\Paket::first() ?? new \App\Models\Paket([
        'id' => 999,
        'nama_paket' => 'Demo Paket',
        'jumlah_sesi' => 16,
        'durasi_bulan' => 1
    ]);
    
    $randomStr = \Illuminate\Support\Str::random(5);
    
    // Fake User
    $fakeUser = new \App\Models\User([
        'name' => 'Demo User ' . $randomStr,
        'email' => 'demo' . $randomStr . '@example.com',
        'role' => 'member',
        'is_profile_complete' => true // Bypass profil
    ]);
    // Set a fake ID so that queries won't fail (though we will bypass them)
    $fakeUser->id = 999999; 

    // Fake Membership
    $fakeMembership = clone $paket; 
    $fakeMembership = new \App\Models\Membership([
        'user_id' => $fakeUser->id,
        'paket_id' => $paket->id,
        'sesi_tersisa' => $paket->jumlah_sesi ?? 16,
        'mulai' => now(),
        'expired' => now()->addMonths($paket->durasi_bulan ?? 1),
        'status' => 'aktif',
    ]);
    $fakeMembership->id = 999999;
    
    // Store in Session
    session([
        'demo_user' => $fakeUser,
        'demo_membership' => $fakeMembership,
        'demo_reservasi' => []
    ]);

    // Force Auth to pass middleware manually since Auth::login() requires DB
    return redirect()->route('dashboard');
})->name('demo.register');


require __DIR__ . '/auth.php';
