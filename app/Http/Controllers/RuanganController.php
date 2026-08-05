<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jadwal;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuanganController extends Controller
{
    public function room_index(Request $request) {
        $user = User::with(['admin'])->find(session('user_id'));
        $role = session('role');
        $lokasi = $user->admin->region_adm;

        $now = now('Asia/Jakarta');
        $hariLookup = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $today = $hariLookup[$now->dayOfWeek];
        $listJam = ['06:30 - 07:30', '07:30 - 08:30', '08:30 - 09:30', '09:30 - 10:30',
                    '10:30 - 11:30', '11:30 - 12:30', '12:30 - 13:30', '13:30 - 14:30',
                    '14:30 - 15:30', '15:30 - 16:30', '16:30 - 17:30'];
        $currTime = $now->format('H:i');
        $jamNow = null;

        foreach ($listJam as $index => $range) {
            if ($range === '') continue;
            [$start, $end] = explode(' - ', $range);
            if ($currTime >= $start && $currTime < $end) {
                $jamNow = $index;
                break;
            }
        }

        $rooms = Ruangan::from('rooms as r')
            ->leftJoin('schedules as s', function ($join) use ($today, $jamNow) {
                $join->on('s.room_id', '=', 'r.room_id')
                    ->where('s.sch_day', $today)
                    ->where('s.sch_jamkul', $jamNow);
            })
            ->leftJoin('rooms_facility as rf', 'rf.room_id', '=', 'r.room_id')
            ->select('r.room_id', 'r.room_name', 'r.room_region', 'r.room_gd',
                'r.room_floor', 'r.room_ord', 's.room_status',
                'rf.pac', 'rf.vwr')
            ->where('r.room_region', $lokasi)
            ->when($request->filled('search_room'), function ($query) use ($request) {
                $query->where('r.room_name', 'like', '%' . $request->search_room . '%');
            })
            ->paginate(10)
            ->withQueryString();

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
                'terisi' => $jamNow ? (int) $row->room_terpakai : 0,
                'total'  => (int) $row->room_total
            ];
        }

        $room_emp = [
            $lokasi => $room_emp[$lokasi] ?? ['kosong' => 0, 'terisi' => 0, 'total' => 0]
        ];
        return view('admin.ruangan',
        compact('user', 'role',  'lokasi', 'today', 'hari',
            'listJam', 'currTime', 'jamNow', 'rooms', 'room_emp')
        );
    }
    
    public function getRoomSchedule(Request $request) {
        $roomName = trim($request->query('room'));
        $ruangan = Ruangan::where('room_name', $roomName)->first();
        
        if (!$ruangan) {
            return response()->json([]); }
        $schedule = Jadwal::where('room_id', $ruangan->room_id)
            ->select('sch_day', 'sch_jamkul', 'room_status', 'sch_dosen', 'sch_mkul', 'sch_kelas')
            ->get();
        return response()->json($schedule);
    }

    public function updateJadwal(Request $request) {
        $listJam = ['06:30 - 07:30', '07:30 - 08:30', '08:30 - 09:30', '09:30 - 10:30',
                    '10:30 - 11:30', '11:30 - 12:30', '12:30 - 13:30', '13:30 - 14:30',
                    '14:30 - 15:30', '15:30 - 16:30', '16:30 - 17:30'];

        $namaRuang = trim($request->input('nama_ruang'));
        if ($namaRuang === '') {
            return back()->with('error', 'Harap pilih ruangan terlebih dahulu!');
        }
        $hariJadwal = trim($request->input('hari_jadwal'));
        if ($hariJadwal === '') {
            return back()->with('error', 'Harap pilih jadwal terlebih dahulu!');
        }

        $jamJadwal = array_search(trim($request->input('jam_jadwal')), $listJam);
        $statusJadwal = trim($request->input('isFilled_jadwal'));
        $dosenJadwal = ucwords(strtolower(trim($request->input('dosen_jadwal'))));
        $matkulJadwal = ucwords(strtolower(trim($request->input('matkul_jadwal'))));
        $kelasJadwal = strtoupper(trim($request->input('kelas_jadwal')));

        $ruangan = Ruangan::where('room_name', $namaRuang)->first();
        if (!$ruangan) {
            return back()->with('error', 'Ruangan tidak ditemukan!');
        }

        if ($statusJadwal === 'READY') {
            if ($dosenJadwal !== '' || $matkulJadwal !== '' || $kelasJadwal !== '') {
                return back()->with('error', 'Harap sesuaikan field Status Ruangan dengan isian di atas!');
            }

            Jadwal::where('room_id', $ruangan->room_id)
                ->where('sch_day', $hariJadwal)
                ->where('sch_jamkul', $jamJadwal)
                ->update([
                    'room_status' => 'READY',
                    'sch_dosen'   => null,
                    'sch_mkul'    => null,
                    'sch_kelas'   => null,
                ]);
        } elseif ($statusJadwal === 'USED') {
            if ($dosenJadwal === '' || $matkulJadwal === '' || $kelasJadwal === '') {
                return back()->with('error', 'Harap sesuaikan field Status Ruangan dengan isian di atas!');
            }

            Jadwal::where('room_id', $ruangan->room_id)
                ->where('sch_day', $hariJadwal)
                ->where('sch_jamkul', $jamJadwal)
                ->update([
                    'room_status' => 'USED',
                    'sch_dosen'   => $dosenJadwal,
                    'sch_mkul'    => $matkulJadwal,
                    'sch_kelas'   => $kelasJadwal,
                ]);
        } else {
            return back()->with('error', 'Status jadwal tidak valid!');
        }
        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }
}
