<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTableSessions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
    {
		Schema::create('sessions', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->integer('user_id')->unsigned()->nullable()->index('user_id');
			$table->string('ip_address', 45)->nullable()->index('ip_address');
			$table->text('user_agent')->nullable();
			$table->text('payload');
			$table->integer('last_activity')->unsigned()->index('last_activity');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
    {
		Schema::drop('session');
	}

}
