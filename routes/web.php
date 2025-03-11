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
use App\Http\Controllers\UtangKeOperatorController;
use App\Http\Controllers\UtangKeOperatorReportController;
use App\Http\Controllers\KreditNasabahPaluReportController;
use App\Http\Controllers\DanaTititpanPetaniReportController;
use App\Http\Controllers\KreditTrashController;
use App\Http\Controllers\RekapDanaController;
use App\Http\Controllers\KreditNasabahPaluController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KreditTitipanPetaniController;
use App\Http\Controllers\JPGR2Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

use App\Models\RekapDana;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::group(['middleware' => 'auth'], function () {

    Route::get('/kalkulator', function () {
        return view('kalkulator');
    });

    // Dashboard and other static pages
    Route::get('/', [HomeController::class, 'home']);
    // Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('billing', 'billing')->name('billing');
    Route::view('profile', 'profile')->name('profile');
    Route::view('rtl', 'rtl')->name('rtl');
    Route::view('user-management', 'laravel-examples/user-management')->name('user-management');
    Route::view('tables', 'tables')->name('tables');
    Route::view('virtual-reality', 'virtual-reality')->name('virtual-reality');
    Route::view('static-sign-in', 'static-sign-in')->name('sign-in');
    Route::view('static-sign-up', 'static-sign-up')->name('sign-up');


    Route::post('/upload-to-anonfiles', function (Request $request) {
        $file = $request->file('image');

        if (!$file) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        // Kirim ke AnonFiles
        $response = Http::attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
            ->post('https://api.anonfile.la/upload');

        $result = $response->json();

        if ($result['status']) {
            return response()->json([
                'file_url' => $result['data']['file']['url']['full']
            ]);
        } else {
            return response()->json(['error' => 'Upload failed'], 500);
        }
    });


    Route::resource('utang-ke-operator', UtangKeOperatorController::class);
    Route::get('/search-kredit', [UtangKeOperatorController::class, 'search'])->name('search.kredit');
    Route::get('/api/kredit/autocomplete', [UtangKeOperatorController::class, 'autocomplete']);
    Route::get('/search-nama', [UtangKeOperatorController::class, 'searchPetani'])->name('search.petani');
    Route::get('/laporan-rekapan-utang-ke-operator', [UtangKeOperatorReportController::class, 'generatePdf'])->name('laporan.operator');
    Route::get('/rekapan-utang-ke-operator/cetak-laporan', [UtangKeOperatorReportController::class, 'downloadLaporanKredit'])->name('laporan.operator.cetak');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');



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


    // Kredit Tititpan Petani routes
    Route::resource('dana-titipan-petani', KreditTitipanPetaniController::class);
    Route::get('/search-kredit', [KreditTitipanPetaniController::class, 'search'])->name('search.kredit');
    Route::get('/api/kredit/autocomplete', [KreditTitipanPetaniController::class, 'autocomplete']);
    Route::get('/search-petani', [KreditTitipanPetaniController::class, 'searchPetani'])->name('search.petani');
    Route::get('/laporan-rekapan-dana-titipan-petani', [DanaTititpanPetaniReportController::class, 'generatePdf'])->name('laporan.titipan');
    Route::get('/rekapan-rekapan-dana-titipan-petani/cetak-laporan', [DanaTititpanPetaniReportController::class, 'downloadLaporanKredit'])->name('laporan.titipan.cetak');



    // Kredit Nasabah Palu routes
    Route::resource('kredit-nasabah-palu', KreditNasabahPaluController::class);
    Route::get('/search-kredit', [KreditNasabahPaluController::class, 'search'])->name('search.kredit');
    Route::get('/api/kredit/autocomplete', [KreditNasabahPaluController::class, 'autocomplete']);
    Route::get('/search-nama', [KreditNasabahPaluController::class, 'searchPetani'])->name('search.petani');
    Route::get('/laporanNasabah-kredit', [KreditNasabahPaluReportController::class, 'generatePdf'])->name('laporanNasabah.kredit');
    Route::get('/kreditNasabah/cetak-laporan', [KreditNasabahPaluReportController::class, 'downloadLaporanKredit'])->name('laporanNasabah.kredit.cetak');

    // Kredit Trash routes
    Route::resource('kredit-ryclebin', KreditTrashController::class);
    // Route untuk halaman kreditTrash
    Route::get('/kredit-ryclebin', [KreditTrashController::class, 'index'])->name('kredit-ryclebin.index');

    Route::get('/search-kredit-ryclebin', [KreditTrashController::class, 'search'])->name('search.kredit');
    Route::get('/api/kredit-ryclebin/autocomplete', [KreditTrashController::class, 'autocomplete']);
    Route::get('/search-petani', [KreditTrashController::class, 'searchPetani'])->name('search.petani');
    // Route::get('/laporan-kredit-ryclebin', [KreditTrashController::class, 'generatePdf'])->name('laporan.kredit');
    // Route::get('/kredit-ryclebin/cetak-laporan', [KreditTrashController::class, 'downloadLaporanKredit'])->name('laporan.kredit.cetak');
    Route::patch('/kredit-ryclebin/restore/{id}', [KreditTrashController::class, 'restore'])->name('kredit-ryclebin.restore');


    Route::get('/get-pdf-link/{gilingId}', [ReceiptController::class, 'getPdfLinkFromDrive']);


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




    Route::resource('rekap-dana', RekapDanaController::class);
    Route::get('/daftar-rekapan-dana', [RekapDanaController::class, 'indexDaftar'])->name('rekapDana.index');
    Route::post('/rekap-dana/store', [RekapDanaController::class, 'store'])->name('rekapdana.store');
    // Di routes/web.php


    Route::resource('daftar-rekapan-kredit', KreditReportController::class);
    Route::get('/daftar-rekapan-kredit', [KreditReportController::class, 'index'])->name('rekapKredit.index');
    Route::post('/rekap-kredit/store', [KreditReportController::class, 'store'])->name('rekapKredit.store');


    Route::resource('DR-utang-ke-operator', UtangKeOperatorReportController::class);
    Route::get('/DR-utang-ke-operator', [UtangKeOperatorReportController::class, 'index'])->name('rekapUtangKeOperator.index');
    Route::post('/rekap-utang-ke-operator/store', [UtangKeOperatorReportController::class, 'store'])->name('rekapUtangKeOperator.store');


    Route::resource('DR-dana-titipan-petani', DanaTititpanPetaniReportController::class);
    Route::get('/DR-dana-titipan-petani', [DanaTititpanPetaniReportController::class, 'index'])->name('rekapKreditTitipanPetani.index');
    Route::post('/rekap-dana-titipan-petani/store', [DanaTititpanPetaniReportController::class, 'store'])->name('rekapKreditTitipanPetani.store');

    Route::resource('DR-kredit-nasabah-palu', KreditNasabahPaluReportController::class);
    Route::get('/DR-kredit-nasabah-palu', [KreditNasabahPaluReportController::class, 'index'])->name('rekapKreditNasabahPalu.index');
    Route::post('/rekap-kredit-nasabah-palu/store', [KreditNasabahPaluReportController::class, 'store'])->name('rekapKreditNasabahPalu.store');



    // Di routes/web.php
    Route::get('/find-pdf-kredit', [KreditReportController::class, 'findPdf']);
    Route::get('/find-pdf-nota-giling', [DaftarGilingController::class, 'findPdf']);
    Route::get('/find-pdf-operator', [UtangKeOperatorReportController::class, 'findPdf']);
    Route::get('/find-pdf-titipan', [DanaTititpanPetaniReportController::class, 'findPdf']);
    Route::get('/find-pdf-dana', [RekapDanaController::class, 'findPdf']);
    Route::get('/find-pdf-nasabah', [KreditNasabahPaluReportController::class, 'findPdf']);


    // User profile and authentication
    Route::get('/logout', [SessionsController::class, 'destroy']);
    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);


    Route::get('/daftar-gilings/{id}/url', [DaftarGilingController::class, 'getS3Url']);
    Route::get('/api/pdf-url/{gilingId}', [DaftarGilingController::class, 'getPdfUrl']);
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/kalkulator', function () {
        return view('kalkulator');
    });

    Route::get('/', [HomeController::class, 'home']);
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
