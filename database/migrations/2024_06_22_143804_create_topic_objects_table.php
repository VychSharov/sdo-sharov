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
        Schema::create('topic_objects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->onDelete('cascade');
            $table->string('type'); // 'text', 'file'
            $table->text('content')->nullable(); // Хранение контента объекта
            $table->text('file_path')->nullable(); // Путь к файлу, если тип объекта 'file'
            $table->foreignId('test_id')->nullable()->constrained('tests')->onDelete('cascade'); // Внешний ключ для тестов
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_objects');
    }
};
