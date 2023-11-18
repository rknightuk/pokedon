<?php

use App\Http\Controllers\MastodonAuthController;
use App\Http\Controllers\SiteController;
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

Route::get('/', [SiteController::class, 'index'])->name('welcome');
Route::get('/timeline', [SiteController::class, 'timeline'])->name('timeline');


Route::post('/auth/login', [MastodonAuthController::class, 'login'])->name('mastodon.login');
Route::get('/auth/callback', [MastodonAuthController::class, 'callback'])->name('mastodon.callback');
Route::get('/auth/logout', [MastodonAuthController::class, 'logout'])->name('mastodon.logout');
