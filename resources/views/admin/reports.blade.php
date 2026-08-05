@extends('layouts.app')

@section('title', 'Admin | Laporan')

@section('content')
    <div class="mb-6">
        <p class="ml-2 text-24 font-semibold text-blue-light-700 dark:text-gray-300">
            <span id="selamat"></span>, Admin {{ ucwords($user->admin->nama_adm) }}!
        </p>
        <div class="mt-3">
            <h2 class="ml-3 text-20 font-semibold text-black dark:text-gray-300">Laporan Data</h2>
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
    {{-- cards 2 --}}
        <h4 class="mr-2 mt-4 mb-2 text-14 text-right font-semibold text-gray-600 dark:text-gray-400">Periode: {{ tglID($tglMulai) }} - {{ tglID($tglSelesai) }} {{ now()->year }}</h4>
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-light-100 rounded-xl dark:bg-gray-800 shrink-0">
                        <svg class="fill-blue-light-800 dark:fill-blue-light-600" width="26" height="26" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.4"
                                d="M7 3a2 2 0 0 0-2 2v5h14V5a2 2 0 0 0-2-2H7z"/>
                            <path d="M8 6h2v2H8V6zm3 0h2v2h-2V6zm3 0h2v2h-2V6zM10 10v4h4v-4h2v5a2 2 0 0 1-2 2h-1v2a1 1 0 1 1-2 0v-2h-1a2 2 0 0 1-2-2v-5h2z"/>
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-600 dark:text-gray-400">Peminjaman Perangkat</span>
                </div>
                <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-blue-light-800 dark:text-blue-light-600">
                    {{ $statPeminjaman->total ?? 0 }}
                </h4>
                <p class="ml-2 text-14 font-medium text-success-600">
                    {{ $statPeminjaman->selesai ?? 0 }} Perangkat telah dikembalikan
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-light-100 rounded-xl dark:bg-gray-800 shrink-0">
                        <svg class="fill-blue-light-800 dark:fill-blue-light-600" width="25" height="25" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.5"
                                d="M8 2a2 2 0 0 0-2 2v18h12V4a2 2 0 0 0-2-2H8z"/>
                            <path d="M9 5h2v2H9V5zm4 0h2v2h-2V5zM9 9h2v2H9V9zm4 0h2v2h-2V9zM9 13h2v2H9v-2zm4 0h2v2h-2v-2zM11 18h2v4h-2v-4z"/>
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-600 dark:text-gray-400">Pemindahan Ruangan</span>
                </div>
                <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-blue-light-800 dark:text-blue-light-600">
                    {{ $statPemindahan->total ?? 0 }}
                </h4>
                <p class="ml-2 text-14 font-medium">
                    <a class="text-success-600">{{ $statPemindahan->selesai ?? 0 }} Selesai</a> &middot; 
                    <a class="text-error-600">{{ $statPemindahan->ditolak ?? 0 }} Ditolak</a>
                </p>
            </div>
        </div>

    {{-- cards ratio & top singko --}}
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">
            <div class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-success-100 rounded-xl dark:bg-gray-800 shrink-0">
                        <svg class="fill-success-600 dark:fill-success-400 stroke-success-600 dark:stroke-success-400" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="" />
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-600 dark:text-gray-400">Rasio Persetujuan Pemindahan</span>
                </div>
                <div class="flex flex-1 items-center justify-center">
                    <h4 class="text-title-sm text-center font-bold text-success-600 dark:text-success-500">{{ $rasioApprove }}%</h4>
                </div>
                <p class="text-14 text-right font-medium text-gray-600 dark:text-gray-400">dari pengajuan yang sudah diproses</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-light-100 rounded-xl dark:bg-gray-800 shrink-0">
                        <svg class="fill-blue-light-800 dark:fill-blue-light-600" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.4" d="M4 20V10a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v10H4z"/>
                            <path d="M10 20V4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v16h-4zm6 0v-7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h-4z"/>
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-600 dark:text-gray-400">Perangkat Paling Sering Dipinjam</span>
                </div>
                <ol class="ml-2 mr-2 mt-4 space-y-2 text-14">
                    @forelse ($topBarang as $item)
                        <li class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-gray-300">
                                {{ BarangList($item->brg_name)[0] ?? $item->brg_name }}
                            </span>
                            <span class="rounded-full bg-blue-light-50 px-2 py-0.5 text-14 font-medium text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400">{{ $item->jumlah }}x</span>
                        </li>
                    @empty
                        <li class="text-14 text-center font-medium text-gray-500">Belum ada data.</li>
                    @endforelse
                </ol>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-warning-100 rounded-xl dark:bg-gray-800 shrink-0">
                        <svg class="fill-warning-500" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z" fill="" />
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-600 dark:text-gray-400">Ruangan Paling Sering Diganti</span>
                </div>
                <ol class="ml-2 mr-2 mt-4 space-y-2 text-14">
                    @forelse ($topRuangan as $item)
                        <li class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-gray-300">{{ $item->room_name }}</span>
                            <span class="rounded-full bg-warning-50 px-2 py-0.5 text-14 font-medium text-warning-500 dark:bg-blue-light-500/15 dark:text-blue-light-400">{{ $item->jumlah }}x</span>
                        </li>
                    @empty
                        <li class="text-14 text-center font-medium text-gray-500">Belum ada data.</li>
                    @endforelse
                </ol>
            </div>
        </div>

    {{-- grafik --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h4 class="ml-3 text-18 font-medium text-gray-700 dark:text-white/90">Grafik Tren Pengajuan</h4>
                <div class="flex flex-wrap items-center justify-end gap-3">
                    <div class="flex items-center gap-1 rounded-lg bg-gray-100 p-1 dark:bg-white/5" x-data="{ mode: 'bulan' }">
                        <button type="button" @click="mode = 'bulan'; window.switchTren('bulan')"
                            :class="mode === 'bulan' ? 'bg-blue-light-700 text-gray-200 dark:text-blue-light-700 dark:bg-gray-300' : 'text-blue-light-700 dark:text-gray-200'"
                            class="rounded-md px-3 py-1.5 text-14 font-medium transition-colors">
                            Per Bulan
                        </button>
                        <button type="button" @click="mode = 'minggu'; window.switchTren('minggu')"
                            :class="mode === 'minggu' ? 'bg-blue-light-700 text-gray-200 dark:text-blue-light-700 dark:bg-gray-300' : 'text-blue-light-700 dark:text-gray-200'"
                            class="rounded-md px-3 py-1.5 text-14 font-medium transition-colors">
                            Per Minggu
                        </button>
                    </div>
                    <button type="button" onclick="refreshPage()"
                        title="Refresh halaman"
                        class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-14 font-medium transition-colors duration-150 bg-blue-light-700 text-white hover:bg-blue-light-900">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="hidden sm:inline">Refresh</span>
                    </button>
                </div>
            </div>
            <div id="chartTren"></div>
        </div>

    {{-- filter data --}}
        <h4 class="ml-3 mt-8 mb-4 text-18 font-semibold text-black dark:text-gray-300">Filter dan Export Data</h4>
        <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
            <form method="GET" action="{{ route('admin.laporan') }}"
                class="flex flex-wrap items-end gap-4"
                x-data='{
                    fp: null,
                    maxDays: 31,
                    init() {
                        this.$nextTick(() => {
                            this.fp = flatpickr(this.$refs.rangeInput, {
                                mode: "range",
                                appendTo: document.body,
                                position: "auto",
                                monthSelectorType: "static",
                                dateFormat: "Y-m-d",
                                defaultDate: [@json($FtglMulai), @json($FtglSelesai)].filter(Boolean),
                                onChange: (selectedDates) => {
                                    if (selectedDates.length === 2) {
                                        const diffDays = Math.round((selectedDates[1] - selectedDates[0]) / 86400000) + 1;
                                        if (diffDays > this.maxDays) {
                                            const capped = new Date(selectedDates[0]);
                                            capped.setDate(capped.getDate() + (this.maxDays - 1));
                                            this.fp.setDate([selectedDates[0], capped], true);
                                            return;
                                        }
                                        this.$refs.tglMulai.value = this.fp.formatDate(selectedDates[0], "Y-m-d");
                                        this.$refs.tglSelesai.value = this.fp.formatDate(selectedDates[1], "Y-m-d");
                                    }
                                }
                            });
                        });
                    }
                }' x-init="init()">

                <div class="flex-1 min-w-50">
                    <label class="ml-1.5 mb-1.5 block text-16 font-medium text-gray-600 dark:text-gray-400">Rentang Tanggal (maks. 1 bulan)</label>
                    <input x-ref="rangeInput" type="text" placeholder="Pilih rentang tanggal..." autocomplete="off" required
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                    <input x-ref="tglMulai" type="hidden" name="tgl_mulai" value="{{ $FtglMulai }}">
                    <input x-ref="tglSelesai" type="hidden" name="tgl_selesai" value="{{ $FtglSelesai }}">
                </div>
                <div class="flex-1 min-w-40">
                    <label class="ml-1.5 mb-1.5 block text-16 font-medium text-gray-600 dark:text-gray-400">Kategori</label>
                    <select name="kategori" required
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                        <option value="" disabled selected {{ $kategori === '' ? 'selected' : '' }}>Pilih kategori</option>
                        <option value="peminjaman" {{ $kategori === 'peminjaman' ? 'selected' : '' }}>Data Peminjaman</option>
                        <option value="pemindahan" {{ $kategori === 'pemindahan' ? 'selected' : '' }}>Data Pemindahan</option>
                    </select>
                </div>
                @if ($filter)
                    <a href="{{ route('admin.laporan') }}"
                        title="Bersihkan filter"
                        class="h-11 inline-flex items-center justify-center rounded-lg bg-error-600 px-4 py-2 text-14 font-medium text-white transition-colors duration-150 hover:bg-error-700">
                        Clear
                    </a>
                @else
                    <button type="submit"
                        title="Terapkan filter"
                        class="h-11 rounded-lg bg-blue-light-700 px-4 py-2 text-14 font-medium text-white transition-colors duration-150 hover:bg-blue-light-900">
                        Terapkan
                    </button>
                @endif
                <button type="button" onclick="refreshPage()"
                    title="Refresh halaman"
                    class="inline-flex items-center justify-center h-11 gap-2 rounded-lg px-3 py-2 text-14 font-medium transition-colors duration-150 bg-blue-light-700 text-white hover:bg-blue-light-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="hidden sm:inline">Refresh</span>
                </button>
            </form>
        </div>
    {{-- tabel export --}}
        @if (!$filter)
            <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-10 text-center dark:border-gray-800 dark:bg-white/3">
                <p class="text-15 text-warning-600">
                    Pilih rentang tanggal dan kategori pada filter di atas, lalu klik <strong>Terapkan</strong> untuk menampilkan data yang ingin diekspor.
                </p>
            </div>
        @else
            <div class="mt-6 min-w-0 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                    <h4 class="ml-1.5 text-16 font-medium text-gray-600 dark:text-gray-400">
                        Data {{ $kategori === 'pemindahan' ? 'Pemindahan Ruangan' : 'Peminjaman Perangkat' }}
                    </h4>
                    <a href="{{ $kategori === 'pemindahan'
                        ? route('laporan.export.pemindahan', ['tgl_mulai' => $FtglMulai, 'tgl_selesai' => $FtglSelesai])
                        : route('laporan.export.peminjaman', ['tgl_mulai' => $FtglMulai, 'tgl_selesai' => $FtglSelesai]) }}"
                        class="rounded-lg bg-blue-light-700 px-4 py-2 text-14 font-medium text-white transition-colors duration-150 hover:bg-blue-light-900">
                        Export CSV
                    </a>
                </div>
                <div class="min-w-0 w-full overflow-x-auto">
                {{-- tb pemindahan --}}
                    @if ($kategori === 'pemindahan')
                        <table class="w-full min-w-300 table-fixed">
                            <thead class="text-14 font-medium ">
                                <tr class="border-b border-gray-100 bg-gray-50/60 dark:border-gray-800 dark:bg-white/2">
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">NO</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">NAMA</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">MATA KULIAH</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">KELAS</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">RUANGAN AWAL</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">RUANGAN GANTI</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">JAM KULIAH</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">TANGGAL</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">WAKTU PENGAJUAN</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">WAKTU SELESAI</th>
                                </tr>
                            </thead>
                            <tbody class="text-14 font-medium divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($preview as $index => $row)
                                    @php $noUrut = $preview->firstItem() + $index; @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/2">
                                        <td class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">{{ $noUrut }}</td>
                                        <td class="px-5 py-3 text-center text-black dark:text-gray-300">{{ ucwords(strtolower($row->nama_dsn ?? '-')) }}
                                            <br><a class="font-mono">({{ $row->username }})</a>
                                        </td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->mv_mkul }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->mv_kelas }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->room_asal }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->room_ganti }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->mv_jamkul }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ tglID($row->mv_tgl_mkul) }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ tglID($row->mv_req_in) }}, {{ timeID($row->mv_req_in) }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ tglID($row->mv_req_update) }}, {{ timeID($row->mv_req_update) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">Tidak ada data pada periode ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                {{-- tb peminjaman --}}
                        <table class="w-full min-w-300 table-fixed">
                            <thead class="text-14 font-medium ">
                                <tr class="border-b border-gray-100 bg-gray-50/60 dark:border-gray-800 dark:bg-white/2">
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">NO</th>
                                    <th class="px-5 py-3 text-center text-black dark:text-gray-400">NAMA</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">PERANGKAT</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">MATA KULIAH</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">RUANGAN</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">JAM KULIAH</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">WAKTU PENGAJUAN</th>
                                    <th class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">WAKTU KEMBALI</th>
                                </tr>
                            </thead>
                            <tbody class="text-14 font-medium divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($preview as $index => $row)
                                    @php $noUrut = $preview->firstItem() + $index; @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/2">
                                        <td class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">{{ $noUrut }}</td>
                                        <td class="px-5 py-3 text-center text-black dark:text-white/90">{{ ucwords(strtolower($row->nama_mhs ?? $row->nama_dsn ?? '-')) }}
                                            <br><a class="font-mono">({{ $row->username }})</a>
                                        </td>
                                        <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ implode(', ', BarangList($row->barang_list)) }}</td>
                                        <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $row->brw_mkul }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->brw_room }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->brw_jamkul }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ tglID($row->brw_req_in) }}, {{ timeID($row->brw_req_in) }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ tglID($row->brw_req_update) }}, {{ timeID($row->brw_req_update) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">Tidak ada data pada periode ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                {{-- page-handle --}}
                    @if ($preview->hasPages())
                        <div class="flex items-center justify-between border-t border-gray-100 px-5 py-4 pt-4 dark:border-gray-800">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Menampilkan {{ $preview->firstItem() }}–{{ $preview->lastItem() }} dari {{ $preview->total() }} data
                            </p>
                            <div class="flex items-center gap-1">
                                @if ($preview->onFirstPage())
                                    <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Prev</span>
                                @else
                                    <a href="{{ $preview->previousPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Prev</a>
                                @endif

                                @php
                                    $current = $preview->currentPage();
                                    $last = $preview->lastPage();
                                @endphp

                                @if ($current > 2)
                                    <a href="{{ $preview->url(1) }}"
                                        class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                                        1
                                    </a>
                                @endif

                                @if ($current > 3)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif

                                @for ($page = max(1, $current - 1); $page <= min($last, $current + 1); $page++)
                                    @if ($page == $current)
                                        <span class="rounded-lg bg-blue-light-700 px-3 py-1.5 text-sm font-medium text-white">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $preview->url($page) }}"
                                            class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endfor

                                @if ($current < $last - 2)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif

                                @if ($current < $last - 1)
                                    <a href="{{ $preview->url($last) }}"
                                        class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                                        {{ $last }}
                                    </a>
                                @endif

                                @if ($preview->hasMorePages())
                                    <a href="{{ $preview->nextPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Next</a>
                                @else
                                    <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Next</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    {{-- script addition --}}
        <script>
            window.addEventListener('load', function () {
                window.initChartTren({
                    bulanLabel: @json($bulanLabel),
                    dataPeminjamanChart: @json($dataPeminjamanChart),
                    dataPemindahanChart: @json($dataPemindahanChart),
                    mingguLabel: @json($mingguLabel),
                    dataPeminjamanChartMingguan: @json($dataPeminjamanChartMingguan),
                    dataPemindahanChartMingguan: @json($dataPemindahanChartMingguan),
                });
            });
        </script>
@endsection
