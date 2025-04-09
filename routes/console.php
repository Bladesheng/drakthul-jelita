<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

/**
 * Rolling database backup
 */
Schedule::call(function () {
	$dbPath = database_path('database.sqlite');
	if (!file_exists($dbPath)) {
		return;
	}

	$s3Path = 'db/database-' . now()->day . '.sqlite';

	Storage::disk('s3')->put($s3Path, file_get_contents($dbPath));
})->daily();
