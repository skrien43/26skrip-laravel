@extends('layouts.app')

@section('title', 'Admin | Manajemen Admin')

@section('content')
    <div x-data="{
        modalOpen: false,
        mode: 'create',
        form: { id: '', username: '', nama_adm: '', kode_peg: '', region_adm: '', password: '', password_confirmation: '' },
        openCreate() {
            this.mode = 'create';
            this.form = { id: '', username: '', nama_adm: '', kode_peg: '', region_adm: '', password: '', password_confirmation: '' };
            this.modalOpen = true;
        },
        openEdit(data) {
            this.mode = 'edit';
            this.form = { id: data.id, username: data.username, nama_adm: data.nama_adm, kode_peg: data.kode_peg, region_adm: data.region_adm, password: '', password_confirmation: '' };
            this.modalOpen = true;
        }
    }">
    <div class="mb-6">
        <p class="ml-2 text-24 font-semibold text-blue-light-700 dark:text-gray-300">
            <span id="selamat"></span>, Admin {{ ucwords($user->admin->nama_adm) }}!
        </p>
        <div class="mt-3">
            <h2 class="ml-3 text-20 font-semibold text-black dark:text-gray-300">Manajemen Admin</h2>
            <p class="ml-3 text-16 mt-1 font-medium text-black dark:text-gray-300">Region Depok - Kampus {{ $lokasi }}</p>
        </div>
    </div>
    {{-- notif error in window --}}
        @if (session('error'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-error-200 bg-error-50 px-4 py-3 dark:border-error-500/30 dark:bg-error-500/10">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-error-600 dark:text-error-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M12 3C7.03 3 3 7.03 3 12s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9z"/>
                </svg>
                <p class="text-14 font-medium items-center text-error-700 dark:text-error-300">
                    {{ session('error') }}
                </p>
            </div>
        @endif
    {{-- add account + table --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
            <div class="mb-5 flex flex-wrap items-center justify-end gap-3">
                <button type="button" @click="openCreate()"
                    class="rounded-lg bg-blue-light-700 px-4 py-2 text-15 font-medium text-white transition-colors duration-150 hover:bg-blue-light-950">
                    Tambah Akun
                </button>
                <button type="button" onclick="refreshPage()"
                    title="Refresh halaman"
                    class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-15 font-medium transition-colors duration-150 bg-blue-light-700 text-white hover:bg-blue-light-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="hidden sm:inline">Refresh</span>
                </button>
            </div>
            <div class="min-w-0 overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
                <div class="w-full overflow-x-auto">
                    <table class="w-full table-fixed text-left">
                        <thead class="text-14 font-medium">
                            <tr class="border-b border-gray-100 bg-gray-50/60 dark:border-gray-800 dark:bg-white/2">
                                <th class="w-[8%] px-4 py-3 text-center text-gray-500 dark:text-gray-400">NO</th>
                                <th class="w-[22%] px-4 py-3 text-center text-gray-500 dark:text-gray-400">KODE PEGAWAI</th>
                                <th class="w-[25%] px-4 py-3 text-center text-gray-500 dark:text-gray-400">USERNAME</th>
                                <th class="w-[25%] px-4 py-3 text-center text-gray-500 dark:text-gray-400">NAMA</th>
                                <th class="w-[20%] px-4 py-3 text-center text-gray-500 dark:text-gray-400">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="text-14 font-medium divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($admin as $index => $u)
                                <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-white/2">
                                    <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-center text-black dark:text-gray-400 font-mono text-16 truncate">{{ $u->admin->kode_peg ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400 truncate">
                                        {{ $u->username }}
                                        @if ((int) session('user_id') === (int) $u->id_user)
                                            <span class="ml-1 inline-flex items-center rounded-full bg-success-200 px-2 py-0.5 text-xs font-medium text-success-700 dark:bg-success-500/15 dark:text-success-500">
                                                YOU
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-black dark:text-white/90 truncate">{{ $u->admin->nama_adm ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap text-13">
                                        @if ((int) session('user_id') !== (int) $u->id_user)
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button"
                                                    @click="openEdit({{ json_encode([
                                                        'id' => $u->id_user,
                                                        'username' => $u->username,
                                                        'nama_adm' => $u->admin->nama_adm ?? '',
                                                        'kode_peg' => $u->admin->kode_peg ?? '',
                                                        'region_adm' => $u->admin->region_adm ?? ''
                                                    ]) }})"
                                                    class="rounded-lg border border-gray-200 px-2.5 py-1.5 font-medium text-gray-600 transition-colors duration-150 hover:border-blue-light-300 hover:bg-blue-light-50 hover:text-blue-light-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-blue-light-500/10">
                                                    EDIT
                                                </button>
                                                <form action="{{ route('admin.mgmt.destroy', $u->id_user) }}" method="POST" class="inline-block"
                                                    onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="rounded-lg border border-red-200 px-2.5 py-1.5 font-medium text-red-600 disabled:opacity-40 disabled:cursor-not-allowed">
                                                        HAPUS
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="flex justify-center">
                                                <span class="w-31.25 block text-center rounded-lg border border-gray-300 px-3 py-1.5 font-medium text-gray-400 opacity-60 cursor-not-allowed dark:border-gray-700 dark:text-gray-500">
                                                    EDIT
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">Belum ada data admin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    {{-- edit admin --}}
        <div x-show="modalOpen" x-transition style="display:none" class="fixed inset-0 z-99999 flex items-center justify-center bg-gray-900/50 p-4">
            <div @click.away="modalOpen = false" class="w-full max-w-lg rounded-2xl bg-white p-6 dark:bg-gray-900">
                <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90" x-text="mode === 'create' ? 'Tambah Akun Admin' : 'Edit Akun Admin'"></h3>
                <form :action="mode === 'create' ? '{{ route('admin.mgmt.store') }}' : '/admin/mgmt/' + form.id"
                    method="POST" class="space-y-4">
                    @csrf
                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                        <input type="text" name="username" x-model="form.username" required
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Admin</label>
                            <input type="text" name="nama_adm" x-model="form.nama_adm" required
                                class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Pegawai</label>
                            <input type="text" name="kode_peg" x-model="form.kode_peg" required
                                class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Region</label>
                        <select name="region_adm" x-model="form.region_adm" required
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                            <option value="" disabled>Pilih region</option>
                            <option value="D">Kampus D</option>
                            <option value="E">Kampus E</option>
                            <option value="G">Kampus G</option>
                            <option value="F8">Kampus F8</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Password <span x-show="mode === 'create'" class="text-error-500">*</span>
                            </label>
                            <input type="password" name="password" x-model="form.password" :required="mode === 'create'"
                                :placeholder="mode === 'edit' ? 'Kosongkan jika tidak ubah' : 'Minimal 6 karakter'"
                                class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" x-model="form.password_confirmation" :required="mode === 'create'"
                                class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-3">
                        <button type="button" @click="modalOpen = false"
                            class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:border-red-200 hover:text-red-600 dark:border-gray-700 dark:text-gray-400">
                            Batal
                        </button>
                        <button type="submit" class="rounded-lg bg-gray-500 px-4 py-2 text-sm font-medium text-white cursor-not-allowed" disabled>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
