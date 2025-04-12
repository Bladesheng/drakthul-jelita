<?php

use App\Models\WowClass;
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
			$table->foreignIdFor(WowClass::class)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('screenshots', function (Blueprint $table) {
			$table->dropForeignIdFor(WowClass::class);
			$table->dropColumn('wow_class_id');
		});
	}
};
