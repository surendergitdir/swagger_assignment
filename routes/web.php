<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HierarchyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/', [HierarchyController::class, 'index']);
Route::get('/client/create', [HierarchyController::class, 'create'])->name('client.create');
Route::post('/client', [HierarchyController::class, 'store'])->name('client.store');
Route::get('/clients/import', [HierarchyController::class, 'importForm'])->name('clients.import.form');
Route::post('/clients/import', [HierarchyController::class, 'import'])->name('clients.import');
