<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\PetaniController;
use App\Http\Controllers\KreditController;
use App\Http\Controllers\DebitController;
use App\Http\Controllers\GilingController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\DaftarGilingController; // Tambahkan ini
use App\Http\Controllers\DaftarGilingTrashController; // Tambahkan ini
use App\Http\Controllers\KreditPembayaranKreditController; // Tambahkan ini
use App\Http\Controllers\KreditReportController;
use App\Http\Controllers\KreditTrashController;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    // Dashboard and other static pages
    Route::get('/', [HomeController::class, 'home']);
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('billing', 'billing')->name('billing');
    Route::view('profile', 'profile')->name('profile');
    Route::view('rtl', 'rtl')->name('rtl');
    Route::view('user-management', 'laravel-examples/user-management')->name('user-management');
    Route::view('tables', 'tables')->name('tables');
    Route::view('virtual-reality', 'virtual-reality')->name('virtual-reality');
    Route::view('static-sign-in', 'static-sign-in')->name('sign-in');
    Route::view('static-sign-up', 'static-sign-up')->name('sign-up');

    // Petani routes
    Route::resource('petani', PetaniController::class);
    Route::get('/search-petani', [KreditController::class, 'search'])->name('search-petani');
    Route::get('/petani/search', [PetaniController::class, 'searchPetani'])->name('petani.search');


    // Kredit routes
    Route::resource('kredit', KreditController::class);
    Route::get('/search-kredit', [KreditController::class, 'search'])->name('search.kredit');
    Route::get('/api/kredit/autocomplete', [KreditController::class, 'autocomplete']);
    Route::get('/search-petani', [KreditController::class, 'searchPetani'])->name('search.petani');
    Route::get('/laporan-kredit', [KreditReportController::class, 'generatePdf'])->name('laporan.kredit');
    Route::get('/kredit/cetak-laporan', [KreditController::class, 'downloadLaporanKredit'])->name('laporan.kredit.cetak');

    // Kredit Trash routes
    Route::resource('kredit-ryclebin', KreditTrashController::class);
    // Route untuk halaman kreditTrash
    Route::get('/kredit-ryclebin', [KreditTrashController::class, 'index'])->name('kredit-ryclebin.index');

    Route::get('/search-kredit-ryclebin', [KreditTrashController::class, 'search'])->name('search.kredit');
    Route::get('/api/kredit-ryclebin/autocomplete', [KreditTrashController::class, 'autocomplete']);
    Route::get('/search-petani', [KreditTrashController::class, 'searchPetani'])->name('search.petani');
    Route::get('/laporan-kredit-ryclebin', [KreditTrashController::class, 'generatePdf'])->name('laporan.kredit');
    Route::get('/kredit-ryclebin/cetak-laporan', [KreditTrashController::class, 'downloadLaporanKredit'])->name('laporan.kredit.cetak');
    Route::patch('/kredit-ryclebin/restore/{id}', [KreditTrashController::class, 'restore'])->name('kredit-ryclebin.restore');




    // Debit routes
    Route::resource('debit', DebitController::class);
    Route::get('/api/debit/search', [DebitController::class, 'search'])->name('debit.search');
    Route::get('/search-petani', [DebitController::class, 'search'])->name('search-petani');
    Route::get('/api/debit/search', [DebitController::class, 'search'])->name('debit.search');


    // Giling routes
    Route::resource('giling', GilingController::class);
    Route::get('/api/giling/search', [GilingController::class, 'search'])->name('api.giling.search');
    Route::get('/giling/cetak/{id}', [GilingController::class, 'cetakPDF'])->name('giling.cetak');

    Route::get('/petani/search', [GilingController::class, 'search'])->name('petani.search');
    Route::get('/search-petani', [GilingController::class, 'searchPetani'])->name('search.petani');

    // Daftar Giling routes
    Route::resource('daftar-giling', DaftarGilingController::class);
    Route::get('/search-daftar-giling', [DaftarGilingController::class, 'search'])->name('daftar-giling.search');
    Route::get('/generate-pdf/{gilingId}', [ReceiptController::class, 'generatePdf'])->name('generate.pdf');
    Route::get('/receipt/{id}', [ReceiptController::class, 'generatePdf']);
    Route::get('/giling/download-pdf', [GilingController::class, 'downloadPdf'])->name('giling.download_pdf');
    Route::get('/search-daftar-giling', [DaftarGilingController::class, 'search'])->name('daftar-giling.search');
    Route::delete('/daftar-giling/{id}', [DaftarGilingController::class, 'destroy'])->name('daftar-giling.destroy');

    Route::get('/receipts/print-latest', [ReceiptController::class, 'printLatest'])->name('receipt.print.latest');
    Route::get('/receipts/print/{id}', [ReceiptController::class, 'printPdf'])->name('receipt.print');

    Route::post('/kredit-pembayaran', [KreditPembayaranKreditController::class, 'store']);
    Route::get('/kredit-pembayaran/{pembayaranKreditId}', [KreditPembayaranKreditController::class, 'index']);
    Route::delete('/kredit-pembayaran/{pembayaranKreditId}/{kreditId}', [KreditPembayaranKreditController::class, 'destroy']);


    // Daftar Giling Trash routes
    Route::resource('daftar-giling-ryclebin', DaftarGilingTrashController::class);

    Route::get('/generate-pdf/{gilingId}', [DaftarGilingTrashController::class, 'generatePdf'])->name('generate.pdf');
    Route::get('/receipt/{id}', [DaftarGilingTrashController::class, 'generatePdf']);
    Route::get('/giling/download-pdf', [DaftarGilingTrashController::class, 'downloadPdf'])->name('giling.download_pdf');
    Route::get('/search-daftar-giling-ryclebin', [DaftarGilingTrashController::class, 'search'])->name('daftar-giling-ryclebin.search');
    Route::delete('/daftar-giling-ryclebin/{id}', [DaftarGilingTrashController::class, 'destroy'])->name('daftar-giling-ryclebin.destroy');
    Route::patch('/daftar-giling-ryclebin/restore/{id}', [DaftarGilingTrashController::class, 'restore'])->name('daftar-giling-ryclebin.restore');



    // User profile and authentication
    Route::get('/logout', [SessionsController::class, 'destroy']);
    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/session', [SessionsController::class, 'store']);
    Route::get('/session', [SessionsController::class, 'store']);
    Route::get('/login/forgot-password', [ResetController::class, 'create']);
    Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
    Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});
