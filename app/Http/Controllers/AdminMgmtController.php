<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminMgmtController extends Controller
{
    public function index() {
        $user = User::with(['admin'])->find(session('user_id'));
        $role = session('role');
        $lokasi = $user->admin->region_adm;
        $admin = User::where('us_role', 'adm')
            ->whereHas('admin', function ($q) use ($lokasi) {
                $q->where('region_adm', $lokasi);
            })
            ->with('admin')
            ->orderBy('username', 'asc')
            ->get();
        return view('admin.manage-adm', compact('admin', 'role', 'user', 'lokasi'));
    }

    public function store(Request $request) {
        $request->validate([
            'username'    => ['required', 'string', 'max:50', 'unique:user,username'],
            'password'    => ['required', 'string', 'min:6', 'confirmed'],
            'nama_adm'    => ['required', 'string', 'max:255'],
            'kode_peg'    => ['required', 'string', 'max:50'],
            'region_adm'  => ['required', 'in:D,E,G,F8'],
        ], [
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username sudah digunakan.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak sesuai.',
            'nama_adm.required'   => 'Nama admin wajib diisi.',
            'kode_peg.required'   => 'Kode pegawai wajib diisi.',
            'region_adm.required' => 'Region wajib dipilih.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'us_role'  => 'adm',
            ]);

            Admin::create([
                'id_user'    => $user->id_user,
                'nama_adm'   => $request->nama_adm,
                'kode_peg'   => $request->kode_peg,
                'region_adm' => $request->region_adm,
            ]);
        });
        return redirect()->route('admin.mgmt.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function update(Request $request, int $id) {
        $user = User::where('id_user', $id)->where('us_role', 'adm')->firstOrFail();

        $request->validate([
            'username'    => ['required', 'string', 'max:50', Rule::unique('user', 'username')->ignore($user->id_user, 'id_user')],
            'password'    => ['nullable', 'string', 'min:6', 'confirmed'],
            'nama_adm'    => ['required', 'string', 'max:255'],
            'kode_peg'    => ['required', 'string', 'max:50'],
            'region_adm'  => ['required', 'in:D,E,G,F8'],
        ], [
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username sudah digunakan.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak sesuai.',
            'nama_adm.required'   => 'Nama admin wajib diisi.',
            'kode_peg.required'   => 'Kode pegawai wajib diisi.',
            'region_adm.required' => 'Region wajib dipilih.',
        ]);

        DB::transaction(function () use ($request, $user) {
            $userData = ['username' => $request->username];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            Admin::where('id_user', $user->id_user)->update([
                'nama_adm'   => $request->nama_adm,
                'kode_peg'   => $request->kode_peg,
                'region_adm' => $request->region_adm,
            ]);
        });

        return redirect()->route('admin.mgmt.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(int $id) {
        if ((int) session('user_id') === (int) $id) {
            return redirect()->route('admin.mgmt.index')->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        DB::transaction(function () use ($id) {
            Admin::where('id_user', $id)->delete();
            User::where('id_user', $id)->delete();
        });
        return redirect()->route('admin.mgmt.index')->with('success', 'Admin berhasil dihapus.');
    }
}
