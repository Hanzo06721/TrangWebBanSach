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
        Schema::create('users', function (Blueprint $table) {
            $table->string('UserID', 10)->primary();
            $table->string('Email', 100);
            $table->string('Fullname', 100);
            $table->string('PasswordHash', 255);
            $table->string('RoleID', 10);
            $table->string('Phone',10);
            $table->boolean('Status')->default(1);
            $table->string('ProfilePicture', 100)->nullable(true);
            $table->dateTime('CreatedAt')->useCurrent();

            $table->foreign('RoleID', 'FK_USER_ROLE')->references('RoleID')->on('roles');
            $table->unique('Email');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
