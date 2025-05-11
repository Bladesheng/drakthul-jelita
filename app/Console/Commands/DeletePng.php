<?php

namespace App\Console\Commands;

use App\Models\Screenshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeletePng extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:delete-png';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deletes all png screenshots';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$screenshots = Screenshot::where('mime_type', '=', 'image/png')->get();

		foreach ($screenshots as $index => $screenshot) {
			$this->info('Deleting screenshot ' . $index + 1 . ' (id ' . $screenshot->id . ')');

			Storage::disk('s3')->delete($screenshot->path);
		}
	}
}
