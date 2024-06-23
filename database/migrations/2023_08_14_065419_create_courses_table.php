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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('overview');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            // $table->text('instructor');
            $table->text('thumbnail');
            // $table->text('rating');
            // $table->text('orig_price');
            // $table->text('current_price');
            // $table->text('number_of_purchase')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
