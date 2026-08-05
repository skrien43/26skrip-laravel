<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AdminMgmtController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PemindahanController;

//LANDING
Route::get('/', [LandingController::class, 'index'])->name('landing');


//AUTH
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//user BRW
Route::middleware(['role:mhs,dsn'])->group(function () {
    Route::get('/dashboard', [PeminjamanController::class, 'getHistory'])->name('dashboard.peminjaman');
    Route::get('/dashboard/form-peminjaman', [PeminjamanController::class, 'showForm'])->name('form.peminjaman');
    Route::post('/dashboard/form-peminjaman', [PeminjamanController::class, 'store'])->name('form.peminjaman.store');
    Route::get('/peminjaman/OTP', [PeminjamanController::class, 'showOTP'])->name('otp.show');
});


//user MV
Route::middleware(['role:dsn'])->group(function () {
    Route::get('/dashboard/pemindahan', [PemindahanController::class, 'getHistory'])->name('dashboard.pemindahan');
    Route::get('/dashboard/pemindahan/form-pemindahan', [PemindahanController::class, 'showForm'])->name('form.pemindahan');
    Route::post('/dashboard/pemindahan/form-pemindahan', [PemindahanController::class, 'store'])->name('form.pemindahan.store');
});


//shared USER
Route::middleware(['role:mhs,dsn'])->group(function () {
    Route::get('/barang/search', [BarangController::class, 'search'])->name('barang.search');
    Route::get('/matkul/search', [PeminjamanController::class, 'getMatkul'])->name('matkul.search');
    Route::get('/dashboard/pemindahan/jadwal/dosen', [PemindahanController::class, 'getJadwalDosen'])->name('jadwal.dosen');
    Route::get('/dashboard/pemindahan/ruangan/kosong', [PemindahanController::class, 'getRuanganKosong'])->name('ruangan.kosong');
});


//ADMIN
Route::middleware(['role:adm'])->group(function () {
    //BRW
    Route::get('/admin', [AdminController::class, 'dashboardBRW'])->name('admin.peminjaman');
    Route::post('/peminjaman/acc/{id}', [AdminController::class, 'brw_acc'])->name('peminjaman.acc');
    Route::post('/peminjaman/selesai/{id}', [AdminController::class, 'brg_return'])->name('peminjaman.selesai');
    Route::delete('/admin/peminjaman/{id}/hapus', [AdminController::class, 'destroy_BRW'])->name('brw.destroy');

    //BARANG
    Route::get('/admin/manage-perangkat', [BarangController::class, 'brg_index'])->name('brg.index');
    Route::post('/barang', [BarangController::class, 'store'])->name('brg.store');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('brg.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('brg.destroy');

    //MV
    Route::get('/admin/pemindahan', [AdminController::class, 'dashboardMV'])->name('admin.pemindahan');
    Route::post('/pemindahan/update', [AdminController::class, 'mv_update'])->name('pemindahan.update');
    Route::delete('/admin/pemindahan/{id}/hapus', [AdminController::class, 'destroy_MV'])->name('mv.destroy');

    //ROOMS
    Route::get('/admin/manage-ruangan', [RuanganController::class, 'room_index'])->name('room.index');
    Route::get('/admin/room/schedule', [RuanganController::class, 'getRoomSchedule'])->name('room.schedule');
    Route::post('/admin/room/update', [RuanganController::class, 'updateJadwal'])->name('room.update');

    //MGMT ADMIN
    Route::get('/admin/manage-admin', [AdminMgmtController::class, 'index'])->name('admin.mgmt.index');
    Route::post('/admin/manage-admin', [AdminMgmtController::class, 'store'])->name('admin.mgmt.store');
    Route::put('/admin/manage-admin/{id}', [AdminMgmtController::class, 'update'])->name('admin.mgmt.update');
    Route::delete('/admin/manage-admin/{id}', [AdminMgmtController::class, 'destroy'])->name('admin.mgmt.destroy');

    //LAPORAN
    Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
    Route::get('/admin/laporan/export/peminjaman', [LaporanController::class, 'exportPeminjaman'])->name('laporan.export.peminjaman');
    Route::get('/admin/laporan/export/pemindahan', [LaporanController::class, 'exportPemindahan'])->name('laporan.export.pemindahan');
});
