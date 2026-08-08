<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'quiz_sessions',

            function (Blueprint $table)
            {
                $table->id();

                $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('quiz_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->integer('total_questions');

                $table->integer('correct_answers')
                    ->default(0);

                $table->decimal(
                    'score_percentage',
                    5,
                    2
                )->default(0);

                $table->timestamp(
                    'started_at'
                );

                $table->timestamp(
                    'finished_at'
                )->nullable();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'quiz_sessions'
        );
    }
};