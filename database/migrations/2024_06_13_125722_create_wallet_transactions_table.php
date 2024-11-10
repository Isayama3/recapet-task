<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->decimal('amount');
            $table->decimal('fees')->nullable();
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('recipient_wallet_id')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('type_id');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('recipient_wallet_id')->references('id')->on('wallets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('wallet_transactions');
    }
};
