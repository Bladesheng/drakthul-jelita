<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('screenshots', function (Blueprint $table) {
			$table->dropIndex('screenshots_wow_name_wow_class_unique');
			$table->dropColumn('wow_class');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('screenshots', function (Blueprint $table) {
			//
		});
	}
};
