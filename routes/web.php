<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScreenshotController;
use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Route;

Route::get('/', [ScreenshotController::class, 'index'])->name('screenshots.index');
Route::get('screenshots/search', [ScreenshotController::class, 'search'])->name(
	'screenshots.search'
);

Route::resource('screenshots', ScreenshotController::class)
	->only(['create', 'store', 'edit', 'update', 'destroy'])
	->middleware([AdminOnly::class]);

Route::get('login', [AuthController::class, 'index']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
