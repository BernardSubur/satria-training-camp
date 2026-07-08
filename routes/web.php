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

require __DIR__ . '/auth.php';
