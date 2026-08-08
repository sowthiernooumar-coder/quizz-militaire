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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->text('description')
                ->nullable();

            $table->foreignId('subject_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('difficulty_level', 3);

            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index([
                'subject_id',
                'difficulty_level'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
