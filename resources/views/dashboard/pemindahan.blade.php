@extends('layouts.app')

@section('title', 'Dashboard Pemindahan')

@section('content')
    @php
        $stLbl = [
            'QUEUED'       => 'Menunggu Persetujuan',
            'CONFIRMED'    => 'Disetujui',
            'VOIDED'       => 'Ditolak',
            'DISCHARGED'   => 'Selesai'
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
            <h2 class="ml-2 text-16 sm:text-20 font-medium text-black dark:text-white/90">Dashboard Pemindahan Ruangan</h2>
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
    {{-- form btn & change to BRW --}}
        <div class="mb-6 flex flex-wrap items-center gap-4">
            <a href="{{ route('dashboard.peminjaman') }}"
                class="flex-1 rounded-lg border bg-blue-light-100 border-gray-200 px-4 py-2 text-center text-12 sm:text-14 font-medium text-gray-600 transition-colors duration-150 hover:text-blue-light-700 hover:border-blue-light-900 dark:hover:bg-white/35 dark:hover:text-blue-900 md:flex-none">
                Pinjam Perangkat
            </a>
            @if (in_array($mv_active->mv_status ?? ['DISCHARGED', 'VOIDED'], ['QUEUED', 'CONFIRMED']))
                <span
                    class="flex-1 cursor-not-allowed rounded-lg bg-gray-300 px-4 py-2 text-center text-12 sm:text-14 font-medium text-gray-400 dark:bg-white/5 dark:text-gray-600 md:flex-none md:ml-auto">
                    PINDAH
                </span>
            @else
                <a href="{{ route('form.pemindahan') }}"
                    class="flex-1 rounded-lg bg-blue-light-700 px-4 py-2 text-center text-12 sm:text-14 font-medium text-white transition-colors duration-150 hover:bg-blue-light-900 md:flex-none md:ml-auto">
                    PINDAH
                </a>
            @endif
        </div>

    {{-- mv active section --}}
        @if ($showSuccessMV)
            <div class="mb-6 rounded-2xl border border-success-200 bg-success-50 p-5 dark:border-success-500/30 dark:bg-success-500/10">
                <p class="text-12 sm:text-14 font-medium text-success-700 dark:text-success-500">
                    Pengajuan pemindahan ruangan berhasil dikirim! Menunggu persetujuan staf Sekretariat Dosen.
                </p>
            </div>
        @endif
        @if ($role === 'dsn' && $mv_active)
            <div class="mb-6 rounded-2xl border border-warning-200 bg-warning-50 p-5 dark:border-warning-500/30 dark:bg-warning-500/10">
                @include('notification.info-mv', ['row' => $mv_active])
            </div>
        @else
            <div class="mb-6 text-12 sm:text-14 font-medium rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-white/3">
                Anda tidak memiliki pemindahan aktif.
            </div>        
        @endif

        <div class="mb-2 sm:mb-4 mt-4">
            <h2 class="ml-2 text-16 sm:text-20 font-medium text-black dark:text-white/90">Riwayat Pemindahan Ruangan</h2>
        </div>
    {{-- mobile viw --}}
        <div class="space-y-3 md:hidden">
            @forelse($historyMV as $row)
                @php
                    $rowDate = $row->mv_status === 'QUEUED' ? $row->mv_req_in : $row->mv_req_update;
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
                        <p class="text-17 text-gray-800 dark:text-white/90">{{ $row->mv_mkul }}</p>
                        @php
                            $badgeColor = match($row->mv_status) {
                                'QUEUED' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
                                'CONFIRMED' => 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-500',
                                'DISCHARGED' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                'VOIDED' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                default => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
                            };
                        @endphp
                        <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeColor }}">
                            {{ $stLbl[$row->mv_status] }}
                        </span>
                    </div>
                    <div class="mt-2 space-y-1 text-gray-500 dark:text-gray-400">
                        <p>Kelas: {{ ucwords($row->mv_kelas) }}</p>
                        <p>Ruangan Awal: {{ ucwords($row->room_id) }}</p>
                        <p>Ruangan Ganti: {{ ucwords($row->room_replace) }}</p>
                        <p>Jam: {{ $row->mv_jamkul }} &middot; Tanggal: {{ tglID($row->mv_tgl_mkul) }}</p>
                        <p class="pt-1 text-14 font-normal text-gray-500 dark:text-gray-500">
                            {{ tglID($rowDate) }}, pkl.{{ timeID($rowDate) }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-12 font-medium rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-white/3">
                    Belum ada riwayat pemindahan ruangan.
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
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">MATA KULIAH</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">KELAS</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">RUANGAN AWAL</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">RUANGAN GANTI</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">JAM KULIAH</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">TANGGAL KULIAH</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">TIMESTAMP</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="text-14 font-medium divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($historyMV as $index => $row)
                            @php 
                                $rowYear = \Carbon\Carbon::parse($row->mv_tgl_mkul)->year;
                                $noUrut = $historyMV->firstItem() + $index;
                            @endphp
                            @if ($currentYearDesktop !== $rowYear)
                                <tr>
                                    <td colspan="9" class="bg-gray-50 px-5 py-2 text-center text-xs font-semibold uppercase tracking-wide text-gray-400 dark:bg-white/2 dark:text-gray-500">
                                        &mdash; &mdash; Tahun {{ $rowYear }} &mdash; &mdash;
                                    </td>
                                </tr>
                                @php $currentYearDesktop = $rowYear; @endphp
                            @endif
                            <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-white/2">
                                <td class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">{{ $noUrut }}</td>
                                <td class="px-5 py-3 text-black dark:text-white/90">{{ $row->mv_mkul }}</td>
                                <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ ucwords($row->mv_kelas) }}</td>
                                <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ ucwords($row->room_id) }}</td>
                                <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400">{{ ucwords($row->room_replace) }}</td>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">Belum ada riwayat pemindahan ruangan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        {{-- page handle --}}
            @if ($historyMV->hasPages())
                <div class="mt-4 flex items-center justify-between border-t border-gray-100 px-2 pt-4 dark:border-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan {{ $historyMV->firstItem() }}–{{ $historyMV->lastItem() }} dari {{ $historyMV->total() }} data
                    </p>
                    <div class="flex items-center gap-1">
                        {{-- Previous --}}
                        @if ($historyMV->onFirstPage())
                            <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Prev</span>
                        @else
                            <a href="{{ $historyMV->previousPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Prev</a>
                        @endif

                        {{-- Page numbers --}}
                        @php
                            $current = $historyMV->currentPage();
                            $last = $historyMV->lastPage();
                        @endphp

                        {{-- Halaman pertama --}}
                        @if ($current > 2)
                            <a href="{{ $historyMV->url(1) }}"
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
                                <a href="{{ $historyMV->url($page) }}"
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
                            <a href="{{ $historyMV->url($last) }}"
                                class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                                {{ $last }}
                            </a>
                        @endif

                        {{-- Next --}}
                        @if ($historyMV->hasMorePages())
                            <a href="{{ $historyMV->nextPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Next</a>
                        @else
                            <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
@endsection
