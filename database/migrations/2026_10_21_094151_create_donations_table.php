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
        Schema::create('donations', function (Blueprint $table) {
            $table->string('DonationID', 10)->primary();
            $table->string('UserID', 10);
            $table->string('PostID', 10);
            $table->string('ContactInfo', 200)->nullable();
            $table->enum('DonationStatus', ['Pending', 'Approved', 'Rejected', 'Completed'])->default('Pending');
            $table->dateTime('CreatedAt')->useCurrent();

            $table->foreign('UserID', 'FK_DONATION_USER')->references('UserID')->on('users');
            $table->foreign('PostID', 'FK_DONATION_POST')->references('PostID')->on('posts');
            $table->unique('PostID', 'UQ_DONATION_POST');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
