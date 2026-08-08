<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'question_answers',

            function (Blueprint $table)
            {
                $table->id();

                $table->foreignId(
                    'question_id'
                )
                ->constrained()
                ->cascadeOnDelete();

                $table->text('answer_text');

                $table->boolean(
                    'is_correct'
                )->default(false);

                $table->integer(
                    'display_order'
                )->default(1);

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'question_answers'
        );
    }
};