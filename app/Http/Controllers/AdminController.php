<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Pemindahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboardBRW(Request $request) {
        $user = User::with(['admin'])->find(session('user_id'));
        $role = session('role');
        $lokasi = $user ->admin->region_adm;

        //MHS + COUNT
            $mhs_main = Peminjaman::from('peminjaman as p')
                ->join('user as u', 'p.id_user', '=', 'u.id_user')
                ->where('p.brw_region', $lokasi)
                ->where('u.us_role', 'mhs');

            $mhs_pending  = (clone $mhs_main)->where('p.brw_status', 'QUEUED')->count();
            $mhs_aktif    = (clone $mhs_main)->where('p.brw_status', 'ACTIVE')->count();
            $mhs_selesai  = (clone $mhs_main)->where('p.brw_status', 'DISCHARGED')->count();
            $mhs_total    = (clone $mhs_main)->count();

            $brw_mhs = Peminjaman::from('peminjaman as p')
                ->leftJoin('user as u', 'p.id_user', '=', 'u.id_user')
                ->leftJoin('mahasiswa as m', 'p.id_user', '=', 'm.id_user')
                ->leftJoin('detail_brg as db', 'p.brw_id', '=', 'db.brw_id')
                ->leftJoin('barang as b', 'db.brg_id', '=', 'b.brg_id')
                ->where('u.us_role', 'mhs')
                ->where('p.brw_region', $lokasi)
                ->when($request->filled('search_mhs'), function ($query) use ($request) {
                    $query->where('p.brw_otp', 'like', '%' . $request->search_mhs . '%');
                })
                ->select(
                    'p.brw_id', 'u.us_role', 'm.npm', 'm.nama_mhs', 'p.brw_mkul',
                    'p.brw_pengajar', 'p.brw_kelas', 'p.brw_room', 'p.brw_jamkul',
                    'p.no_hp', 'p.brw_region', 'p.brw_jaminan', 'p.brw_status',
                    'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update',
                    DB::raw("GROUP_CONCAT(IF(b.brg_name LIKE '%P-%', 
                    CONCAT(b.brg_name, ' (', b.brg_kode, ')'), b.brg_name) 
                    ORDER BY b.brg_cat SEPARATOR ', ') as barang_list")
                )
                ->groupBy(
                    'p.brw_id', 'u.us_role', 'm.npm', 'm.nama_mhs', 'p.brw_mkul',
                    'p.brw_pengajar', 'p.brw_kelas', 'p.brw_room', 'p.brw_jamkul',
                    'p.no_hp', 'p.brw_region', 'p.brw_jaminan', 'p.brw_status',
                    'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update'
                )
                ->orderByDesc('p.brw_req_in')
                ->orderBy('p.brw_status', 'asc')
                ->orderByDesc('p.brw_req_update')
                ->orderByRaw("(p.brw_otp = '------') ASC")
                ->paginate(10, ['*'], 'page_BRW-MHS')
                ->withQueryString();

        //DOSEN + COUNT
            $dsn_main = Peminjaman::from('peminjaman as p')
                ->join('user as u', 'p.id_user', '=', 'u.id_user')
                ->where('p.brw_region', $lokasi)
                ->where('u.us_role', 'dsn');

            $dsn_pending  = (clone $dsn_main)->where('p.brw_status', 'QUEUED')->count();
            $dsn_aktif    = (clone $dsn_main)->where('p.brw_status', 'ACTIVE')->count();
            $dsn_selesai  = (clone $dsn_main)->where('p.brw_status', 'DISCHARGED')->count();
            $dsn_total    = (clone $dsn_main)->count();

            $brw_dsn = Peminjaman::from('peminjaman as p')
                ->leftJoin('user as u', 'p.id_user', '=', 'u.id_user')
                ->leftJoin('dosen as d', 'p.id_user', '=', 'd.id_user')
                ->leftJoin('detail_brg as db', 'p.brw_id', '=', 'db.brw_id')
                ->leftJoin('barang as b', 'db.brg_id', '=', 'b.brg_id')
                ->where('u.us_role', 'dsn')
                ->where('p.brw_region', $lokasi)
                ->when($request->filled('search_dsn'), function ($query) use ($request) {
                    $query->where('p.brw_otp', 'like', '%' . $request->search_dsn . '%');
                })
                ->select('p.brw_id', 'u.us_role', 'd.kode_dsn', 'd.nama_dsn',
                    'p.brw_mkul', 'p.brw_room', 'p.brw_jamkul', 'p.brw_region',
                    'p.brw_status', 'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update',
                    DB::raw("GROUP_CONCAT(IF(b.brg_name LIKE '%P-%', 
                    CONCAT(b.brg_name, ' (', b.brg_kode, ')'), b.brg_name) 
                    ORDER BY b.brg_cat SEPARATOR ', ') as barang_list")
                )
                ->groupBy('p.brw_id', 'u.us_role', 'd.kode_dsn', 'd.nama_dsn',
                    'p.brw_mkul', 'p.brw_room', 'p.brw_jamkul', 'p.brw_region',
                    'p.brw_status', 'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update'
                )
                ->orderByDesc('p.brw_req_in')
                ->orderBy('p.brw_status', 'asc')
                ->orderByDesc('p.brw_req_update')
                ->orderByRaw("(p.brw_otp = '------') ASC")
                ->paginate(10, ['*'], 'page_BRW-DSN')
                ->withQueryString();

        return view('admin.peminjaman',
        compact('user', 'role', 'lokasi', 'mhs_pending', 'dsn_pending', 'mhs_aktif', 'dsn_aktif',
        'mhs_selesai', 'dsn_selesai', 'mhs_total', 'dsn_total', 'brw_mhs', 'brw_dsn')
        );
    }

    public function brw_acc(int $id) {
        $brw = Peminjaman::find($id);
        if (!$brw) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
        if ($brw->brw_status !== 'QUEUED') {
            return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 400);
        }
        $brw->update(['brw_status' => 'ACTIVE']);
        return response()->json(['success' => true]);
    }

    public function brg_return(int $id) {
        $brw = Peminjaman::find($id);
        if (!$brw) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
        if ($brw->brw_status !== 'ACTIVE') {
            return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 400);
        }
        $brg_ids = DB::table('detail_brg')
            ->where('brw_id', $id)
            ->pluck('brg_id');

        Barang::whereIn('brg_id', $brg_ids)
            ->update(['brg_status' => 'ready']);

        $brw->update([
            'brw_otp'    => '------',
            'brw_status' => 'DISCHARGED',
        ]);
        return response()->json(['success' => true]);
    }

    public function destroy_BRW(int $id) {
        $peminjaman = Peminjaman::findOrFail($id);
        if ($peminjaman->brw_status !== 'DISCHARGED' || $peminjaman->brw_otp !== '------') {
            return back()->with('error', 'Data belum selesai, tidak dapat dihapus!');
        }
        
        DB::table('detail_brg')->where('brw_id', $peminjaman->brw_id)->delete();
        $peminjaman->delete();
        return back()->with('success', 'Data peminjaman berhasil dihapus.');
    }
    
    public function dashboardMV(Request $request) {
        $user = User::with(['admin'])->find(session('user_id'));
        $role = session('role');

        $lokasi = ['D', 'E', 'G', 'F8'];
        $now = now('Asia/Jakarta');
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $today = $hari[$now->dayOfWeek];
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
        
        //COUNT STATUS
            $mv_main     = Pemindahan::from('pemindahan');
            $mv_pending  = (clone $mv_main)->where('mv_status', 'QUEUED')->count();
            $mv_aktif    = (clone $mv_main)->where('mv_status', 'CONFIRMED')->count();
            $mv_selesai  = (clone $mv_main)->where('mv_status', 'DISCHARGED')->count();
            $mv_total    = (clone $mv_main)->count();
        
        //COUNT ROOM EMPTY
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

        $mv_dsn = Pemindahan::from('pemindahan as p')
            ->leftJoin('user as u', 'p.id_user', '=', 'u.id_user')
            ->leftJoin('dosen as d', 'p.id_user', '=', 'd.id_user')
            ->leftJoin('rooms as ra', 'p.room_id', '=', 'ra.room_id')
            ->leftJoin('rooms as rg', 'p.room_replace', '=', 'rg.room_id')
            ->when($request->filled('search_MV'), function ($query) use ($request) {
                $query->where('d.nama_dsn', 'like', '%' . $request->search_MV . '%');
            })
            ->select(
                'p.mv_id', 'd.kode_dsn', 'd.nama_dsn', 'p.mv_mkul', 'p.mv_reason',
                'p.mv_kelas', 'p.mv_tgl_mkul', 'p.mv_jamkul', 'ra.room_name as room_asal',
                'rg.room_name as room_ganti', 'p.mv_req_in', 'p.mv_req_update', 'p.mv_status',
                'rg.room_region as room_ganti_region'
            )
            ->orderByDesc('p.mv_req_in')
            ->orderBy('p.mv_status', 'asc')
            ->orderByDesc('p.mv_req_update')
            ->paginate(10)
            ->withQueryString();
        return view('admin.pemindahan',
        compact('user', 'role', 'lokasi', 'mv_pending', 'mv_aktif', 'mv_selesai',
        'mv_total', 'mv_dsn', 'room_data', 'room_emp', 'listJam', 'currTime', 'jamNow')
        );
    }

    public function mv_update(Request $request) {
        $id   = $request->input('id_p');
        $aksi = $request->input('aksi');

        $user = User::with(['admin'])->find(session('user_id'));
        $lokasi = $user->admin->region_adm;

        $row = DB::table('pemindahan as p')
            ->select('p.*', 'd.nama_dsn', 'rg.room_region as room_ganti_region',
                DB::raw("CASE
                WHEN DAYNAME(p.mv_tgl_mkul) = 'Monday' THEN 'Senin'
                WHEN DAYNAME(p.mv_tgl_mkul) = 'Tuesday' THEN 'Selasa'
                WHEN DAYNAME(p.mv_tgl_mkul) = 'Wednesday' THEN 'Rabu'
                WHEN DAYNAME(p.mv_tgl_mkul) = 'Thursday' THEN 'Kamis'
                WHEN DAYNAME(p.mv_tgl_mkul) = 'Friday' THEN 'Jumat'
                WHEN DAYNAME(p.mv_tgl_mkul) = 'Saturday' THEN 'Sabtu'
                ELSE 'Minggu' END AS hari")
            )
            ->leftJoin('dosen as d', 'p.id_user', '=', 'd.id_user')
            ->leftJoin('rooms as rg', 'p.room_replace', '=', 'rg.room_id')
            ->where('p.mv_id', $id)
            ->first();

        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        if (in_array($aksi, ['CONFIRMED', 'VOIDED']) && $row->room_ganti_region !== $lokasi) {
            return response()->json(['success' => false, 'message' => 'Anda tidak berwenang memproses pengajuan ini.'], 403);
        }

        $sch_dosen  = $row->nama_dsn;
        $sch_mkul   = $row->mv_mkul;
        $sch_kelas  = $row->mv_kelas;
        $mv_status  = $row->mv_status;
        $hari       = $row->hari;
        $jamGanti   = explode('/', $row->mv_jamkul);
        $room_id    = $row->room_id;
        $room_replace = $row->room_replace;

        //Pending → Reject
            if ($mv_status === 'QUEUED' && $aksi === 'VOIDED') {
                DB::table('pemindahan')->where('mv_id', $id)->update(['mv_status' => 'VOIDED']);
            }

        //Pending → Approve
            elseif ($mv_status === 'QUEUED' && $aksi === 'CONFIRMED') {
                DB::table('pemindahan')->where('mv_id', $id)->update(['mv_status' => 'CONFIRMED']);

                foreach ($jamGanti as $jam) {
                    DB::table('schedules')
                        ->where('room_id', $room_id)
                        ->where('sch_day', $hari)
                        ->where('sch_jamkul', $jam)
                        ->update([
                            'room_status' => 'READY',
                            'sch_dosen'   => null,
                            'sch_mkul'    => null,
                            'sch_kelas'   => null,
                        ]);

                    DB::table('schedules')
                        ->where('room_id', $room_replace)
                        ->where('sch_day', $hari)
                        ->where('sch_jamkul', $jam)
                        ->update([
                            'room_status' => 'USED',
                            'sch_dosen'   => $sch_dosen,
                            'sch_mkul'    => $sch_mkul,
                            'sch_kelas'   => $sch_kelas,
                        ]
                    );
                }
            }

        //Approved → Finished
            elseif ($mv_status === 'CONFIRMED' && $aksi === 'DISCHARGED') {
                DB::table('pemindahan')->where('mv_id', $id)->update(['mv_status' => 'DISCHARGED']);

                foreach ($jamGanti as $jam) {
                    DB::table('schedules')
                        ->where('room_id', $room_id)
                        ->where('sch_day', $hari)
                        ->where('sch_jamkul', $jam)
                        ->update([
                            'room_status' => 'USED',
                            'sch_dosen'   => $sch_dosen,
                            'sch_mkul'    => $sch_mkul,
                            'sch_kelas'   => $sch_kelas,
                        ]);

                    DB::table('schedules')
                        ->where('room_id', $room_replace)
                        ->where('sch_day', $hari)
                        ->where('sch_jamkul', $jam)
                        ->update([
                            'room_status' => 'READY',
                            'sch_dosen'   => null,
                            'sch_mkul'    => null,
                            'sch_kelas'   => null,
                        ]
                    );
                }
            }
        return response()->json(['success' => true]);
    }

    public function destroy_MV(int $id) {
        $pemindahan = Pemindahan::findOrFail($id);
        if (!in_array($pemindahan->mv_status, ['DISCHARGED', 'VOIDED'])) {
            return back()->with('error', 'Data belum selesai, tidak dapat dihapus!');
        }

        $pemindahan->delete();
        return back()->with('success', 'Data pemindahan berhasil dihapus.');
    }
}
