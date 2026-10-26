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
        Schema::create('favorites', function (Blueprint $table) {
            $table->string('FavoriteID', 10)->primary();
            $table->string('UserID', 10);
            $table->string('PostID', 10);
            $table->dateTime('CreatedAt')->useCurrent();

            $table->foreign('UserID', 'FK_FAVORITE_USER')->references('UserID')->on('users');
            $table->foreign('PostID', 'FK_FAVORITE_POST')->references('PostID')->on('posts');
            $table->unique(['UserID', 'PostID'], 'UQ_FAVORITE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
