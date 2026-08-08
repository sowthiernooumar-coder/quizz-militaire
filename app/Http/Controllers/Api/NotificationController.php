<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class NotificationController
extends Controller
{
    //Afficher les notifications de l'utilisateur
    public function index()
    {
        return auth()

            ->user()

            ->notifications

            ->sortByDesc(
                'created_at'
            )

            ->values();
    }

    //Marqué une notification comme lue
    public function markAsRead(
    string $id)
    {
        $notification =

            auth()
            ->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json([

            'message' =>
                'Notification lue'
        ]);
    }

    //Supprimer une notification
    public function destroy(
    string $id)
    {
        auth()

        ->user()

        ->notifications()

        ->findOrFail($id)

        ->delete();

        return response()->json([

            'message' =>
                'Notification supprimée'
        ]);
    }

    //Compter les notifications non lues
    public function unreadCount()
    {
        return response()->json([

            'count' =>

                auth()

                ->user()

                ->unreadNotifications()

                ->count()
        ]);
    }
}