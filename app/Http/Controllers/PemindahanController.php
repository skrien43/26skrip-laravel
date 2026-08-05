<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pemindahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemindahanController extends Controller
{
    public function showForm() {
        $role = session('role');
        $user = User::with('dosen')->find(session('user_id'));
        return view('form.pemindahan', compact('user', 'role'));
    }

    //GET jadwal
    public function getJadwalDosen(Request $request) {
        $namaDosen = $request->query('nama', '');
        if (!$namaDosen) return response()->json([]);
        $data = DB::select("SELECT * FROM (SELECT
            j.room_id, j.sch_day, j.sch_mkul, j.sch_kelas,
            r.room_name,
            GROUP_CONCAT(j.sch_jamkul ORDER BY CAST(j.sch_jamkul AS UNSIGNED) 
            SEPARATOR '/') AS jam_grup,
            DATE_ADD(CURDATE(), INTERVAL (
                CASE
                    WHEN j.sch_day = 'Senin'   THEN 2
                    WHEN j.sch_day = 'Selasa'  THEN 3
                    WHEN j.sch_day = 'Rabu'    THEN 4
                    WHEN j.sch_day = 'Kamis'   THEN 5
                    WHEN j.sch_day = 'Jumat'   THEN 6
                    WHEN j.sch_day = 'Sabtu'   THEN 7
                END - DAYOFWEEK(CURDATE())) DAY) AS tanggal
            FROM schedules j
            LEFT JOIN rooms r ON j.room_id = r.room_id
            WHERE j.sch_dosen = ?
            GROUP BY
                j.sch_dosen, j.sch_day, r.room_name,
                j.sch_kelas, j.sch_mkul, j.room_id, tanggal
            ORDER BY j.sch_day, jam_grup
        ) grpall", [$namaDosen]);
        return response()->json($data);
    }

    //GET ruangan
    public function getRuanganKosong(Request $request) {
        $hari   = $request->query('hari', '');
        $jam    = $request->query('jam', '');
        $region = $request->query('region', '');
        if (!$hari || !$jam) return response()->json([]);
        $data = DB::select("SELECT * FROM (SELECT
                r.room_id, r.room_name, r.room_region,
                r.room_gd, r.room_floor, r.room_ord,
                GROUP_CONCAT(j.sch_jamkul ORDER BY CAST(j.sch_jamkul AS UNSIGNED) 
                SEPARATOR '/') AS jam_grup
            FROM schedules j
            LEFT JOIN rooms r ON j.room_id = r.room_id
            WHERE j.room_status = 'READY'
            AND j.sch_day = ?
            AND r.room_region = ?
            GROUP BY
                r.room_id, r.room_name, r.room_region,
                r.room_gd, r.room_floor, r.room_ord,
                j.sch_jamkul - (
                    SELECT COUNT(*) FROM schedules j2
                    WHERE j2.room_id = j.room_id
                    AND j2.sch_day = j.sch_day
                    AND j2.sch_jamkul <= j.sch_jamkul
                    AND j2.room_status = 'READY')) grp
        WHERE jam_grup LIKE ?", [$hari, $region, "%{$jam}%"]);
        return response()->json($data);
    }

    public function store(Request $request) {
        $now = now('Asia/Jakarta');
        $hour = $now->hour;
        $day = $now->dayOfWeek;
        if ($day === 0 || $hour < 6 || $hour >= 17) {
            return redirect('/dashboard/pemindahan/form-pemindahan')
                ->with('error', 'Maaf, pemindahan ruangan hanya dapat dilakukan pada saat JAM dan HARI PERKULIAHAN!');
        }

        $request->validate([
            'room_id'      => 'required|integer|exists:rooms,room_id',
            'room_replace' => 'required|integer|exists:rooms,room_id',
            'mv_mkul'      => 'required|string|max:150',
            'mv_kelas'     => 'required|string|size:5',
            'mv_jamkul'    => ['required', 'string', 'max:8', 'regex:/^[0-9\/]+$/'],
            'mv_tgl_mkul'  => 'required|date',
            'mv_reason'    => 'required|string|max:1000'
        ], [
            'mv_kelas.size'       => 'Format kelas salah!',
            'mv_jamkul.regex'     => 'Format jam tidak valid!',
            'room_replace.exists' => 'Ruangan pengganti tidak ditemukan!',
        ]);

        $MV = Pemindahan::create([
            'id_user'      => session('user_id'),
            'room_id'      => $request->room_id,
            'room_replace' => $request->room_replace,
            'mv_mkul'      => trim($request->mv_mkul),
            'mv_kelas'     => strtoupper(trim($request->mv_kelas)),
            'mv_jamkul'    => trim($request->mv_jamkul),
            'mv_tgl_mkul'  => $request->mv_tgl_mkul,
            'mv_reason'    => trim($request->mv_reason),
            'mv_status'    => 'QUEUED',
        ]);
        session(['last_mv_success' => $MV->mv_id]);
        return redirect()->route('dashboard.pemindahan');
    }

    public function getHistory() {
        $user = User::with(['dosen'])->find(session('user_id'));
        $role = session('role');

        $mv_active = Pemindahan::from('pemindahan as p')
            ->leftJoin('rooms as ra', 'p.room_id', '=', 'ra.room_id')
            ->leftJoin('rooms as rg', 'p.room_replace', '=', 'rg.room_id')
            ->select('p.*', 'ra.room_name as room_asal', 'rg.room_name as room_ganti')
            ->where('p.id_user', session('user_id'))
            ->whereIn('p.mv_status', ['QUEUED', 'CONFIRMED'])
            ->orderByDesc('p.mv_req_in')
            ->first();
        $showSuccessMV = session()->pull('last_mv_success', false);

        $historyMV = DB::table('pemindahan as p')
            ->select(
                'u.username', 'p.mv_mkul', 'p.mv_kelas',
                'p.mv_tgl_mkul', 'p.mv_jamkul', 'ra.room_name as room_id',
                'rg.room_name as room_replace', 'p.mv_status', 'p.mv_req_in',
                'p.mv_req_update', 'p.mv_tgl_mkul')
            ->leftJoin('user as u', 'p.id_user', '=', 'u.id_user')
            ->leftJoin('rooms as ra', 'p.room_id', '=', 'ra.room_id')
            ->leftJoin('rooms as rg', 'p.room_replace', '=', 'rg.room_id')
            ->where('p.id_user', session('user_id'))
            ->whereIn('p.mv_status', ['DISCHARGED', 'VOIDED'])
            ->orderByDesc('p.mv_req_in')
            ->orderBy('p.mv_status', 'asc')
            ->orderByDesc('p.mv_req_update')
            ->paginate(8)
            ->withQueryString();
        return view('dashboard.pemindahan', compact('historyMV', 'user', 'role', 'mv_active', 'showSuccessMV'));
    }
}
