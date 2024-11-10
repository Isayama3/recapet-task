<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		Schema::create('settings', function (Blueprint $table) {
			$table->id();
			$table->string('key');
			$table->string('title')->nullable();
			$table->longText('value');
			$table->string('display_name')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('wallet_transactions');
	}
};
