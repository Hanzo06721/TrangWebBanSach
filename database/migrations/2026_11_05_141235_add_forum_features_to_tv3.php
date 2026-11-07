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
        Schema::table('post_comments', function (Blueprint $table) {
            $table->string('ParentCommentID', 10)->nullable()->after('UserID');
            $table->string('ImagePath', 255)->nullable()->after('Content');
            $table->index(['PostID', 'ParentCommentID'], 'post_comments_post_parent_index');
            $table->foreign('ParentCommentID')->references('CommentID')->on('post_comments')->nullOnDelete();
        });

        Schema::create('post_reactions', function (Blueprint $table) {
            $table->string('ReactionID', 10)->primary();
            $table->string('PostID', 10);
            $table->string('UserID', 10);
            $table->enum('Type', ['Thích', 'Yêu thích', 'Wow', 'Haha', 'Buồn', 'Phẫn nộ']);
            $table->dateTime('CreatedAt')->useCurrent();
            $table->unique(['PostID', 'UserID']);
            $table->foreign('PostID')->references('PostID')->on('posts')->cascadeOnDelete();
            $table->foreign('UserID')->references('UserID')->on('users')->cascadeOnDelete();
        });

        Schema::create('comment_reactions', function (Blueprint $table) {
            $table->string('ReactionID', 10)->primary();
            $table->string('CommentID', 10);
            $table->string('UserID', 10);
            $table->enum('Type', ['Thích', 'Yêu thích', 'Wow', 'Haha', 'Buồn', 'Phẫn nộ']);
            $table->dateTime('CreatedAt')->useCurrent();
            $table->unique(['CommentID', 'UserID']);
            $table->foreign('CommentID')->references('CommentID')->on('post_comments')->cascadeOnDelete();
            $table->foreign('UserID')->references('UserID')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_reactions');
        Schema::dropIfExists('post_reactions');
        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropForeign(['ParentCommentID']);
            $table->dropIndex('post_comments_post_parent_index');
            $table->dropColumn(['ParentCommentID', 'ImagePath']);
        });
    }
};
