<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChekoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProdukController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisMerkController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;


Route::get('/', [CustomerProdukController::class, 'index'])->name('produk.customer');


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    Route::get('/register', [AuthController::class, 'registerView'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


Route::middleware(['auth', 'checkrole:admin,manajer'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/transaksi/{status}', [DashboardController::class, 'showByStatus'])
        ->name('admin.transaksi.by_status');
    Route::get('/transaksi/status/{status}', [TransactionController::class, 'byStatus'])->name('admin.transaksi.by_status');
    Route::patch('/admin/transaksi/{id}/status/{status}', [TransactionController::class, 'updateStatus'])
        ->name('admin.transaksi.updateStatus');
    Route::get('/admin/transaksi/selesai/export-pdf', [TransactionController::class, 'exportSelesaiPdf'])
        ->name('admin.transaksi.export_pdf');
    Route::get('/laporan', [LaporanController::class, 'produkTerjual'])->name('admin.laporan.penjualan');

    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/', [CustomerController::class, 'indexdata'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
    });

    Route::get('/jenis-merk', [JenisMerkController::class, 'index'])->name('jenis-merk.index');
    Route::post('/jenis-merk', [JenisMerkController::class, 'store'])->name('jenis-merk.store');
    Route::put('/jenis-merk/{type}/{id}', [JenisMerkController::class, 'update'])->name('jenis-merk.update');
    Route::delete('/jenis-merk/{type}/{id}', [JenisMerkController::class, 'destroy'])->name('jenis-merk.destroy');

    Route::prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('index');
        Route::get('/create', [KaryawanController::class, 'create'])->name('create');
        Route::post('/', [KaryawanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [KaryawanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KaryawanController::class, 'update'])->name('update');
        Route::delete('/{id}', [KaryawanController::class, 'destroy'])->name('destroy');
    });

    Route::post('/role', [RoleController::class, 'store'])->name('role.store');

    Route::prefix('produk')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth', 'checkrole:customer'])->group(function () {
    Route::prefix('keranjang')->name('keranjang.')->group(function () {
        Route::get('/', [KeranjangController::class, 'index'])->name('index');
        Route::post('/tambah', [KeranjangController::class, 'tambah'])->name('tambah');
        Route::post('/update', [KeranjangController::class, 'update'])->name('update');
        Route::post('/update-ajax', [KeranjangController::class, 'updateAjax'])->name('update.ajax');
        Route::delete('/hapus/{id}', [KeranjangController::class, 'hapus'])->name('hapus');
    });

    Route::post('/checkout', [ChekoutController::class, 'store'])->name('checkout.store');
    Route::post('/keranjang/beli-langsung', [ChekoutController::class, 'beliLangsung'])->name('keranjang.beliLangsung');
    Route::patch('/pesanan/{id}/batal', [TransactionController::class, 'batalCustomer'])->name('pesanan.customer.batal');
    Route::get('/pesanan', [TransactionController::class, 'pesanan'])->name('pesanan.customer');
    Route::get('/invoice/{id}', [TransactionController::class, 'downloadInvoice'])->name('invoice.download');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [CustomerProfileController::class, 'update'])->name('profile.update');
});
