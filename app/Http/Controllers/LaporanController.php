<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Pemindahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request) {
        $adminLogin = Admin::where('id_user', session('user_id'))->first();
        $lokasi = $adminLogin->region_adm;
        $kategori = $request->input('kategori');
        $tglMulai = \Carbon\Carbon::parse($request->input('tgl_mulai', now('Asia/Jakarta')->startofMonth()))->format('Y-m-d');
        $tglSelesai = \Carbon\Carbon::parse($request->input('tgl_selesai', now('Asia/Jakarta')))->format('Y-m-d');
        $filter = $request->has('tgl_mulai') && $request->has('tgl_selesai') && $request->has('kategori');
        if ($filter) {
            $FtglMulai = \Carbon\Carbon::parse($request->tgl_mulai)->format('Y-m-d');
            $FtglSelesai = \Carbon\Carbon::parse($request->tgl_selesai)->format('Y-m-d');
        } else {
            $FtglMulai = null;
            $FtglSelesai = null;
        }

        //2 cards
            $statPeminjaman = Peminjaman::where('brw_region', $lokasi)
                ->where('brw_otp', '------')
                ->whereBetween(DB::raw('DATE(brw_req_in)'), [$tglMulai, $tglSelesai])
                ->selectRaw("COUNT(*) as total,
                    SUM(CASE WHEN brw_status = 'DISCHARGED' THEN 1 ELSE 0 END) as selesai")
                ->first();

            $statPemindahan = Pemindahan::from('pemindahan as p')
                ->leftJoin('rooms as r', 'p.room_id', '=', 'r.room_id')
                ->where('r.room_region', $lokasi)
                ->whereBetween(DB::raw('DATE(p.mv_req_in)'), [$tglMulai, $tglSelesai])
                ->selectRaw("COUNT(*) as total,
                    SUM(CASE WHEN p.mv_status = 'DISCHARGED' THEN 1 ELSE 0 END) as selesai,
                    SUM(CASE WHEN p.mv_status = 'VOIDED' THEN 1 ELSE 0 END) as ditolak,
                    SUM(CASE WHEN p.mv_status = 'CONFIRMED' THEN 1 ELSE 0 END) as disetujui")
                ->first();

        //ratio
            $totalDiproses = ($statPemindahan->disetujui ?? 0) + ($statPemindahan->ditolak ?? 0) + ($statPemindahan->selesai ?? 0);
            $rasioApprove = $totalDiproses > 0
                ? round((($statPemindahan->disetujui + $statPemindahan->selesai) / $totalDiproses) * 100, 1)
                : 0;

        //top singko barang
            $topBarang = Barang::from('peminjaman as p')
                ->leftJoin('detail_brg as pb', 'p.brw_id', '=', 'pb.brw_id')
                ->leftJoin('barang as b', 'pb.brg_id', '=', 'b.brg_id')
                ->where('p.brw_region', $lokasi)
                ->whereBetween(DB::raw('DATE(p.brw_req_in)'), [$tglMulai, $tglSelesai])
                ->whereNotNull('b.brg_name')
                ->select('b.brg_name', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('b.brg_name')
                ->orderByDesc('jumlah')
                ->limit(5)
                ->get();

        //top singko rooms
            $topRuangan = Pemindahan::from('pemindahan as p')
                ->leftJoin('rooms as r', 'p.room_id', '=', 'r.room_id')
                ->where('r.room_region', $lokasi)
                ->whereBetween(DB::raw('DATE(p.mv_req_in)'), [$tglMulai, $tglSelesai])
                ->select('r.room_name', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('r.room_name')
                ->having('jumlah', '>=', 5)
                ->orderByDesc('jumlah')
                ->limit(5)
                ->get();

        //grafik-months
            $trenPeminjaman = Peminjaman::where('brw_region', $lokasi)
                ->where('brw_req_in', '>=', now()->subMonths(6)->startOfMonth())
                ->selectRaw("DATE_FORMAT(brw_req_in, '%Y-%m') as bulan, COUNT(*) as total")
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            $trenPemindahan = Pemindahan::from('pemindahan as p')
                ->leftJoin('rooms as r', 'p.room_id', '=', 'r.room_id')
                ->where('r.room_region', $lokasi)
                ->where('p.mv_req_in', '>=', now()->subMonths(6)->startOfMonth())
                ->selectRaw("DATE_FORMAT(p.mv_req_in, '%Y-%m') as bulan, COUNT(*) as total")
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            // 6 mos
                $bulanLabel = [];
                $dataPeminjamanChart = [];
                $dataPemindahanChart = [];
                for ($i = 5; $i >= 0; $i--) {
                    $bulan = now()->subMonths($i)->format('Y-m');
                    $bulanLabel[] = now()->subMonths($i)->translatedFormat('M Y');
                    $dataPeminjamanChart[] = (int) ($trenPeminjaman->firstWhere('bulan', $bulan)->total ?? 0);
                    $dataPemindahanChart[] = (int) ($trenPemindahan->firstWhere('bulan', $bulan)->total ?? 0);
                }

        //grafik-week
            $trenPeminjamanMinggu = Peminjaman::where('brw_region', $lokasi)
                ->where('brw_req_in', '>=', now('Asia/Jakarta')->subWeeks(8)->startOfWeek())
                ->selectRaw("YEARWEEK(brw_req_in, 3) as minggu, COUNT(*) as total")
                ->groupBy('minggu')
                ->orderBy('minggu')
                ->get();

            $trenPemindahanMinggu = Pemindahan::from('pemindahan as p')
                ->leftJoin('rooms as r', 'p.room_id', '=', 'r.room_id')
                ->where('r.room_region', $lokasi)
                ->where('p.mv_req_in', '>=', now('Asia/Jakarta')->subWeeks(8)->startOfWeek())
                ->selectRaw("YEARWEEK(p.mv_req_in, 3) as minggu, COUNT(*) as total")
                ->groupBy('minggu')
                ->orderBy('minggu')
                ->get();

            $mingguLabel = [];
            $dataPeminjamanChartMingguan = [];
            $dataPemindahanChartMingguan = [];
            for ($i = 7; $i >= 0; $i--) {
                $start = now('Asia/Jakarta')->subWeeks($i)->startOfWeek();
                $yw = (int) $start->format('oW');
                $mingguLabel[] = $start->translatedFormat('d M');
                $dataPeminjamanChartMingguan[] = (int) ($trenPeminjamanMinggu->firstWhere('minggu', $yw)->total ?? 0);
                $dataPemindahanChartMingguan[] = (int) ($trenPemindahanMinggu->firstWhere('minggu', $yw)->total ?? 0);
            }

        //table export
            $preview = null;
            if ($filter) {            
                if ($kategori === 'pemindahan') {
                    $preview = Pemindahan::from('pemindahan as p')
                        ->leftJoin('dosen as d', 'p.id_user', '=', 'd.id_user')
                        ->leftJoin('user as u', 'd.id_user', '=', 'u.id_user')
                        ->leftJoin('rooms as ra', 'p.room_id', '=', 'ra.room_id')
                        ->leftJoin('rooms as rg', 'p.room_replace', '=', 'rg.room_id')
                        ->select('u.username', 'd.nama_dsn', 'p.mv_mkul', 'p.mv_kelas',
                            'ra.room_name as room_asal', 'rg.room_name as room_ganti',
                            'p.mv_jamkul', 'p.mv_tgl_mkul', 'p.mv_status', 'p.mv_req_in', 'p.mv_req_update')
                        ->where('ra.room_region', $lokasi)
                        ->whereBetween(DB::raw('DATE(p.mv_req_in)'), [$FtglMulai, $FtglSelesai])
                        ->orderBy('p.mv_req_update', 'desc')
                        ->paginate(15)
                        ->withQueryString();
                } else {
                    $preview = Peminjaman::from('peminjaman as p')
                        ->leftJoin('user as u', 'p.id_user', '=', 'u.id_user')
                        ->leftJoin('mahasiswa as m', 'p.id_user', '=', 'm.id_user')
                        ->leftJoin('dosen as d', 'p.id_user', '=', 'd.id_user')
                        ->leftJoin('detail_brg as db', 'p.brw_id', '=', 'db.brw_id')
                        ->leftJoin('barang as b', 'db.brg_id', '=', 'b.brg_id')
                        ->select('u.username','m.nama_mhs', 'd.nama_dsn', 'db.brw_id', 'b.brg_id', 'p.brw_mkul', 'p.brw_room',
                            'p.brw_jamkul', 'p.brw_region', 'p.brw_status', 'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update',
                            DB::raw("GROUP_CONCAT(IF(b.brg_name LIKE '%P-%', 
                            CONCAT(b.brg_name, ' (', b.brg_kode, ')'), b.brg_name) 
                            ORDER BY b.brg_cat SEPARATOR ', ') as barang_list")
                        )
                        ->groupBy('p.brw_id', 'm.nama_mhs', 'd.nama_dsn', 'db.brw_id', 'b.brg_id', 'p.brw_mkul', 'p.brw_room',
                            'p.brw_jamkul', 'p.brw_region', 'p.brw_status', 'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update'
                        )
                        ->where('p.brw_region', $lokasi)
                        ->where('p.brw_otp', '------')
                        ->whereBetween(DB::raw('DATE(p.brw_req_in)'), [$FtglMulai, $FtglSelesai])
                        ->orderBy('p.brw_req_update', 'desc')
                        ->paginate(15)
                        ->withQueryString();
                }
            }

            $role = session('role');
            $user = User::with(['mahasiswa', 'dosen', 'admin'])->find(session('user_id'));
            return view('admin.reports', compact(
                'preview', 'statPeminjaman', 'statPemindahan', 'rasioApprove', 'dataPemindahanChart',
                'topBarang', 'topRuangan', 'bulanLabel', 'dataPeminjamanChart',
                'mingguLabel', 'dataPeminjamanChartMingguan', 'dataPemindahanChartMingguan',
                'tglMulai', 'FtglMulai', 'tglSelesai', 'FtglSelesai', 'kategori', 'lokasi', 'role', 'user', 'filter')
            );
    }

    public function exportPeminjaman(Request $request) {
        $adminLogin = Admin::where('id_user', session('user_id'))->first();
        $lokasi = $adminLogin->region_adm;
        $filter = $request->has('tgl_mulai') && $request->has('tgl_selesai');
        if ($filter) {
            $FtglMulai = \Carbon\Carbon::parse($request->tgl_mulai)->format('Y-m-d');
            $FtglSelesai = \Carbon\Carbon::parse($request->tgl_selesai)->format('Y-m-d');
        } else {
            $FtglMulai = null;
            $FtglSelesai = null;
        }

        $data = Peminjaman::from('peminjaman as p')
            ->leftJoin('user as u', 'p.id_user', '=', 'u.id_user')
            ->leftJoin('mahasiswa as m', 'p.id_user', '=', 'm.id_user')
            ->leftJoin('dosen as d', 'p.id_user', '=', 'd.id_user')
            ->leftJoin('detail_brg as db', 'p.brw_id', '=', 'db.brw_id')
            ->leftJoin('barang as b', 'db.brg_id', '=', 'b.brg_id')
            ->select('u.username','m.nama_mhs', 'd.nama_dsn', 'db.brw_id', 'b.brg_id', 'p.brw_mkul', 'p.brw_room',
                'p.brw_jamkul', 'p.brw_region', 'p.brw_status', 'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update',
                DB::raw("GROUP_CONCAT(IF(b.brg_name LIKE '%P-%', 
                CONCAT(b.brg_name, ' (', b.brg_kode, ')'), b.brg_name) 
                ORDER BY b.brg_cat SEPARATOR ', ') as barang_list")
            )
            ->groupBy('p.brw_id', 'm.nama_mhs', 'd.nama_dsn', 'db.brw_id', 'b.brg_id', 'p.brw_mkul', 'p.brw_room',
                'p.brw_jamkul', 'p.brw_region', 'p.brw_status', 'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update'
            )
            ->where('p.brw_region', $lokasi)
            ->where('p.brw_otp', '------')
            ->whereBetween(DB::raw('DATE(p.brw_req_in)'), [$FtglMulai, $FtglSelesai])
            ->orderByDesc('p.brw_req_update')
            ->get();
        $filename = "Lap. Peminjaman Perangkat Kmp-{$lokasi} ({$FtglMulai}_sd_{$FtglSelesai}).csv";

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No','NPM/Kode Dosen', 'Nama',  'Perangkat', 'Mata Kuliah', 'Ruangan', 'Jam Kuliah', 'Waktu Pengajuan', 'Waktu Kembali']);
            foreach ($data as $index => $row) {
                fputcsv($handle, [
                    $index + 1,
                    $row->username,
                    ucwords(strtolower($row->nama_mhs ?? $row->nama_dsn ?? '-')),
                    implode(', ', BarangList($row->barang_list)),
                    $row->brw_mkul,
                    $row->brw_room,
                    "'" . $row->brw_jamkul . "'",
                    tglID($row->brw_req_in) . ', ' . timeID($row->brw_req_in),
                    tglID($row->brw_req_update) . ', ' . timeID($row->brw_req_update)
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportPemindahan(Request $request) {
        $adminLogin = Admin::where('id_user', session('user_id'))->first();
        $lokasi = $adminLogin->region_adm;
        $filter = $request->has('tgl_mulai') && $request->has('tgl_selesai');
        if ($filter) {
            $FtglMulai = \Carbon\Carbon::parse($request->tgl_mulai)->format('Y-m-d');
            $FtglSelesai = \Carbon\Carbon::parse($request->tgl_selesai)->format('Y-m-d');
        } else {
            $FtglMulai = null;
            $FtglSelesai = null;
        }

        $data = Pemindahan::from('pemindahan as p')
            ->leftJoin('dosen as d', 'p.id_user', '=', 'd.id_user')
            ->leftJoin('user as u', 'd.id_user', '=', 'u.id_user')
            ->leftJoin('rooms as ra', 'p.room_id', '=', 'ra.room_id')
            ->leftJoin('rooms as rg', 'p.room_replace', '=', 'rg.room_id')
            ->select('u.username', 'd.nama_dsn', 'p.mv_mkul', 'p.mv_kelas',
                'ra.room_name as room_asal', 'rg.room_name as room_ganti', 'p.mv_jamkul',
                'p.mv_tgl_mkul', 'p.mv_status', 'p.mv_req_in', 'p.mv_req_update', 'p.mv_reason')
            ->where('ra.room_region', $lokasi)
            ->whereBetween(DB::raw('DATE(p.mv_req_in)'), [$FtglMulai, $FtglSelesai])
            ->orderByDesc('p.mv_req_update')
            ->get();
        $filename = "Lap. Pemindahan Ruangan Mengajar Kmp-{$lokasi} ({$FtglMulai}_sd_{$FtglSelesai}).csv";

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Kode Dosen', 'Nama Dosen', 'Mata Kuliah', 'Kelas', 'Ruangan Awal', 'Ruangan Ganti', 'Jam Kuliah', 'Tanggal Kuliah', 'Waktu Pengajuan', 'Waktu Selesai', 'Alasan Pemindahan']);
            foreach ($data as $index => $row) {
                fputcsv($handle, [
                    $index + 1,
                    $row->username,
                    ucwords(strtolower($row->nama_dsn)),
                    $row->mv_mkul,
                    $row->mv_kelas,
                    $row->room_asal,
                    $row->room_ganti,
                    "'" . $row->mv_jamkul . "'",
                    tglID($row->mv_tgl_mkul),
                    tglID($row->mv_req_in) . ', ' . timeID($row->mv_req_in),
                    tglID($row->mv_req_update) . ', ' . timeID($row->mv_req_update),
                    $row->mv_reason
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
