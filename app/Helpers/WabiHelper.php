<?php
namespace App\Helpers;

class WabiHelper
{
    public static function formatDate($createdAt)
    {
        $bulanIndo = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $tanggal = date('j', strtotime($createdAt)); // Tanggal (1-31)
        $bulan = $bulanIndo[date('n', strtotime($createdAt)) - 1]; // Nama bulan dalam bahasa Indonesia
        $tahun = date('Y', strtotime($createdAt)); // Tahun
        $waktu = date('H:i', strtotime($createdAt)); // Jam:Menit
        return "{$tanggal} {$bulan} {$tahun} | {$waktu}";
    }
}
