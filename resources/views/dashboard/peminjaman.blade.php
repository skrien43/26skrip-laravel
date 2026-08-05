@extends('layouts.app')

@section('title', 'Dashboard Peminjaman')

@section('content')
    @php
        $stLbl = [
            'QUEUED'        => 'Dalam Antrian',
            'ACTIVE'        => 'Aktif',
            'DISCHARGED'    => 'Selesai'
        ];
        $currentYearMobile = null;
        $currentYearDesktop = null;
    @endphp
    <div class="mb-6">
        <p class="ml-2 text-18 sm:text-24 font-semibold text-blue-light-700 dark:text-gray-300">
            @if ($role === 'dsn')
                @php
                    $words = preg_split('/\s+/', trim(ucwords(strtolower($user->dosen->nama_dsn))));
                    $displayName = '';
                    if (count($words) >= 2) {
                        $displayName = $words[0] . ' ' . $words[1];
                        for ($i = 2; $i < count($words); $i++) {
                            $displayName .= ' ' . mb_substr($words[$i], 0, 1) . '.';
                        }
                    } elseif (count($words) === 1) {
                        $displayName = $words[0];
                    }
                @endphp
                <span id="selamat"></span>, {{ $displayName }}
            @else
                <span id="selamat"></span>, {{ implode(' ', array_slice(explode(' ', ucwords(strtolower($user->mahasiswa->nama_mhs))), 0, 2)) }}!
            @endif
        </p>
        <div class="mt-3">
            <h2 class="ml-2 text-16 sm:text-20 font-medium text-black dark:text-white/90">Dashboard Peminjaman Perangkat</h2>
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

    {{-- form btn & change to MV --}}
        <div class="mb-6 flex flex-wrap items-center gap-4">
            @if ($role === 'dsn')
                <a href="{{ route('dashboard.pemindahan') }}"
                    class="flex-1 rounded-lg border bg-blue-light-100 border-gray-200 px-4 py-2 text-center text-12 sm:text-14 font-medium text-gray-600 transition-colors duration-150 hover:text-blue-light-700 hover:border-blue-light-900 dark:hover:bg-white/35 dark:hover:text-blue-900 md:flex-none">
                    Pindah Ruangan
                </a>
            @endif
            <div class="{{ $role === 'dsn' ? 'flex-1 md:flex-none md:ml-auto' : 'ml-auto w-full md:w-auto' }}">
                @if (($brw_otp ?? '------') !== '------')
                    <span class="block w-full cursor-not-allowed rounded-lg bg-gray-300 px-4 py-2 text-center text-12 sm:text-14 font-medium text-gray-400 dark:bg-white/5 dark:text-gray-600">
                        PINJAM
                    </span>
                @else
                    <a href="{{ route('form.peminjaman') }}"
                        class="block w-full rounded-lg bg-blue-light-700 px-4 py-2 text-center text-12 sm:text-14 font-medium text-white transition-colors duration-150 hover:bg-blue-light-900">
                        PINJAM
                    </a>
                @endif
            </div>
        </div>

    {{-- brw active section --}}
        @if(($brw_otp ?? '------') !== '------')
            <div class="mb-6 rounded-2xl border border-warning-200 bg-warning-50 p-5 dark:border-warning-500/30 dark:bg-warning-500/10">
                @include('notification.info-brw', ['row' => $brw_active])
            </div>
        @else
            <div class="mb-6 text-12 sm:text-14 font-medium rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-white/3">
                Anda tidak memiliki peminjaman aktif.
            </div>        
        @endif

        <div class="mb-2 sm:mb-4 mt-4">
            <h2 class="ml-2 text-16 sm:text-20 font-medium text-black dark:text-white/90">Riwayat Peminjaman Perangkat</h2>
        </div>

    {{-- mobile viw --}}
        <div class="space-y-3 md:hidden">
            @forelse($history as $row)
                @php
                    $rowDate = $row->brw_status === 'QUEUED' ? $row->brw_req_in : $row->brw_req_update;
                    $rowYear = \Carbon\Carbon::parse($rowDate)->year;
                @endphp
                @if ($currentYearMobile !== $rowYear)
                    <div class="flex items-center gap-3 pt-2">
                        <span class="h-px flex-1 bg-gray-200 dark:bg-gray-800"></span>
                        <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">Tahun {{ $rowYear }}</span>
                        <span class="h-px flex-1 bg-gray-200 dark:bg-gray-800"></span>
                    </div>
                    @php $currentYearMobile = $rowYear; @endphp
                @endif

                <div class="ml-1 mr-1 text-14 font-medium rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/3">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-17 text-black dark:text-white/90">{{ $row->brw_mkul }}</p>
                        @php
                            $badgeColor = match($row->brw_status) {
                                'QUEUED' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
                                'ACTIVE' => 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-500',
                                'DISCHARGED' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                default => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
                            };
                        @endphp
                        <span class="shrink-0 rounded-full px-2.5 py-0.5 {{ $badgeColor }}">
                            {{ $stLbl[$row->brw_status] ?? '-' }}
                        </span>
                    </div>
                    <div class="mt-2 space-y-1 text-gray-500 dark:text-gray-400">
                        <p>Perangkat:</p>
                            <ol class="ml-4 list-decimal">
                                @forelse (BarangList($row->barang_list) as $item)
                                    <li>{{ $item }}</li>
                                @empty
                                    <li class="list-none">-</li>
                                @endforelse
                            </ol>
                        @if ($role === 'mhs')
                            <p>Dosen: {{ ucwords(strtolower($row->brw_pengajar ?? '-')) }}</p>
                        @endif
                        <p>Kampus {{ $row->brw_region }}</p>
                        <p>Ruangan: {{ $row->brw_room }} &ndash; Jam: {{ $row->brw_jamkul }}</p>
                        @if ($role === 'mhs')
                            <p>Jaminan: {{ $row->brw_jaminan }}</p>
                        @endif
                        <p class="pt-1 text-14 font-normal text-gray-500 dark:text-gray-500">
                            {{ tglID($rowDate) }}, pkl.{{ timeID($rowDate) }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-12 sm:text-14 font-medium rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-white/3">
                    Belum ada riwayat peminjaman perangkat.
                </div>
            @endforelse
        </div>

    {{-- desktop viw --}}
        <div class="hidden overflow-hidden rounded-2xl border border-gray-200 bg-white md:block dark:border-gray-800 dark:bg-white/3">
            <div class="overflow-x-auto">
                <table class="w-full min-w-225">
                    <thead class="text-14 font-medium">
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">NO</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">PERANGKAT</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">MATA KULIAH</th>
                            @if ($role === 'mhs')
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">DOSEN PENGAJAR</th>
                            @endif
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">RUANGAN</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">JAM KULIAH</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">LOKASI PEMINJAMAN</th>
                            @if ($role === 'mhs')
                                <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">JAMINAN</th>
                            @endif
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">TIMESTAMP</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="text-14 font-medium divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($history as $index => $row)
                            @php
                                $rowDate = $row->brw_status === 'QUEUED' ? $row->brw_req_in : $row->brw_req_update;
                                $rowYear = \Carbon\Carbon::parse($rowDate)->year;
                                $colCount = $role === 'mhs' ? 10 : 8;
                                $noUrut = $history->firstItem() + $index;
                            @endphp
                            @if ($currentYearDesktop !== $rowYear)
                                <tr>
                                    <td colspan="{{ $colCount }}" class="bg-gray-50 px-5 py-2 text-center text-xs font-semibold uppercase tracking-wide text-gray-400 dark:bg-white/2 dark:text-gray-500">
                                        &mdash; &mdash; Tahun {{ $rowYear }} &mdash; &mdash;
                                    </td>
                                </tr>
                                @php $currentYearDesktop = $rowYear; @endphp
                            @endif
                            <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-white/2">
                                <td class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">{{ $noUrut }}</td>
                                <td class="px-5 py-3 text-black dark:text-white/90">
                                    <ol class="ml-4 list-decimal">
                                        @forelse (BarangList($row->barang_list) as $item)
                                            <li>{{ $item }}</li>
                                        @empty
                                            <li class="list-none">-</li>
                                        @endforelse
                                    </ol>
                                </td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $row->brw_mkul }}</td>
                                @if ($role === 'mhs')
                                    <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ ucwords(strtolower($row->brw_pengajar ?? '-')) }}</td>
                                @endif
                                <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->brw_room }}</td>
                                <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->brw_jamkul }}</td>
                                <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">Kampus {{ $row->brw_region }}</td>
                                @if ($role === 'mhs')
                                    <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ $row->brw_jaminan }}</td>
                                @endif
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
                                        {{ $stLbl[$row->brw_status] ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">Belum ada riwayat peminjaman perangkat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($history->hasPages())
                <div class="mt-4 flex items-center justify-between border-t border-gray-100 px-2 pt-4 dark:border-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan {{ $history->firstItem() }}–{{ $history->lastItem() }} dari {{ $history->total() }} data
                    </p>
                    <div class="flex items-center gap-1">
                        {{-- Previous --}}
                        @if ($history->onFirstPage())
                            <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Prev</span>
                        @else
                            <a href="{{ $history->previousPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Prev</a>
                        @endif

                        {{-- Page numbers --}}
                        @php
                            $current = $history->currentPage();
                            $last = $history->lastPage();
                        @endphp

                        {{-- Halaman pertama --}}
                        @if ($current > 2)
                            <a href="{{ $history->url(1) }}"
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
                                <a href="{{ $history->url($page) }}"
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
                            <a href="{{ $history->url($last) }}"
                                class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                                {{ $last }}
                            </a>
                        @endif
                        
                        {{-- Next --}}
                        @if ($history->hasMorePages())
                            <a href="{{ $history->nextPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Next</a>
                        @else
                            <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    {{-- logout confirm --}}
        <form id="logout-dash" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <script>
            history.pushState(null, "", location.href);
            window.addEventListener("popstate", function () {
                const keluar = confirm("Anda yakin ingin logout dari sistem?");
                if (keluar) {
                    document.getElementById("logout-dash").submit();
                } else {
                    history.pushState(null, "", location.href);
                }
            });
        </script>
@endsection
