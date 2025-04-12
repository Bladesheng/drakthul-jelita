<?php

namespace App\Console\Commands;

use App\Models\Screenshot;
use App\Models\WowClass;
use Illuminate\Console\Command;

class MigrateWowClassNamesToIds extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'data:migrate-wow-classes';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Convert wow_class string to wow_class_id foreign key';

	/**
	 * Execute the console command.
	 */
	public function handle(): void
	{
		$count = 0;

		foreach (Screenshot::all() as $screenshot) {
			$wowClass = WowClass::where('name', $screenshot->wow_class)->first();

			$screenshot->wow_class_id = $wowClass->id;
			$screenshot->save();

			$count++;
		}

		$this->info("Done - updated {$count} screenshots");
	}
}
