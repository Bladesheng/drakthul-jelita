<?php

namespace App\Console\Commands;

use App\Models\Screenshot;
use Illuminate\Console\Command;

class SaveDimensions extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:save-dimensions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Saves width and heigth of all screenshots';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$screenshots = Screenshot::all();

		foreach ($screenshots as $index => $screenshot) {
			$this->info('Measuring screenshot ' . $index + 1 . ' (id ' . $screenshot->id . ')');

			$url = config('filesystems.cdn_url') . '/' . $screenshot->path;

			$imageContents = file_get_contents($url);
			$info = getimagesizefromstring($imageContents);

			if ($info) {
				$screenshot->width = $info[0];
				$screenshot->height = $info[1];
				$screenshot->save();
			} else {
				$this->error('Failed to get image size');
			}
		}
	}
}
