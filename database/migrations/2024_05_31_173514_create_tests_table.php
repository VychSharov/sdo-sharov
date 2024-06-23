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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_name');
            $table->text('description')->nullable();
            $table->integer('duration')->nullable(); // Продолжительность теста в минутах, например
            $table->integer('grade_3')->nullable();
            $table->integer('grade_4')->nullable();
            $table->integer('grade_5')->nullable();
            $table->integer('attempts')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps(); // Временные метки created_at и updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
