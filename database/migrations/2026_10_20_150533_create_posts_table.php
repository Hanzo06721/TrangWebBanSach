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
        Schema::create('posts', function (Blueprint $table) {
            $table->string('PostID',10)->primary();
            $table->string('BookID',10);
            $table->string('UserID', 10);
            $table->string('Title', 100);
            $table->decimal('Price', 18,2)->nullable();
            $table->enum('PostType', ['Bán', 'Trao đổi', 'Ký gửi', 'Quyên góp'])->nullable();
            $table->enum('Condition',['Mới', 'Như mới', 'Tốt', 'Ổn', 'Cũ']);
            $table->string('Description', 500)->nullable();
            $table->enum('PostStatus', ['Chờ duyệt', 'Đã duyệt', 'Bị từ chối'])->default('Chờ duyệt');


            $table->foreign('BookID', 'FK_POST_BOOK')->references('BookID')->on('books');
            $table->foreign('UserID', 'FK_POST_USER')->references('UserID')->on('users');

            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
