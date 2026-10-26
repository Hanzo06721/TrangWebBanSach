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
        Schema::create('books', function (Blueprint $table) {
            $table->string('BookID', 10)->primary();
            $table->string('Title', 200);
            $table->string('AuthorName',100)->nullable();
            $table->string('ISBN',30)->nullable()->unique();
            $table->string('Publisher', 100)->nullable();
            $table->string('CategoryID', 10);
            $table->string('Description', 500)->nullable();
            $table->string('CoverImage', 100)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent();

            $table->foreign('CategoryID', 'FK_BOOK_CATEGORY')->references('CategoryID')->on('categories');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
