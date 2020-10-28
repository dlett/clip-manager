<?php

use App\Http\Controllers\CuratorController;
use App\Http\Controllers\ClipController;
use App\Http\Controllers\LoginController;
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

Route::get('/auth', [LoginController::class, 'redirectToProvider'])->name('login');
Route::get('/auth/callback', [LoginController::class, 'handleProviderCallback']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/clips', [ClipController::class, 'index'])->name('home');
    Route::get('/clips/{clip}', [ClipController::class, 'show'])->name('clip.show');
    Route::get('/curator/{curator}', [CuratorController::class, 'show'])->name('curator.show');
});

Route::get('/', function () {
    return view('welcome');
});
