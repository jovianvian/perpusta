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
        Schema::create('book_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->string('issue_type'); // damaged, missing_pages, wrong_content, other
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending');
            $table->timestamps();

            // Foreign keys
            // We use bigInteger for ids usually.
            // Assuming users and books tables exist.
            // If foreign key constraints fail (e.g. MyISAM), we can remove ->constrained().
            // But let's try to be standard first.
            // However, to be safe and avoid errors if table names differ slightly or types differ, 
            // I will just index them for now or use loose coupling if I am not 100% sure of the exact schema of books (e.g. if it uses uuid).
            // But 'books' table is standard in this project.
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_reports');
    }
};
