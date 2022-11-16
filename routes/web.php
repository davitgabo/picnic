<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [ExportController::class,'index']);

Route::post('/export', [ExportController::class,'export']);

Route::get('/export/all', [ExportController::class,'segmentExport']);

Route::post('/get/segments',[ExportController::class, 'segmentDescriptions']);
