@extends('layouts.app')

@section('title', 'Admin | Manajemen Ruangan')

@section('content')
    <script>
        const APP_URL = {
            roomSchedule: "{{ route('room.schedule') }}",
            listJam: @json($listJam),
        };
    </script>
    <div class="mb-6">
        <p class="ml-2 text-24 font-semibold text-blue-light-700 dark:text-gray-300">
            <span id="selamat"></span>, Admin {{ ucwords($user->admin->nama_adm) }}!
        </p>
        <div class="mt-3">
            <h2 class="ml-3 text-20 font-semibold text-black dark:text-gray-300">Manajemen Ruangan Kelas</h2>
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
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6">
    {{-- count used --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
            @foreach ($room_emp as $loc => $data)
                <div class="flex items-center gap-3 {{ !$loop->first ? 'mt-6' : '' }}">
                    <div class="flex items-center justify-center w-12 h-12 bg-warning-100 rounded-xl dark:bg-gray-800">
                        <svg class="fill-warning-500" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM16.59 16.59L11 13V7H12.5V12.25L17.5 15.15L16.59 16.59Z" fill="" />
                        </svg>
                    </div>
                    <h4 class="text-16 font-medium text-gray-500 dark:text-gray-400">
                        Total Ruangan Kelas Digunakan
                    </h4>
                </div>
                <p class="ml-2 mt-3 mb-4 text-16 font-medium text-gray-600 dark:text-white/90">
                    Jam perkuliahan ke-
                    @if ($jamNow)
                        {{ $jamNow }} ({{ $listJam[$jamNow] }})
                    @else
                        x (--:--)
                    @endif
                </p>
                <div class="flex flex-wrap gap-3">
                    <div class="w-full rounded-xl bg-gray-50 px-3 py-2 text-center dark:bg-white/3">
                        <p class="mt-1 text-title-sm font-bold text-warning-500">
                            {{ $data['terisi'] > 0 ? $data['terisi'] : '0' }}<span class="text-14 font-medium text-gray-500 dark:text-white/75">/{{ $data['total'] }}</span>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    {{-- count empty --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
            @foreach ($room_emp as $loc => $data)
                <div class="flex items-center gap-3 {{ !$loop->first ? 'mt-6' : '' }}">
                    <div class="flex items-center justify-center w-12 h-12 bg-success-100 rounded-xl dark:bg-gray-800">
                        <svg class="fill-success-600 dark:fill-success-400" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.4" d="M8 3a2 2 0 0 0-2 2v1H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-1V5a2 2 0 0 0-2-2H8z"/>
                            <path d="M9 3h6a1 1 0 0 1 1 1v2H8V4a1 1 0 0 1 1-1z"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16 12.7l-4.3 4.3-2.4-2.4a1 1 0 1 1 1.4-1.4l1 1 3.6-3.6a1 1 0 1 1 1.4 1.4z"/>
                        </svg>
                    </div>
                    <h4 class="text-16 font-medium text-gray-500 dark:text-gray-400">
                        Total Ruangan Kelas Kosong
                    </h4>
                </div>
                <p class="ml-2 mt-3 mb-4 text-16 font-medium text-gray-600 dark:text-white/90">
                    Jam perkuliahan ke-
                    @if ($jamNow)
                        {{ $jamNow }} ({{ $listJam[$jamNow] }})
                    @else
                        x (--:--)
                    @endif
                </p>
                <div class="flex flex-wrap gap-3">
                    <div class="w-full rounded-xl bg-gray-50 px-3 py-2 text-center dark:bg-white/3">
                        <p class="mt-1 text-title-sm font-bold text-success-600 dark:text-success-500">
                            {{ $data['kosong'] > 0 ? $data['kosong'] : '0' }}<span class="text-14 font-medium text-gray-500 dark:text-white/75">/{{ $data['total'] }}</span>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- table --}}
        <div class="mt-5 overflow-hidden rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/3">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                <form method="GET" class="w-full max-w-70">
                    <div class="relative flex items-center gap-2">
                        <div class="relative flex-1">
                            <input type="text" name="search_room" value="{{ request('search_room') }}" placeholder="Cari ruangan..."
                                class="h-10 w-full rounded-lg border border-gray-200 bg-transparent pl-9 pr-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                        </div>
                        @if (request('search_room'))
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
            <div class="overflow-x-auto">
                <table class="w-full min-w-175">
                    <thead class="text-14">
                        <tr class="border-b border-gray-100 bg-gray-50/60 dark:border-gray-800 dark:bg-white/2">
                            <th rowspan="2" class="px-5 py-3 text-center align-middle text-gray-500 dark:text-gray-400">NO</th>
                            <th rowspan="2" class="px-5 py-3 text-center align-middle text-gray-500 dark:text-gray-400">RUANGAN</th>
                            <th rowspan="2" class="px-5 py-3 text-center align-middle text-gray-500 dark:text-gray-400">LANTAI</th>
                            <th colspan="2" class="border-b border-gray-100 px-5 py-3 text-center text-gray-500 dark:border-gray-800 dark:text-gray-400">BUILT-IN FACILITY</th>
                            <th rowspan="2" class="px-5 py-3 text-center align-middle text-gray-500 dark:text-gray-400">AKSI</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50/60 dark:border-gray-800 dark:bg-white/2">
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">KARTU RUANGAN</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400" title="Mounted Proyektor">PROYEKTOR</th>
                        </tr>
                    </thead>
                    <tbody class="text-14 font-medium divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($rooms as $index => $row)
                            @php $noUrut = $rooms->firstItem() + $index; @endphp
                            <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-white/2">
                                <td class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">{{ $noUrut }}</td>
                                <td class="px-5 py-3 text-center text-black dark:text-white/90">{{ $row->room_name }}</td>
                                <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->room_floor }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span class="font-semibold {{ $row->pac ? 'text-success-600' : 'text-error-600' }}">
                                        {{ $row->pac ? '✓ Ada' : '✗ Tidak Ada' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="font-semibold {{ $row->vwr ? 'text-success-600' : 'text-error-600' }}">
                                        {{ $row->vwr ? '✓ Ada' : '✗ Tidak Ada' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center text-13">
                                    <button type="button" onclick="toggleRoomSchedule('{{ $row->room_name }}', this)"
                                        data-room="{{ $row->room_name }}"
                                        class="btn-detail-room rounded-lg border border-gray-200 px-3 py-1.5 font-medium text-gray-600 transition-colors duration-150 hover:border-blue-light-300 hover:bg-blue-light-50 hover:text-blue-light-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-blue-light-500/10">
                                        DETAIL
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">Belum ada data ruangan kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        {{-- page handle --}}
            @if ($rooms->hasPages())
                <div class="flex items-center justify-between border-t border-gray-100 px-5 py-4 pt-4 dark:border-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan {{ $rooms->firstItem() }}–{{ $rooms->lastItem() }} dari {{ $rooms->total() }} data
                    </p>
                    <div class="flex items-center gap-1">
                        @if ($rooms->onFirstPage())
                            <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Prev</span>
                        @else
                            <a href="{{ $rooms->previousPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Prev</a>
                        @endif

                        @php
                            $current = $rooms->currentPage();
                            $last = $rooms->lastPage();
                        @endphp

                        @if ($current > 2)
                            <a href="{{ $rooms->url(1) }}"
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
                                <a href="{{ $rooms->url($page) }}"
                                    class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        @if ($current < $last - 2)
                            <span class="px-2 text-gray-400">...</span>
                        @endif

                        @if ($current < $last - 1)
                            <a href="{{ $rooms->url($last) }}"
                                class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                                {{ $last }}
                            </a>
                        @endif

                        @if ($rooms->hasMorePages())
                            <a href="{{ $rooms->nextPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Next</a>
                        @else
                            <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    {{-- manual insert sch --}}
        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
            <form method="POST" action="{{ route('room.update') }}">
                @csrf
                <div class="mt-1.5 mb-5 text-26 font-semibold text-center text-gray-800 dark:text-white/90">
                    <h3 id="room-title-admin">
                        Ruangan
                    </h3>
                    <input type="hidden" id="nama_ruang" name="nama_ruang">
                </div>
                <div class="flex flex-1 items-end gap-4">
                    <div class="flex-1">
                        <label class="ml-1.5 mb-1.5 block text-15 font-medium text-gray-700 dark:text-gray-300">Hari</label>
                        <input id="hari_jadwal" name="hari_jadwal" readonly disabled
                            class="h-11 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm text-gray-700 dark:border-gray-800 dark:bg-white/5 dark:text-gray-300 cursor-not-allowed">
                    </div>
                    <div class="flex-1">
                        <label class="ml-1.5 mb-1.5 block text-15 font-medium text-gray-700 dark:text-gray-300">Jam</label>
                        <input id="jam_jadwal" name="jam_jadwal" readonly disabled
                            class="h-11 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm text-gray-700 dark:border-gray-800 dark:bg-white/5 dark:text-gray-300 cursor-not-allowed">
                    </div>
                    <div class="flex-1">
                        <label class="ml-1.5 mb-1.5 block text-15 font-medium text-gray-700 dark:text-gray-300">Status Ruangan</label>
                        <select id="isFilled_jadwal" name="isFilled_jadwal" required
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                            <option value="" disabled selected>Pilih status ruangan</option>
                            <option value="READY">READY</option>
                            <option value="USED">USED</option>
                        </select>
                    </div>
                    <button type="button" onclick="refreshPage()"
                        title="Refresh halaman"
                        class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg px-3 text-15 font-medium transition-colors duration-150 bg-blue-light-700 text-white hover:bg-blue-light-900">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="hidden sm:inline">Refresh</span>
                    </button>
                </div>
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label class="ml-1.5 mb-1.5 block text-15 font-medium text-gray-700 dark:text-gray-300">Dosen/Penanggung Jawab</label>
                        <input id="dosen_jadwal" name="dosen_jadwal" placeholder="Masukkan nama..."
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="ml-1.5 mb-1.5 block text-15 font-medium text-gray-700 dark:text-gray-300">Mata Kuliah/Kegiatan</label>
                        <input id="matkul_jadwal" name="matkul_jadwal" placeholder="Masukkan matkul/kegiatan..."
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="ml-1.5 mb-1.5 block text-15 font-medium text-gray-700 dark:text-gray-300">Kelas/Pengguna</label>
                        <input id="kelas_jadwal" name="kelas_jadwal" placeholder="Masukkan kelas/pengguna..."
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                    </div>
                </div>
                <p id="grid_cld" class="-mb-2 mt-8 text-center text-13 font-normal italic text-gray-400">
                    *Tekan DETAIL untuk melihat kalender jadwal pada masing-masing ruangan dari tabel di atas,<br>
                    tekan kembali untuk mengosongkan kalender jadwal
                </p>
    {{-- kalender jadwal --}}
                <div class="mt-8 overflow-x-auto">
                    <div class="mx-auto w-full max-w-250 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-7 gap-px bg-gray-200 text-center dark:bg-gray-700">
                            <div class="bg-gray-100 dark:bg-gray-800"></div>
                            @foreach ($hari as $namaHari)
                                <div class="bg-gray-100 py-2.5 text-14 font-semibold uppercase tracking-wide text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    {{ $namaHari }}
                                </div>
                            @endforeach
                            @for ($i = 0; $i < 10; $i++)
                                <div class="flex items-center justify-center text-18 font-semibold bg-gray-50 text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                                    {{ $i + 1 }}
                                </div>
                                @for ($j = 0; $j < 6; $j++)
                                    <div id="{{ $hari[$j] }}_{{ $i + 1 }}" onclick="descJadwal(this.id)"
                                        class="jadwal-cell h-12 cursor-pointer bg-white transition-colors duration-150 hover:bg-blue-light-50 hover:ring-1 hover:ring-inset hover:ring-blue-light-300 dark:bg-gray-900 dark:hover:bg-blue-light-500/10 dark:hover:ring-blue-light-500/40"></div>
                                @endfor
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                    class="mt-4 w-50 rounded-lg bg-blue-light-700 px-4 py-2.5 text-14 font-medium text-white transition-colors duration-150 hover:bg-success-600">
                    SAVE
                </button>
                </div>
            </form>
        </div>
@endsection
