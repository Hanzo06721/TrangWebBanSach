<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'CreatedAt')) {
                $table->dateTime('CreatedAt')->nullable()->useCurrent()->after('PostStatus');
            }
            if (! Schema::hasColumn('posts', 'UpdatedAt')) {
                $table->dateTime('UpdatedAt')->nullable()->useCurrent()->after('CreatedAt');
            }
        });

        Schema::create('post_images', function (Blueprint $table) {
            $table->string('ImageID', 10)->primary();
            $table->string('PostID', 10);
            $table->string('ImagePath', 255);
            $table->boolean('IsPrimary')->default(false);
            $table->dateTime('CreatedAt')->useCurrent();
            $table->foreign('PostID')->references('PostID')->on('posts')->cascadeOnDelete();
        });

        Schema::create('post_comments', function (Blueprint $table) {
            $table->string('CommentID', 10)->primary();
            $table->string('PostID', 10);
            $table->string('UserID', 10);
            $table->string('Content', 500);
            $table->enum('Status', ['Hiển thị', 'Đã ẩn'])->default('Hiển thị');
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->nullable();
            $table->foreign('PostID')->references('PostID')->on('posts')->cascadeOnDelete();
            $table->foreign('UserID')->references('UserID')->on('users')->cascadeOnDelete();
        });

        Schema::create('post_reports', function (Blueprint $table) {
            $table->string('ReportID', 10)->primary();
            $table->string('PostID', 10);
            $table->string('UserID', 10);
            $table->string('Reason', 500);
            $table->enum('Status', ['Chờ xử lý', 'Đã xử lý', 'Đã từ chối'])->default('Chờ xử lý');
            $table->dateTime('CreatedAt')->useCurrent();
            $table->foreign('PostID')->references('PostID')->on('posts')->cascadeOnDelete();
            $table->foreign('UserID')->references('UserID')->on('users')->cascadeOnDelete();
            $table->unique(['PostID', 'UserID']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_reports');
        Schema::dropIfExists('post_comments');
        Schema::dropIfExists('post_images');

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['CreatedAt', 'UpdatedAt']);
        });
    }
};
