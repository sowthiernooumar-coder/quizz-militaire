<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
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

            'title' => $this->title,

            'difficulty_level' => $this->difficulty_level,

            'subject' => $this->subject?->name,

            'creator' =>
                $this->creator?->first_name
                .' '.
                $this->creator?->last_name,

            'questions_count' =>
                $this->questions()->count(),

            'created_at' =>
                $this->created_at
        ];
    }
}
