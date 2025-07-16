<?php

use App\Models\Bahasa;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BahasaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrameworkController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\ProfilWebController;
use App\Http\Controllers\PortofolioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);






Route::get('/admin', [ProfilWebController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard');

Route::put('/admin/{profilWeb:id}', [ProfilWebController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard.update');


Route::middleware('auth')->group(function () {
    Route::resource('/admin/projek', PortofolioController::class)->parameters([
        'projek' => 'portofolio',
    ]);
    Route::post('/portofolio/urutan/swap', [PortofolioController::class, 'swapUrutanAjax'])->name('portofolio.swapUrutanAjax');


    Route::resource('/admin/bahasa', BahasaController::class);
    Route::resource('/admin/framework', FrameworkController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
