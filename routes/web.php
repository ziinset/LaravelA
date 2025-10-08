<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\kontenController;
use App\Http\Controllers\siswaController;
use App\Http\Controllers\kbmController;
use Illuminate\Support\Facades\Route;

Route::get('/', [kontenController::class, 'landing'])->name('landing');
Route::get('/login', [adminController::class, 'formLogin'])->name('login');
Route::post('/login', [adminController::class, 'prosesLogin'])->name('login.post');
Route::get('/home', [siswaController::class, 'home'])->name('home');
Route::get('/siswa/create', [siswaController::class, 'create'])->name('siswa.create');
Route::post('/siswa/store', [siswaController::class, 'store'])->name('siswa.store');
Route::get('/siswa/{id}/edit', [siswaController::class, 'edit'])->name('siswa.edit');
Route::put('/siswa/{id}/update', [siswaController::class, 'update'])->name('siswa.update');
Route::get('/siswa/{id}/delete', [siswaController::class, 'destroy'])->name('siswa.delete');
Route::get('/logout', [adminController::class, 'logout'])->name('logout');
Route::get('/register', [adminController::class, 'formregister'])->name('register');
Route::post('/register', [adminController::class, 'prosesRegister'])->name('register.post');
Route::get('/detil/{id}', [kontenController::class, 'detil'])->name('detil');

// KBM (Jadwal) routes
Route::get('/kbm', [kbmController::class, 'index'])->name('kbm.index');
Route::get('/kbm/guru/{idguru}', [kbmController::class, 'showGuru'])->name('kbm.guru');
Route::get('/kbm/kelas/{idwalas}', [kbmController::class, 'showKelas'])->name('kbm.kelas');