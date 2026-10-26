<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('TransactionID', 10)->primary();
            $table->string('PostID', 10)->unique();
            $table->string('BuyerID', 10);
            $table->string('SellerID', 10);
            $table->enum('TransactionStatus', ['Pending', 'Accepted', 'Completed'])->default('Pending');
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('CompletedAt')->nullable();

            $table->foreign('PostID', 'FK_TRANS_POST')->references('PostID')->on('posts');
            $table->foreign('BuyerID','FK_TRANS_BUYER')->references('UserID')->on('users');
            $table->foreign('SellerID','FK_TRANS_SELLER')->references('UserID')->on('users');
            $table->unique('PostID', 'UQ_TRANSACTION_POST');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
