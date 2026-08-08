<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewQuizNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $quizTitle
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
                'Nouveau Quiz',

            'message' =>
                'Le quiz '.$this->quizTitle.
                ' est maintenant disponible.'
        ];
    }
}