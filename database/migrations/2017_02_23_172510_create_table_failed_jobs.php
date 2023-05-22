<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTableFailedJobs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
    {
		Schema::create('failed_jobs', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('uuid')->index();
			$table->text('connection');
			$table->text('queue');
			$table->text('payload');
			$table->text('exception');
			$table->timestamp('failed_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
    {
		Schema::drop('failed_jobs');
	}

}
