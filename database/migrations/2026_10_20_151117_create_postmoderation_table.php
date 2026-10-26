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
        Schema::create('postmoderation', function (Blueprint $table) {
            $table->string('ModerationID', 10)->primary();
            $table->string('PostID', 10);
            $table->string('AdminID', 10);
            $table->enum('Status', ['Approved','Rejected',]);
            $table->string('Reason', 500)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();

            $table->foreign('PostID', 'FK_POSTMO_POST')->references('PostID')->on('posts');
            $table->foreign('AdminID', 'FK_POSTMO_ADMIN')->references('UserID')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postmoderation');
    }
};
