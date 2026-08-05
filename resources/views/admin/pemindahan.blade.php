@extends('layouts.app')

@section('title', 'Admin | Dashboard Pemindahan Ruangan')

@section('content')
    @php
        $stLbl = [
            'QUEUED'       => 'Pending',
            'CONFIRMED'    => 'Diterima',
            'DISCHARGED'   => 'Selesai',
            'VOIDED'       => 'Ditolak'
        ];
    @endphp
    <div class="mb-6">
        <p class="ml-2 text-24 font-semibold text-blue-light-700 dark:text-gray-300">
            <span id="selamat"></span>, Admin {{ ucwords($user->admin->nama_adm) }}!
        </p>
        <div class="mt-3">
            <h2 class="ml-3 text-20 font-semibold text-black dark:text-gray-300">Dashboard Pemindahan Ruangan Mengajar Dosen</h2>
            <p class="ml-3 text-16 mt-1 font-medium text-black dark:text-gray-300">Region Depok</p>
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
    {{-- room empty --}}
        <div class="mt-6 mb-6 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
            <h4 class="mb-2 -mt-1 text-18 font-semibold text-gray-800 dark:text-white/90">Total Ruangan Kelas Kosong</h4>
            <p class="ml-2 mb-4 text-16 font-medium text-gray-600 dark:text-white/90">Jam perkuliahan ke-
                @if ($jamNow)
                    {{ $jamNow }} ({{ $listJam[$jamNow] }})
                @else
                    x (--:--)
                @endif
            </p>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                @foreach ($room_emp as $loc => $data)
                    <div class="rounded-xl bg-gray-100 p-4 text-center dark:bg-white/3">
                        <p class="text-14 font-semibold uppercase tracking-wide text-gray-700 dark:text-white/90">Kampus {{ $loc }}</p>
                        <p class="mt-1 text-title-sm font-bold text-success-600 dark:text-success-500">
                            {{ $data['kosong'] > 0 ? $data['kosong'] : 'FULL' }}<span class="text-14 font-medium text-gray-500 dark:text-white/75">/{{ $data['total'] }}</span>
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    {{-- req-mv --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-warning-100 rounded-xl dark:bg-gray-800">
                        <svg class="fill-warning-500" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z" fill="" />
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-500 dark:text-gray-400">Pengajuan Pemindahan</span>
                </div>
                <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-warning-500">
                    {{ ($mv_pending ?? 0) > 0 ? $mv_pending : '-' }}
                </h4>
            </div>
    {{-- acc-mv --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-success-100 rounded-xl dark:bg-gray-800">
                        <svg class="fill-success-600 dark:fill-success-400" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="" />
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-500 dark:text-gray-400">Pemindahan Disetujui</span>
                </div>
                <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-success-600 dark:text-success-500">
                    {{ ($mv_aktif ?? 0) > 0 ? $mv_aktif : '-' }}
                </h4>
            </div>
    {{-- done-mv --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-success-100 rounded-xl dark:bg-gray-800">
                        <svg class="fill-success-600 dark:fill-success-400" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.4" d="M8 3a2 2 0 0 0-2 2v1H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-1V5a2 2 0 0 0-2-2H8z"/>
                            <path d="M9 3h6a1 1 0 0 1 1 1v2H8V4a1 1 0 0 1 1-1z"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16 12.7l-4.3 4.3-2.4-2.4a1 1 0 1 1 1.4-1.4l1 1 3.6-3.6a1 1 0 1 1 1.4 1.4z"/>
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-500 dark:text-gray-400">Pemindahan Selesai</span>
                </div>
                <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-success-600 dark:text-success-500">
                    {{ ($mv_selesai ?? 0) > 0 ? $mv_selesai : '-' }}
                </h4>
            </div>
    {{-- all-mv --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-light-100 rounded-xl dark:bg-gray-800">
                        <svg class="fill-blue-light-800 dark:fill-blue-light-600" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 12H7v-2h10v2zm0-4H7V9h10v2z" fill="" />
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-500 dark:text-gray-400">Total Pengajuan</span>
                </div>
                <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-blue-light-800 dark:text-blue-light-600">
                    {{ ($mv_total ?? 0) > 0 ? $mv_total : '-' }}
                </h4>
            </div>
        </div>
    <div class="mt-4 mb-4 flex items-center justify-between">
        <h4 class="ml-3 mt-4 text-18 font-semibold text-black dark:text-gray-300">Data Pemindahan</h4>
        <button type="button" onclick="refreshPage()"
            title="Refresh halaman"
            class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-15 font-medium transition-colors duration-150 bg-blue-light-700 text-white hover:bg-blue-light-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span class="hidden sm:inline">Refresh</span>
        </button>
    </div>
    {{-- table --}}
        <div class="mt-4 min-w-0 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                <form method="GET" class="w-full max-w-70">
                    <div class="relative flex items-center gap-2">
                        <div class="relative flex-1">
                            <input type="text" name="search_MV" value="{{ request('search_MV') }}" placeholder="Cari nama Dosen..."
                                class="h-10 w-full rounded-lg border border-gray-200 bg-transparent pl-9 pr-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                        </div>
                        @if (request('search_MV'))
                            <a href="{{ url()->current() }}" title="Hapus pencarian"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-error-700 text-error-500 hover:bg-error-100 dark:hover:bg-error-100/50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="min-w-0 w-full overflow-x-auto">
                <div x-data="{
                    modalOpen: false, mv_id: null, mv_status: '', mv_reason: '', mv_mkul: '', mv_kelas: '', 
                    room_asal: '', room_ganti: '', room_ganti_region: '', adminRegion: '{{ $user->admin->region_adm }}',
                    openModal(data) {
                        this.mv_id = data.id;
                        this.mv_status = data.status;
                        this.mv_reason = data.reason || 'Tidak ada alasan yang dicantumkan.';
                        this.mv_mkul = data.mkul;
                        this.mv_kelas = data.kelas;
                        this.room_asal = data.roomAsal;
                        this.room_ganti = data.roomGanti;
                        this.room_ganti_region = data.roomGantiRegion;
                        this.modalOpen = true;
                    },
                    canApprove() {
                        return this.room_ganti_region === this.adminRegion;
                    }}">
                    <table class="w-max min-w-300">
                        <thead class="text-14">
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">NO</th>
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">NAMA</th>
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">MATA KULIAH</th>
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">KELAS</th>
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">RUANGAN AWAL</th>
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">RUANGAN GANTI</th>
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">JAM KULIAH</th>
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">TANGGAL KULIAH</th>
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">TIMESTAMP</th>
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">STATUS</th>
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="text-14 font-medium divide-y divide-gray-100 dark:divide-gray-800">
                            @php $currentYearMv = null; @endphp
                            @forelse($mv_dsn as $index => $row)
                                @php
                                    $rowYear = \Carbon\Carbon::parse($row->mv_tgl_mkul)->year;
                                    $noUrut = $mv_dsn->firstItem() + $index;
                                @endphp
                                @if ($currentYearMv !== $rowYear)
                                    <tr>
                                        <td colspan="11" class="bg-gray-50 px-5 py-2 text-center text-12 font-semibold uppercase tracking-wide text-blue-light-700 dark:bg-white/2 dark:text-blue-light-500">
                                            &mdash; &mdash; Tahun {{ $rowYear }} &mdash; &mdash;
                                        </td>
                                    </tr>
                                    @php $currentYearMv = $rowYear; @endphp
                                @endif

                                <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-white/2">
                                    <td class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">{{ $noUrut }}</td>
                                    <td class="px-5 py-3 text-center text-black dark:text-white/90">{{ ucwords(strtolower($row->nama_dsn)) }}<br><a class="font-mono">({{ $row->kode_dsn }})</a></td>
                                    <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $row->mv_mkul }}</td>
                                    <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ ucwords($row->mv_kelas) }}</td>
                                    <td class="px-5 py-3 text-center text-warning-500">{{ ucwords($row->room_asal) }}</td>
                                    <td class="px-5 py-3 text-center text-blue-light-800">{{ ucwords($row->room_ganti) }}</td>
                                    <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->mv_jamkul }}</td>
                                    <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ tglID($row->mv_tgl_mkul) }}</td>
                                    <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">
                                        @if ($row->mv_status === 'QUEUED')
                                            {{ tglID($row->mv_req_in) }}, {{ timeID($row->mv_req_in) }}
                                        @else
                                            {{ tglID($row->mv_req_update) }}, {{ timeID($row->mv_req_update) }}
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        @php
                                            $badgeColor = match($row->mv_status) {
                                                'QUEUED' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
                                                'CONFIRMED' => 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-500',
                                                'DISCHARGED' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                                'VOIDED' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                                default => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-full py-0.5 px-2.5 {{ $badgeColor }}">
                                            {{ $stLbl[$row->mv_status] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center whitespace-nowrap text-12">
                                        <div class="flex justify-center gap-2">
                                            <button type="button"
                                                @click="openModal({
                                                    id: '{{ $row->mv_id }}',
                                                    status: '{{ $row->mv_status }}',
                                                    reason: '{{ addslashes($row->mv_reason ?? '') }}',
                                                    mkul: '{{ addslashes($row->mv_mkul) }}',
                                                    kelas: '{{ addslashes($row->mv_kelas) }}',
                                                    roomAsal: '{{ addslashes($row->room_asal) }}',
                                                    roomGanti: '{{ addslashes($row->room_ganti) }}',
                                                    roomGantiRegion: '{{ $row->room_ganti_region }}'
                                                })"
                                                class="rounded-lg border border-gray-200 py-1.5 text-gray-600 transition-colors duration-150
                                                    hover:border-blue-light-300 hover:bg-blue-light-50 hover:text-blue-light-600
                                                    {{ in_array($row->mv_status, ['DISCHARGED', 'VOIDED']) ? 'px-3' : 'px-9' }}">
                                                DETAIL
                                            </button>
                                            @if (in_array($row->mv_status, ['DISCHARGED', 'VOIDED']))
                                                <form action="{{ route('mv.destroy', $row->mv_id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin hapus data pemindahan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="rounded-lg border border-red-200 px-3 py-1.5 text-red-600 transition-colors duration-150 hover:bg-red-50">
                                                        HAPUS
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">Belum ada data pemindahan ruangan mengajar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
            {{-- Modal Detail Pemindahan --}}
                    <div x-show="modalOpen" x-transition style="display:none" @click.self="modalOpen = false"
                        class="fixed inset-0 z-99999 flex items-center justify-center bg-gray-900/50 p-4">
                        <div class="w-full max-w-md rounded-2xl bg-white p-6 dark:bg-gray-900">
                            <h3 class="mb-4 text-16 font-semibold text-gray-800 dark:text-white/90">Detail Pengajuan Pemindahan</h3>
                            <div class="space-y-2 text-14">
                                <div class="flex justify-between ml-3 mr-3">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Mata Kuliah</span>
                                    <span class="font-semibold text-gray-800 dark:text-white/90" x-text="mv_mkul"></span>
                                </div>
                                <div class="flex justify-between ml-3 mr-3">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Kelas</span>
                                    <span class="font-semibold text-gray-800 dark:text-white/90" x-text="mv_kelas"></span>
                                </div>
                                <div class="flex justify-between ml-3 mr-3">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Ruangan Awal</span>
                                    <span class="font-semibold text-warning-600" x-text="room_asal"></span>
                                </div>
                                <div class="flex justify-between ml-3 mr-3">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Ruangan Ganti</span>
                                    <span class="font-semibold text-blue-light-700" x-text="room_ganti"></span>
                                </div>
                            </div>

                            <div class="mt-4 rounded-lg bg-gray-50 p-3 text-sm text-gray-600 dark:bg-white/5 dark:text-gray-400">
                                <p class="mb-1 font-medium text-gray-700 dark:text-gray-300">Alasan Pemindahan:</p>
                                <p x-text="mv_reason"></p>
                            </div>

                            <div class="mt-6 flex text-15 items-center justify-end gap-3">
                                <button type="button" @click="modalOpen = false"
                                    class="rounded-lg border border-gray-200 px-4 py-2 font-medium text-white bg-blue-light-700 hover:bg-blue-light-900">
                                    Tutup
                                </button>

                                <template x-if="mv_status === 'QUEUED'">
                                    <div class="flex flex-col items-end gap-2">
                                        <div class="flex gap-3">
                                            <button type="button" @click="confirmPemindahan(mv_id, 'VOIDED'); modalOpen = false"
                                                :disabled="!canApprove()" title="Hanya admin kampus ruangan pengganti yang dapat menolak/menerima."
                                                class="rounded-lg px-4 py-2 font-medium text-white transition-colors duration-150 bg-error-500 hover:bg-error-600 disabled:bg-gray-300 disabled:cursor-not-allowed dark:disabled:bg-gray-700">
                                                Tolak
                                            </button>
                                            <button type="button" @click="confirmPemindahan(mv_id, 'CONFIRMED'); modalOpen = false"
                                                :disabled="!canApprove()" title="Hanya admin kampus ruangan pengganti yang dapat menolak/menerima."
                                                class="rounded-lg px-4 py-2 font-medium text-white transition-colors duration-150 bg-blue-light-500 hover:bg-blue-light-600 disabled:bg-gray-300 disabled:cursor-not-allowed dark:disabled:bg-gray-700">
                                                Terima
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="mv_status === 'CONFIRMED'">
                                    <button type="button" @click="confirmPemindahan(mv_id, 'DISCHARGED'); modalOpen = false"
                                        class="rounded-lg bg-success-500 px-4 py-2 font-medium text-white hover:bg-success-600">
                                        Selesai
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- page-handle --}}
        @if ($mv_dsn->hasPages())
            <div class="mt-4 flex items-center justify-between border-t border-gray-100 px-2 pt-4 dark:border-gray-800">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan {{ $mv_dsn->firstItem() }}–{{ $mv_dsn->lastItem() }} dari {{ $mv_dsn->total() }} data
                </p>
                <div class="flex items-center gap-1">
                    {{-- Previous --}}
                    @if ($mv_dsn->onFirstPage())
                        <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Prev</span>
                    @else
                        <a href="{{ $mv_dsn->previousPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Prev</a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach ($mv_dsn->getUrlRange(1, $mv_dsn->lastPage()) as $page => $url)
                        @if ($page == $mv_dsn->currentPage())
                            <span class="rounded-lg bg-blue-light-700 px-3 py-1.5 text-sm font-medium text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($mv_dsn->hasMorePages())
                        <a href="{{ $mv_dsn->nextPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Next</a>
                    @else
                        <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Next</span>
                    @endif
                </div>
            </div>
        @endif
@endsection
