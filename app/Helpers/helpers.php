<?php

function tglID(?string $datetime): string {
    if (!$datetime) return ' ';
    $waktu = strtotime($datetime);
    $idMo = [
        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
        'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
    ];
    $tgl = date('d', $waktu);
    $mo  = $idMo[date('F', $waktu)];
    return "$tgl $mo";
}

function timeID(?string $datetime): string {
    if (!$datetime) return ' ';
    $waktu = strtotime($datetime);
    $jam  = date('H:i', $waktu);
    return "$jam";
}

function BarangList(?string $barangList): array {
    if (!$barangList) return [];
    $items = explode(', ', $barangList);
    return array_map(function ($item) {
        if (str_starts_with($item, 'P-')) {
            return 'Proyektor ' . substr($item, 2);
        } elseif (str_starts_with($item, 'HDMI')) {
            return 'Kabel ' . $item;
        }
        return $item;
    }, $items);
}
