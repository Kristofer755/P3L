<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenitipan;
use Illuminate\Http\Request;

class TransaksiPenitipanController extends Controller
{
    // Lihat semua transaksi penitipan
    public function index()
    {
        // dengan relasi penitip dan detail
        $transaksis = TransaksiPenitipan::with(['penitip', 'detail.barang'])->get();
        return response()->json($transaksis);
    }

    // Lihat detail 1 transaksi penitipan
    public function show($id)
    {
        $transaksi = TransaksiPenitipan::with(['penitip', 'detail.barang'])
            ->findOrFail($id);
        return response()->json($transaksi);
    }

    public function updateStatusDisiapkan($id)
    {
        $transaksi = TransaksiPenitipan::with(['penitip', 'detail.barang'])
            ->findOrFail($id);
        
        // Update status
        $transaksi->status_penitipan = 'Disiapkan';
        $transaksi->save();

        // Kirim notifikasi ke penitip (FCM)
        $fcm_token = $transaksi->penitip->fcm_token ?? null;
        $nama_barang = $transaksi->detail->barang->nama_barang ?? '-';
        if ($fcm_token) {
            $title = "Barang laku terjual";
            $body  = "Barang '$nama_barang' sudah laku dan sedang disiapkan!";
            $this->sendFcmToPenitip($fcm_token, $title, $body);
        }

        return response()->json(['success' => true, 'message' => 'Status updated & notif sent']);
    }

    // Helper FCM
    private function sendFcmToPenitip($fcmToken, $title, $body)
    {
        $SERVER_API_KEY = 'ISI_DENGAN_SERVER_KEY_FIREBASE_MU'; // <- Ganti dengan Server Key FCM-mu
        $data = [
            "to" => $fcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "default"
            ],
            // Optional: bisa tambahkan data payload jika mau
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        curl_close($ch);

        // Optional: logging response
        // \Log::info('FCM Response: ' . $response);

        return $response;
    }
}
