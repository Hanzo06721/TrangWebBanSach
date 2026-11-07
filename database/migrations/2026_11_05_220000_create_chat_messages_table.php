<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->string('MessageID', 10)->primary();
            $table->string('PostID', 10);
            $table->string('TransactionID', 10)->nullable();
            $table->string('SenderID', 10);
            $table->text('Message');
            $table->dateTime('CreatedAt')->useCurrent();

            $table->index(['PostID', 'CreatedAt']);
            $table->foreign('PostID')->references('PostID')->on('posts')->cascadeOnDelete();
            $table->foreign('SenderID')->references('UserID')->on('users')->cascadeOnDelete();
            $table->foreign('TransactionID')->references('TransactionID')->on('transactions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
