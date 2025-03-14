<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\PalletController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\QualityMarkController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Resource routes
    Route::resources([
        'warehouses' => WarehouseController::class,
        'lines' => LineController::class,
        'racks' => RackController::class,
        'pallets' => PalletController::class,
        'packages' => PackageController::class,
        'quality-marks' => QualityMarkController::class,
    ]);
});

require __DIR__.'/auth.php';
