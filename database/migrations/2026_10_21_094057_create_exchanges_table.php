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
        Schema::create('exchanges', function (Blueprint $table) {
            $table->string('ExchangeID', 10)->primary();
            $table->string('SenderID', 10);
            $table->string('PostID', 10);
            $table->string('OfferedBookID', 10);
            $table->string('Message', 500)->nullable();
            $table->enum('ExchangeStatus', ['Pending', 'Accepted', 'Rejected', 'Cancelled', 'Completed'])->default('Pending');
            $table->dateTime('CreatedAt')->useCurrent();

            $table->foreign('SenderID', 'FK_EXCHANGE_SENDER')->references('UserID')->on('users');
            $table->foreign('PostID', 'FK_EXCHANGE_POST')->references('PostID')->on('posts');
            $table->foreign('OfferedBookID', 'FK_EXCHANGE_BOOK')->references('BookID')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchanges');
    }
};
