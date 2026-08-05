<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Jadwal;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function showForm() {
        $user = User::with(['mahasiswa', 'dosen'])->find(session('user_id'));
        $role = session('role');
        return view('form.peminjaman', compact('user', 'role'));
    }

    public function store(Request $request) {
        $role = session('role');
        $now = now('Asia/Jakarta');
        $hour = $now->hour;
        $day = $now->dayOfWeek;
        if ($day === 0 || $hour < 7 || $hour >= 17) {
            return redirect('/dashboard/form-peminjaman')
            ->with('error', 'Maaf, peminjaman hanya dapat dilakukan pada saat JAM dan HARI PERKULIAHAN!');
        }

        $active_brw = Peminjaman::where('id_user', session('user_id'))
            ->where('brw_otp', '!=', '------')
            ->exists();
        if ($active_brw) {
            return redirect()->route('form.peminjaman')
                ->with('error', 'Selesaikan terlebih dulu peminjaman terakhir!');
        }

        $request->merge([
            'brw_mkul' => trim(str_replace(['*', '/'], '', $request->brw_mkul)),
            'no_hp'    => $request->no_hp ? preg_replace('/\D/', '', $request->no_hp) : null,
        ]);

        $rules = [
            'brw_region' => 'required|in:D,E,G,F8',
            'brw_room'   => 'required|string|max:6',
            'brw_jamkul' => ['required', 'string', 'max:11', 'regex:/^[0-9\/]+$/'],
            'kode_hdmi'  => 'required|integer'
        ];

        if ($role === 'mhs') {
            $rules['brw_pengajar'] = 'required|string|max:185';
            $rules['brw_kelas']    = 'required|string|size:5';
            $rules['no_hp']        = ['required', 'string', 'min:10', 'max:12', 'regex:/^8[0-9]+$/'];
            $rules['brw_jaminan']  = 'required|in:KTP,SIM';
        }

        $request->validate($rules, [
            'brw_kelas.size'     => 'Format kelas salah!',
            'brw_jamkul.regex'   => 'Format jam perkuliahan tidak valid.',
            'no_hp.regex'        => 'Nomor HP tidak valid! (Harus diawali angka 8)',
            'no_hp.min'          => 'Panjang No. HP minimal 10 digit.',
            'no_hp.max'          => 'Panjang No. HP maksimal 12 digit.',
            'kode_hdmi.required' => 'Pilih salah satu kabel HDMI!',
            'kode_hdmi.integer'  => 'Pilihan HDMI tidak valid.'
        ]);

        $brw_room     = strtoupper(trim($request->brw_room));
        $brw_mkul     = trim($request->brw_mkul);
        $brw_kelas    = $role === 'dsn' ? null : strtoupper(trim($request->brw_kelas));
        $brw_pengajar = $role === 'dsn' ? null : strtoupper(trim($request->brw_pengajar));
        $no_hp        = $role === 'dsn' ? null : trim($request->no_hp);

        #OTP Gen
        do {
                $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            } while (Peminjaman::where('brw_otp', $otp)->exists());

        $brw = Peminjaman::create([
            'id_user'      => session('user_id'),
            'brw_mkul'     => $brw_mkul,
            'brw_pengajar' => $brw_pengajar,
            'brw_kelas'    => $brw_kelas,
            'brw_room'     => $brw_room,
            'brw_jamkul'   => trim($request->brw_jamkul),
            'no_hp'        => $role === 'dsn' ? null : '+62' . $no_hp,
            'brw_region'   => $request->brw_region,
            'brw_jaminan'  => $role === 'dsn' ? null : $request->brw_jaminan,
            'brw_otp'      => $otp,
            'brw_status'   => 'QUEUED',
        ]);

        $brgSelect = array_filter([
            $request->kode_pac,
            $request->kode_vwr,
            $request->kode_hdmi
        ]);

        foreach ($brgSelect as $brg_id) {
            DB::table('detail_brg')->insert([
                'brw_id' => $brw->brw_id,
                'brg_id' => (int) $brg_id,
            ]);
            Barang::where('brg_id', (int) $brg_id)
            ->update(['brg_status' => 'used']);
        }
        session(['last_brw_id' => $brw->brw_id]);
        return redirect()->route('otp.show');
    }
    
    public function showOTP() {
        $id  = session('last_brw_id');
        $brw = Peminjaman::find($id);
        if (!$brw || $brw->id_user !== session('user_id')) {
            return redirect()->route('form.peminjaman');
        }
        $role = session('role');
        return view('notification.otp', compact('brw', 'role'));
    }

    public function getMatkul(Request $request) {
        $query = $request->query('q', '');
        $data  = Jadwal::select(DB::raw('DISTINCT sch_mkul'))
            ->where('sch_mkul', 'LIKE', "%{$query}%")
            ->orderBy('sch_mkul')
            ->limit(10)
            ->pluck('sch_mkul');
        return response()->json($data);
    }

    public function getHistory() {
        if (session('role') === 'adm') {
            return "<script>alert('Akses ditolak!'); window.location='/login';</script>";
        }
        $user = User::with(['mahasiswa', 'dosen'])->find(session('user_id'));
        $role = session('role');
        $now = now('Asia/Jakarta');
        $listJam = ['06:30 - 07:30', '07:30 - 08:30', '08:30 - 09:30', '09:30 - 10:30',
                    '10:30 - 11:30', '11:30 - 12:30', '12:30 - 13:30', '13:30 - 14:30',
                    '14:30 - 15:30', '15:30 - 16:30', '16:30 - 17:30'];
        $currTime = $now->format('H:i');


        $brw_active = Peminjaman::from('peminjaman as p')
            ->leftJoin('detail_brg as pb', 'p.brw_id', '=', 'pb.brw_id')
            ->leftJoin('barang as b', 'pb.brg_id', '=', 'b.brg_id')
            ->where('p.id_user', session('user_id'))
            ->where('p.brw_otp', '!=', '------')
            ->select('p.brw_id', 'p.brw_mkul', 'p.brw_room', 'p.brw_jamkul', 'p.brw_region',
                'p.brw_status', 'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update',
                DB::raw("GROUP_CONCAT(IF(b.brg_name LIKE '%P-%', 
                CONCAT(b.brg_name, ' (', b.brg_kode, ')'), b.brg_name) 
                ORDER BY b.brg_cat SEPARATOR ', ') as barang_list"))
            ->groupBy('p.brw_id', 'p.brw_mkul', 'p.brw_room', 'p.brw_jamkul', 'p.brw_region',
                'p.brw_status', 'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update')
            ->orderByDesc('p.brw_req_in')
            ->first();
        $brw_otp = $brw_active->brw_otp ?? '------';

        $jamBACK = null;
        if ($brw_active) {
            $jamKulArr = explode('/', $brw_active->brw_jamkul);
            $jamSlot = count($jamKulArr);
            $jamBACK = \Carbon\Carbon::parse($brw_active->brw_req_in)
                ->addHours($jamSlot)
                ->addMinutes(30);
        }

        $history = Peminjaman::from('peminjaman as p')
            ->leftJoin('user as u', 'p.id_user', '=', 'u.id_user')
            ->leftJoin('detail_brg as pb', 'p.brw_id', '=', 'pb.brw_id')
            ->leftJoin('barang as b', 'pb.brg_id', '=', 'b.brg_id')
            ->where('p.id_user', session('user_id'))
            ->where('p.brw_otp', '=', '------')
            ->select('u.username', 'p.brw_id', 'p.brw_mkul', 'p.brw_pengajar',
                'p.brw_room', 'p.brw_jamkul', 'p.brw_region', 'p.brw_status',
                'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update', 'p.brw_jaminan',
                DB::raw("GROUP_CONCAT(IF(b.brg_name LIKE '%P-%', 
                CONCAT(b.brg_name, ' (', b.brg_kode, ')'), b.brg_name) 
                ORDER BY b.brg_cat SEPARATOR ', ') as barang_list"))
            ->groupBy('p.brw_id', 'u.username', 'p.brw_mkul', 'p.brw_pengajar',
                'p.brw_room', 'p.brw_jamkul', 'p.brw_region', 'p.brw_status',
                'p.brw_otp', 'p.brw_req_in', 'p.brw_req_update', 'p.brw_jaminan')
            ->orderByDesc('p.brw_req_in')
            ->orderBy('p.brw_status', 'asc')
            ->orderByDesc('p.brw_req_update')
            ->paginate(8)
            ->withQueryString();
        return view('dashboard.peminjaman', compact('history', 'user', 'role', 'brw_active', 
        'brw_otp', 'listJam', 'currTime', 'jamBACK'));
    }
}
