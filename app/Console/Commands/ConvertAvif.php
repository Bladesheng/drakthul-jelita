<?php

namespace App\Console\Commands;

use App\Models\Screenshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConvertAvif extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:convert-avif';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Converts all screenshots to avif';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$screenshots = Screenshot::all();

		foreach ($screenshots as $index => $screenshot) {
			$this->info('Converting screenshot ' . $index + 1 . ' (id ' . $screenshot->id . ')');

			$url = config('filesystems.cdn_url') . '/' . $screenshot->path;
			$pngPath = sys_get_temp_dir() . '/' . Str::uuid() . '.png';

			$imageContents = file_get_contents($url);
			file_put_contents($pngPath, $imageContents);

			$pngImg = imagecreatefrompng($pngPath);
			if (!$pngImg) {
				$this->error('Unsupported image format');
				return;
			}

			$avifPath = sys_get_temp_dir() . '/' . Str::uuid() . '.avif';
			if (!imageavif($pngImg, $avifPath, 90)) {
				imagedestroy($pngImg);
				unlink($pngPath);
				return;
			}

			$path = Storage::disk('s3')->putFile($avifPath);

			$screenshot->path = $path;
			$screenshot->mime_type = 'image/avif';
			$screenshot->size = filesize($avifPath);
			$screenshot->save();

			imagedestroy($pngImg);
			unlink($pngPath);
			unlink($avifPath);
		}
	}
}
