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
        Schema::create('reviews', function (Blueprint $table) {
            $table->string('ReviewID', 10)->primary();
            $table->string('TransactionID', 10);
            $table->string('ReviewerID', 10);
            $table->string('ReviewedUserID', 10);
            $table->integer('Rating');
            $table->string('Comment', 500)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();

            $table->foreign('TransactionID', 'FK_REVIEW_TRANSACTION')->references('TransactionID')->on('transactions');
            $table->foreign('ReviewerID', 'FK_REVIEW_REVIEWER')->references('UserID')->on('users');
            $table->foreign('ReviewedUserID', 'FK_REVIEW_REVIEWED_USER')->references('UserID')->on('users');
            $table->unique('TransactionID', 'UQ_REVIEW_TRANSACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
