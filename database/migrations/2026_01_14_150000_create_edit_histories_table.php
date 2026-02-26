<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edit_histories', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->unsignedBigInteger('row_id');
            $table->text('perubahan')->nullable();
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edit_histories');
    }
};

