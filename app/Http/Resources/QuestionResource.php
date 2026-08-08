<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [

            'id' => $this->id,

            'quiz_id' => $this->quiz_id,

            'question' => $this->question,

            'option_a' => $this->option_a,

            'option_b' => $this->option_b,

            'option_c' => $this->option_c,

            'option_d' => $this->option_d,

            'correct_answer' =>
                $this->correct_answer,

            'explanation' =>
                $this->explanation
        ];
    }
}
