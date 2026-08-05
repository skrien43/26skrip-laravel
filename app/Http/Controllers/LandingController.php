<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index() {
        $lokasi = ['D', 'E', 'G', 'F8'];
        $now = now('Asia/Jakarta');
        // dd(\Carbon\CarbonTimeZone::listIdentifiers());
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $today = $hari[$now->dayOfWeek];
        $listJam = ['06:30 - 07:30', '07:30 - 08:30', '08:30 - 09:30', '09:30 - 10:30', 
                    '10:30 - 11:30', '11:30 - 12:30', '12:30 - 13:30', '13:30 - 14:30', 
                    '14:30 - 15:30', '15:30 - 16:30', '16:30 - 17:30'];
        $currTime = $now->format('H:i');
        $jamNow = null;
        $dateNOW = \Carbon\Carbon::now('Asia/Jakarta')
            ->locale('id')
            ->translatedFormat('l, d F Y');

        foreach ($listJam as $index => $range) {
            if ($range === '') continue;
            [$start, $end] = explode(' - ', $range);
            if ($currTime >= $start && $currTime < $end) {
                $jamNow = $index;
                break;
            }
        }

        $room_data = DB::select("SELECT r.room_region,
                    COUNT(DISTINCT CASE WHEN s.room_status = 'READY' THEN s.room_id END) as room_kosong,
                    COUNT(DISTINCT CASE WHEN s.room_status = 'USED' AND s.sch_jamkul = ? THEN s.room_id END) as room_terpakai,
                    COUNT(DISTINCT r.room_id) as room_total
                FROM rooms r LEFT JOIN schedules s
                ON r.room_id = s.room_id AND s.sch_day = ?
                GROUP BY r.room_region ORDER BY r.room_region", [$jamNow, $today]);

        $room_emp = [];
        foreach ($room_data as $row) {
            $room_emp[$row->room_region] = [
                'kosong' => $jamNow ? ($row->room_total - $row->room_terpakai) : (int) $row->room_total,
                'total'  => (int) $row->room_total
            ];
        }
        $room_sorted = [];
        foreach ($lokasi as $loc) {
            $room_sorted[$loc] = $room_emp[$loc] ?? ['kosong' => 0, 'total' => 0];
        }
        $room_emp = $room_sorted;

        return view('landing.index', 
        compact('today', 'currTime', 'jamNow', 'dateNOW', 'lokasi', 'listJam', 'room_data', 'room_emp')
        );
    }
}
