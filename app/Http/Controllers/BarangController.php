<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function search(Request $request) {
        $type     = $request->query('type', '');
        $location = $request->query('location', '');
        $query    = $request->query('query', '');

        $validLocations = ['D', 'E', 'G', 'F8'];
        if (!in_array($location, $validLocations)) {
            return response()->json([]);
        }

        $catMap = [
            'PAC'  => 'PAC',
            'VWR'  => 'VWR',
            'HDMI' => 'HDMI',
        ];
        if (!array_key_exists($type, $catMap)) {
            return response()->json([]);
        }

        $results = Barang::where('brg_region', $location)
            ->where('brg_cat', $catMap[$type])
            ->where('brg_status', 'ready')
            ->where(function ($q) use ($query) {
                $q->where('brg_name', 'LIKE', "%{$query}%")
                    ->orWhere('brg_kode', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get(['brg_id', 'brg_kode', 'brg_name']);
        return response()->json($results);
    }

    public function brg_index(Request $request) {
        $user = User::with(['admin'])->find(session('user_id'));
        $role = session('role');
        $lokasi = $user->admin->region_adm;

        #COUNT $PAC
            $pac = collect();
            $pac_ready = 0;
            $pac_used = 0;
            $pac_rest = 0;
            if ($lokasi === 'E') {
                $pac_main = Barang::where('brg_cat', 'PAC');
                $pac = (clone $pac_main)
                    ->when($request->filled('search_pac'), function ($query) use ($request) {
                        $query->where('brg_kode', 'like', '%' . $request->search_pac . '%');
                    })
                    ->orderBy('brg_status', 'asc')
                    ->orderByDesc('brg_updated')
                    ->paginate(10, ['*'], 'page_kartu')
                    ->withQueryString();
                $pac_ready = (clone $pac_main)->where('brg_status', 'READY')->count();
                $pac_used  = (clone $pac_main)->where('brg_status', 'USED')->count();
                $pac_rest  = (clone $pac_main)->where('brg_status', 'REST')->count();
            }

        #COUNT $VWR
            $vwr_main = Barang::where('brg_cat', 'VWR')
            ->where('brg_region', $lokasi);
            $vwr = (clone $vwr_main)
                ->when($request->filled('search_vwr'), function ($query) use ($request) {
                    $query->where('brg_kode', 'like', '%' . $request->search_vwr . '%');
                })
                ->orderBy('brg_status', 'asc')
                ->orderByDesc('brg_updated')
                ->paginate(10, ['*'], 'page_viewer')
                ->withQueryString();
            $vwr_ready = (clone $vwr_main)->where('brg_status', 'READY')->count();
            $vwr_used  = (clone $vwr_main)->where('brg_status', 'USED')->count();
            $vwr_rest = (clone $vwr_main)->where('brg_status', 'REST')->count();

        #COUNT $HDMI
            $hdmi_main = Barang::where('brg_cat', 'HDMI')
                ->where('brg_region', $lokasi);
            $hdmi = (clone $hdmi_main)
                ->when($request->filled('search_hdmi'), function ($query) use ($request) {
                    $query->where('brg_kode', 'like', '%' . $request->search_hdmi . '%');
                })
                ->orderBy('brg_status', 'asc')
                ->orderByDesc('brg_updated')
                ->paginate(10, ['*'], 'page_hdmi')
                ->withQueryString();
            $hdmi_ready = (clone $hdmi_main)->where('brg_status', 'READY')->count();
            $hdmi_used  = (clone $hdmi_main)->where('brg_status', 'USED')->count();
            $hdmi_rest  = (clone $hdmi_main)->where('brg_status', 'REST')->count();

        return view('admin.barang', compact(
            'user', 'role', 'lokasi',
            'pac', 'pac_used', 'pac_ready', 'pac_rest',
            'vwr', 'vwr_used', 'vwr_ready', 'vwr_rest',
            'hdmi', 'hdmi_used', 'hdmi_ready', 'hdmi_rest'
        ));
    }

    public function store(Request $request) {
        $user = User::with(['admin'])->find(session('user_id'));
        $lokasi = $user->admin->region_adm;

        $request->validate([
            'brg_kode'      => 'required|string|max:15',
            'brg_cat'       => 'required|in:VWR,HDMI',
            'brg_name'      => 'required|string|max:100',
        ]);

        Barang::create([
            'brg_kode'      => $request->brg_kode,
            'brg_cat'       => $request->brg_cat,
            'brg_name'      => $request->brg_name,
            'brg_region'    => $lokasi,
            'brg_status'    => 'REST'
        ]);
        return redirect()->route('brg.index');
    }

    public function update(Request $request, int $id) {
        $barang = Barang::findOrFail($id);
        $rules = [
            'brg_kode'   => 'required|string|max:15',
            'brg_name'   => 'required|string|max:100',
            'brg_status' => 'required|in:READY,REST',
        ];

        if ($barang->brg_cat !== 'PAC') {
            $rules['brg_region'] = 'required|in:D,E,G,F8';
        }

        $request->validate($rules);
        if ($barang->brg_status === 'USED') {
            return back()->with('error', 'Perangkat sedang digunakan, tidak dapat diedit!');
        }

        $updated = $barang->update([
            'brg_kode'   => $request->brg_kode,
            'brg_name'   => $request->brg_name,
            'brg_region' => $barang->brg_cat === 'PAC' ? 'E' : $request->brg_region,
            'brg_status' => $request->brg_status,
        ]);

        if ($updated) {
            return redirect()->route('brg.index')->with('success', 'Data berhasil diperbarui.');
        }
        return back()->with('error', 'Gagal update data!');
    }

    public function destroy(int $id) {
        $barang = Barang::findOrFail($id);

        if ($barang->brg_status === 'USED') {
            return back()->with('error', 'Perangkat sedang digunakan, tidak dapat dihapus!');
        }
        $barang->delete();
        return redirect()->route('brg.index');
    }
}
