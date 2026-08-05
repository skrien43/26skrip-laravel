<div>
    <div class="mb-4 flex items-center justify-between">
        <h4 class="ml-3 mt-4 text-18 font-semibold text-black dark:text-gray-300">Data Peminjaman Dosen</h4>
        <button type="button" onclick="refreshPage()"
            title="Refresh halaman"
            class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-15 font-medium transition-colors duration-150 bg-blue-light-700 text-white hover:bg-blue-light-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span class="hidden sm:inline">Refresh</span>
        </button>
    </div>
    {{-- req-brw --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-warning-100 rounded-xl dark:bg-gray-800">
                        <svg class="fill-warning-500" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z" fill="" />
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-500 dark:text-gray-400">Pengajuan Peminjaman</span>
                </div>
                <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-warning-500">
                    {{ ($dsn_pending ?? 0) > 0 ? $dsn_pending : '-' }}
                </h4>
            </div>
    {{-- active-brw --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-warning-100 rounded-xl dark:bg-gray-800">
                        <svg class="fill-warning-500" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM16.59 16.59L11 13V7H12.5V12.25L17.5 15.15L16.59 16.59Z" fill="" />
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-500 dark:text-gray-400">Peminjaman Aktif</span>
                </div>
                <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-warning-500">
                    {{ ($dsn_aktif ?? 0) > 0 ? $dsn_aktif : '-' }}
                </h4>
            </div>
    {{-- fin-brw --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-success-100 rounded-xl dark:bg-gray-800">
                        <svg class="fill-success-600 dark:fill-success-400 stroke-success-600 dark:stroke-success-400" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="" />
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-500 dark:text-gray-400">Peminjaman Selesai</span>
                </div>
                <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-success-600 dark:text-success-500">
                    {{ ($dsn_selesai ?? 0) > 0 ? $dsn_selesai : '-' }}
                </h4>
            </div>
    {{-- all-brw --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-light-100 rounded-xl dark:bg-gray-800 shrink-0">
                        <svg class="fill-blue-light-900 dark:fill-blue-light-600" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 12H7v-2h10v2zm0-4H7V9h10v2z" fill="" />
                        </svg>
                    </div>
                    <span class="text-16 font-medium text-gray-500 dark:text-gray-400">Total Pengajuan</span>
                </div>
                <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-blue-light-800 dark:text-blue-light-600">
                    {{ ($dsn_total ?? 0) > 0 ? $dsn_total : '-' }}
                </h4>
            </div>
        </div>

    {{-- table --}}
        <div class="mt-6 min-w-0 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                <form method="GET" class="w-full max-w-70">
                    <div class="relative flex items-center gap-2">
                        <div class="relative flex-1">
                            <input type="text" name="search_dsn" value="{{ request('search_dsn') }}" placeholder="Cari OTP..."
                                class="h-10 w-full rounded-lg border border-gray-200 bg-transparent pl-9 pr-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                        </div>
                        @if (request('search_dsn'))
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
                <table class="w-max min-w-300">
                    <thead class="text-14">
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">NO</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">OTP</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">NAMA</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">PERANGKAT</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">MATA KULIAH</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">RUANGAN</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">JAM KULIAH</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">TIMESTAMP</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">STATUS</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-14 font-medium divide-y divide-gray-100 dark:divide-gray-800">
                        @php $currentYearDsn = null; @endphp
                        @forelse($brw_dsn as $index => $row)
                            @php
                                $rowDate = $row->brw_status === 'QUEUED' ? $row->brw_req_in : $row->brw_req_update;
                                $rowYear = \Carbon\Carbon::parse($rowDate)->year;
                                $noUrut = $brw_dsn->firstItem() + $index;
                            @endphp
                            @if ($currentYearDsn !== $rowYear)
                                <tr>
                                    <td colspan="10" class="bg-gray-50 px-5 py-2 text-center text-12 font-semibold uppercase tracking-wide text-blue-light-700 dark:bg-white/2 dark:text-blue-light-500">
                                        &mdash; &mdash; Tahun {{ $rowYear }} &mdash; &mdash;
                                    </td>
                                </tr>
                                @php $currentYearDsn = $rowYear; @endphp
                            @endif

                            <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-white/2">
                                <td class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">{{ $noUrut }}</td>
                                <td class="px-5 py-3 text-center text-16 text-black dark:text-white/90 font-mono">{{ $row->brw_otp }}</td>
                                <td class="px-5 py-3 text-center text-black dark:text-white/90">{{ ucwords(strtolower($row->nama_dsn)) }}
                                    <br><a class="font-mono">({{ $row->kode_dsn }})</a>
                                </td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-400">
                                    <ol class="ml-4 list-decimal">
                                        @forelse (BarangList($row->barang_list) as $item)
                                            <li>{{ $item }}</li>
                                        @empty
                                            <li class="list-none">-</li>
                                        @endforelse
                                    </ol>
                                </td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $row->brw_mkul }}</td>
                                <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ ucwords($row->brw_room) }}</td>
                                <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->brw_jamkul }}</td>
                                <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">
                                    {{ tglID($rowDate) }}, {{ timeID($rowDate) }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @php
                                        $badgeColor = match($row->brw_status) {
                                            'QUEUED' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
                                            'ACTIVE' => 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-500',
                                            'DISCHARGED' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                            default => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full py-0.5 px-2.5 {{ $badgeColor }}">
                                        {{ $stLbl[$row->brw_status] ?? $row->brw_status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center text-12">
                                    @if ($row->brw_status === 'QUEUED')
                                        <button class="btn-acc rounded-lg border border-gray-200 px-3 py-1.5 text-gray-600 transition-colors duration-150 hover:border-blue-light-300 hover:bg-blue-light-50 hover:text-blue-light-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-blue-light-500/10" data-id="{{ $row->brw_id }}">TERIMA</button>
                                    @elseif ($row->brw_status === 'ACTIVE')
                                        <button class="btn-selesai rounded-lg border border-gray-200 px-3 py-1.5 text-gray-600 transition-colors duration-150 hover:border-success-300 hover:bg-success-50 hover:text-success-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-success-500/10" data-id="{{ $row->brw_id }}">SELESAI</button>
                                    @elseif ($row->brw_status === 'DISCHARGED' && $row->brw_otp === '------')
                                        <form id="form-delete-brw-{{ $row->brw_id }}" action="{{ route('brw.destroy', $row->brw_id) }}" method="POST" style="display:inline-block"
                                            onsubmit="return confirm('Yakin hapus data peminjaman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-lg border border-red-200 px-3 py-1.5 text-red-600 transition-colors duration-150 hover:bg-red-50 dark:border-red-900 dark:hover:bg-red-500/10">
                                                HAPUS
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" disabled class="rounded-lg border border-gray-100 px-3 py-1.5 text-gray-300 dark:border-gray-800 dark:text-gray-600">SELESAI</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">Belum ada data peminjaman Dosen.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    {{-- page handle --}}
            @if ($brw_dsn->hasPages())
                <div class="flex items-center justify-between border-t border-gray-100 px-5 py-4 pt-4 dark:border-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan {{ $brw_dsn->firstItem() }}–{{ $brw_dsn->lastItem() }} dari {{ $brw_dsn->total() }} data
                    </p>
                    <div class="flex items-center gap-1">
                        {{-- Previous --}}
                        @if ($brw_dsn->onFirstPage())
                            <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Prev</span>
                        @else
                            <a href="{{ $brw_dsn->previousPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Prev</a>
                        @endif

                        {{-- Page numbers --}}
                        @php
                            $current = $brw_dsn->currentPage();
                            $last = $brw_dsn->lastPage();
                        @endphp

                        {{-- Halaman pertama --}}
                        @if ($current > 2)
                            <a href="{{ $brw_dsn->url(1) }}"
                                class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                                1
                            </a>
                        @endif

                        {{-- Titik-titik kiri --}}
                        @if ($current > 3)
                            <span class="px-2 text-gray-400">...</span>
                        @endif

                        {{-- Halaman sekitar current --}}
                        @for ($page = max(1, $current - 1); $page <= min($last, $current + 1); $page++)
                            @if ($page == $current)
                                <span class="rounded-lg bg-blue-light-700 px-3 py-1.5 text-sm font-medium text-white">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $brw_dsn->url($page) }}"
                                    class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        {{-- Titik-titik kanan --}}
                        @if ($current < $last - 2)
                            <span class="px-2 text-gray-400">...</span>
                        @endif

                        {{-- Halaman terakhir --}}
                        @if ($current < $last - 1)
                            <a href="{{ $brw_dsn->url($last) }}"
                                class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                                {{ $last }}
                            </a>
                        @endif

                        {{-- Next --}}
                        @if ($brw_dsn->hasMorePages())
                            <a href="{{ $brw_dsn->nextPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Next</a>
                        @else
                            <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
</div>
