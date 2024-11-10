<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		Schema::create('wallets', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id');
			$table->decimal('balance')->default('0');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('no action');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('wallets');
	}
};
