<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Rute Web SIMRS & RME)
|--------------------------------------------------------------------------
|
| Di sini adalah tempat pendaftaran semua rute web untuk aplikasi SIMRS & RME.
| Semua rute ini dimuat oleh RouteServiceProvider dan akan menggunakan middleware
| SetLocale untuk memproses pergantian bahasa melalui Session.
|
*/

// Rute Pengalihan Utama: Mengarahkan halaman beranda '/' langsung ke dashboard terproteksi
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rute Ganti Bahasa (Locale Switcher): Dapat diakses tanpa login untuk fleksibilitas bahasa
Route::get('/lang/{locale}', [LanguageController::class, 'switchLocale'])->name('lang.switch');

// Kelompok Rute Autentikasi: Hanya dapat diakses oleh pengguna yang belum masuk (guest)
Route::middleware('guest')->group(function () {
    // Alur masuk (Login)
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Alur daftar (Register)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Kelompok Rute Terproteksi: Hanya dapat diakses oleh pengguna yang telah login (auth)
Route::middleware('auth')->group(function () {
    // Alur keluar (Logout)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dasbor Pemantauan Utama
    Route::get('/dashboard', [MedicalRecordController::class, 'dashboard'])->name('dashboard');

    // Kelompok Rute Rekam Medis Elektronik (RME)
    Route::prefix('medical-records')->name('medical-records.')->group(function () {
        Route::get('/create', [MedicalRecordController::class, 'create'])->name('create');
        Route::post('/store', [MedicalRecordController::class, 'store'])->name('store');
        Route::delete('/{id}', [MedicalRecordController::class, 'destroy'])->name('destroy');
    });

    // Kelompok Rute Manajemen Pasien (CRUD Lengkap)
    Route::resource('patients', PatientController::class);

    // Kelompok Rute Manajemen Dokter (CRUD Lengkap - Admin Only)
    Route::resource('doctors', DoctorController::class)->except(['show']);

    // Kelompok Rute Manajemen Staff & Akun Pengguna (CRUD Lengkap - Admin Only)
    Route::resource('users', UserController::class)->except(['show']);

    // Kelompok Rute Integrasi Eksternal (BPJS & SATUSEHAT Kemenkes)
    Route::prefix('integration')->name('integration.')->group(function () {
        // Simulasi Sinkronisasi data RME ke sistem BPJS VClaim
        Route::post('/sync-bpjs/{id}', [IntegrationController::class, 'syncBpjs'])->name('sync.bpjs');
        // Simulasi Sinkronisasi data RME ke SATUSEHAT FHIR API Kemenkes
        Route::post('/sync-satusehat/{id}', [IntegrationController::class, 'syncSatusehat'])->name('sync.satusehat');
    });
});
