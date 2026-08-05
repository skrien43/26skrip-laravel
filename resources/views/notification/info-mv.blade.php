<div>
    <div class="mb-3 flex items-center justify-between">
        @php
            $badgeColorMV = match($row->mv_status) {
                'QUEUED'      => 'bg-warning-600/25 text-warning-800 dark:bg-warning-500/15 dark:text-warning-500',
                'CONFIRMED'   => 'bg-blue-light-600/25 text-blue-light-800 dark:bg-blue-light-500/15 dark:text-blue-light-500',
                'DISCHARGED'  => 'bg-success-600/25 text-success-800 dark:bg-success-500/15 dark:text-success-500',
                'VOIDED'      => 'bg-error-600/25 text-error-800 dark:bg-error-500/15 dark:text-error-500',
                default       => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
            };
        @endphp
        <h3 class="text-16 font-semibold text-warning-700 dark:text-warning-500">Status:
            <span class="inline-flex items-center rounded-full py-0.5 px-2.5 {{ $badgeColorMV }}">
                {{ $stLbl[$row->mv_status] ?? $row->mv_status }}
            </span>
        </h3>
    </div>

    <div class="text-justify text-14 font-medium text-warning-700 dark:text-warning-400">
        <p>
            Saat ini Anda memiliki pemindahan ruangan aktif untuk mata kuliah <strong>{{ $row->mv_mkul }}</strong>,
            kelas <strong>{{ $row->mv_kelas }}</strong>, pada tanggal perkuliahan <strong> {{ tglID($row->mv_tgl_mkul) }} {{ now()->year }}</strong>
            jam ke-<strong> {{ \App\Helpers\JadwalHelper::formatJamKul($row->mv_jamkul) }} </strong>.
        </p>
        <p class="mt-2">
            Ruangan awal: <strong>{{ ucwords($row->room_asal ?? '-') }}</strong><br>
            Ruangan ganti: <strong>{{ ucwords($row->room_ganti ?? '-') }}</strong><br>
            <strong>Alasan:</strong><br>{{ $row->mv_reason }}
        </p>
        @if ($row->mv_status === 'QUEUED')
        <p class="mt-2">
            Pengajuan Anda sedang <strong>menunggu persetujuan</strong> dari staf Sekretariat Dosen.
        </p>
        @elseif ($row->mv_status === 'CONFIRMED')
        <p class="mt-2">
            Pengajuan Anda telah <strong>disetujui</strong>.
        </p>
        @endif
        <p>
            Harap hubungi staf Sekretariat Dosen untuk menyelesaikan pindah ruangan/reset ruangan,
            agar dapat mengajukan pemindahan ruangan kembali.
        </p>

        <p class="mt-2">Terima kasih!</p>
    </div>

    <p class="mt-4 text-right text-14 font-medium text-warning-600 dark:text-warning-500">
        TTD.<br>
        Staf Sekretariat Dosen
    </p>
</div>
