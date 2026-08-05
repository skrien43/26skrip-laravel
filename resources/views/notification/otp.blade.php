@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
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
    <div class="mx-auto max-w-lg">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center dark:border-gray-800 dark:bg-white/3 md:p-8">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-success-50 dark:bg-success-500/15">
                <svg class="text-success-600 dark:text-success-500" width="28" height="28" viewBox="0 0 24 24" fill="none">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-24 font-semibold text-gray-800 dark:text-white/90">Success</h2>
            <p class="mt-1 text-15 text-gray-500 dark:text-gray-400">
                Data peminjaman perangkat Anda berhasil tercatat pada sistem.
            </p>
            <p class="mt-6 text-18 font-semibold text-gray-500 dark:text-gray-400">Kode OTP:</p>
            <div id="otp-display" class="mt-2 flex justify-center gap-2">
                @foreach (str_split($brw->brw_otp) as $digit)
                    <div
                        class="text-22 flex h-14 w-14 items-center justify-center rounded-xl border-2 border-brand-200 bg-white font-bold
                        tracking-wider text-blue-light-600 shadow-sm dark:border-brand-700 dark:bg-gray-900 dark:text-blue-light-400">
                        {{ $digit }}
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="copyOTP()"
                class="mt-3 text-16 font-medium text-blue-light-600">
                Salin OTP
            </button>
            <p class="mt-6 text-15 text-justify text-gray-500 dark:text-gray-400">
                <strong class="text-gray-700 dark:text-gray-300">Simpan</strong> dan
                <strong class="text-gray-700 dark:text-gray-300">tunjukkan</strong> kode di atas kepada staf Sekretariat Dosen
                saat Anda <strong class="text-gray-700 dark:text-gray-300">mengambil</strong> dan
                <strong class="text-gray-700 dark:text-gray-300">mengembalikan</strong>
                perangkat di ruang Sekretariat Dosen. Terima Kasih!
            </p>
            <table class="mt-6 w-full text-left text-15">
                <tr>
                    <td class="py-1.5 text-gray-500 dark:text-gray-400">Mata Kuliah</td>
                    <td class="py-1.5 font-medium text-gray-800 dark:text-white/90">{{ $brw->brw_mkul }}</td>
                </tr>
                <tr>
                    <td class="py-1.5 text-gray-500 dark:text-gray-400">Ruangan</td>
                    <td class="py-1.5 font-medium text-gray-800 dark:text-white/90">{{ $brw->brw_room }}</td>
                </tr>
                <tr>
                    <td class="py-1.5 text-gray-500 dark:text-gray-400">Jam</td>
                    <td class="py-1.5 font-medium text-gray-800 dark:text-white/90">{{ $brw->brw_jamkul }}</td>
                </tr>
                <tr>
                    <td class="py-1.5 text-gray-500 dark:text-gray-400">Lokasi</td>
                    <td class="py-1.5 font-medium text-gray-800 dark:text-white/90">Kampus {{ $brw->brw_region }}</td>
                </tr>
                <tr>
                    <td class="py-1.5 text-gray-500 dark:text-gray-400">Status</td>
                    <td class="py-1.5">
                        <span class="inline-flex items-center rounded-full bg-warning-50 px-2.5 py-0.5 font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">
                            {{ $brw->brw_status === 'QUEUED' ? 'Dalam Antrian' : $brw->brw_status }}
                        </span>
                    </td>
                </tr>
            </table>
            <a href="{{ route('dashboard.peminjaman') }}"
                class="mt-8 block w-full rounded-lg bg-blue-light-700 py-3 text-center text-16 font-medium text-white transition-colors duration-150 hover:bg-blue-light-900">
                BACK
            </a>
        </div>
    </div>
@endsection
