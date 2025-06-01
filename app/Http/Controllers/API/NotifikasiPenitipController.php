<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotifikasiPenitip;

class NotifikasiPenitipController extends Controller
{
    public function getPenitipNotif($id_penitip)
    {
        $notif = NotifikasiPenitip::where('id_penitip', $id_penitip)
                    ->where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->first();

        return response()->json($notif);
    }

    public function markAsRead($id)
    {
        $notif = NotifikasiPenitip::find($id);
        if ($notif) {
            $notif->is_read = true;
            $notif->save();
        }

        return response()->json(['message' => 'Marked as read']);
    }
}

