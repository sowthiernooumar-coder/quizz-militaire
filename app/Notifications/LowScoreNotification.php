<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class LowScoreNotification
extends Notification
{
    public function __construct(
        public float $score
    ) {}

    public function via(
        object $notifiable
    ): array
    {
        return ['database'];
    }

    public function toArray(
        object $notifiable
    ): array
    {
        return [

            'title' =>
                'Score Faible',

            'message' =>
                'Un stagiaire a obtenu '
                .$this->score.'%'
        ];
    }
}