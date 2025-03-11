<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\LineController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('racks', RackController::class);
    Route::resource('lines', LineController::class);
    Route::resource('pallets', PalletController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('quality_marks', QualityMarkController::class);
});

require __DIR__.'/auth.php';
