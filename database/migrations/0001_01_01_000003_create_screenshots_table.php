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
		Schema::create('screenshots', function (Blueprint $table) {
			$table->id();
			$table->string('path')->unique();
			$table->string('mime_type');
			$table->integer('size');
			$table->string('wow_name');
			$table->string('wow_class');
			$table->timestamps();

			$table->unique(['wow_name', 'wow_class']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('screenshots');
	}
};
