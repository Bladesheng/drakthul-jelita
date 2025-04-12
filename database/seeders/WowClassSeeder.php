<?php

namespace Database\Seeders;

use App\Models\WowClass;
use Illuminate\Database\Seeder;

class WowClassSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$classes = [
			['name' => 'death knight', 'color' => '#C41E3A'],
			['name' => 'demon hunter', 'color' => '#A330C9'],
			['name' => 'druid', 'color' => '#FF7C0A'],
			['name' => 'evoker', 'color' => '#33937F'],
			['name' => 'hunter', 'color' => '#AAD372'],
			['name' => 'mage', 'color' => '#3FC7EB'],
			['name' => 'monk', 'color' => '#00FF98'],
			['name' => 'paladin', 'color' => '#F48CBA'],
			['name' => 'priest', 'color' => '#FFFFFF'],
			['name' => 'rogue', 'color' => '#FFF468'],
			['name' => 'shaman', 'color' => '#0070DD'],
			['name' => 'warlock', 'color' => '#8788EE'],
			['name' => 'warrior', 'color' => '#C69B6D'],
		];

		foreach ($classes as $class) {
			WowClass::firstOrCreate(['name' => $class['name']], $class);
		}
	}
}
