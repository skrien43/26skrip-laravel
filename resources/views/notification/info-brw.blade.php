<div>
    <div class="mb-3 flex items-center justify-between">
        @php
            $badgeColor = match($row->brw_status) {
                'QUEUED'      => 'bg-warning-600/25 text-warning-800 dark:bg-warning-500/15 dark:text-warning-500',
                'ACTIVE'      => 'bg-blue-light-600/25 text-blue-light-800 dark:bg-blue-light-500/15 dark:text-blue-light-500',
                'DISCHARGED'  => 'bg-success-600/25 text-success-800 dark:bg-success-500/15 dark:text-success-500',
                default       => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
            };
        @endphp
        <h3 class="text-16 font-semibold text-warning-700 dark:text-warning-500">Status: 
            <p class="inline-flex items-center rounded-full py-0.5 px-2.5 {{ $badgeColor }}">
                {{ $stLbl[$row->brw_status] ?? $row->brw_status }}
            </p>
        </h3>
        <h3 class="text-16 font-mono font-bold text-black dark:text-white">
            OTP: {{ $brw_active->brw_otp }}
        </h3>
    </div>
    <div class="text-justify text-14 font-medium text-warning-700 dark:text-warning-400">
        <p>
            Saat ini Anda sedang meminjam perangkat untuk mata kuliah <strong>{{ $brw_active->brw_mkul }}</strong>, 
            di Kampus {{ $brw_active->brw_region }} sejak <strong> {{ tglID($brw_active->brw_req_in) }} {{ now()->year }}</strong> 
            pukul {{ timeID($brw_active->brw_req_in) }}.
        </p>
        <p class="mt-2">Perangkat yang dipinjam:</p>
            <ol class="ml-5 list-decimal">
                @forelse (BarangList($brw_active->barang_list) as $item)
                    <li>{{ $item }}</li>
                @empty
                    <li class="list-none">-</li>
                @endforelse
            </ol>
        @if($jamBACK)
            <p class="mt-2">
                Harap kembalikan perangkat tersebut paling lambat pukul <strong>{{ timeID($jamBACK) }} di hari yang sama</strong>,
                dan selesaikan peminjaman pada ruangan Sekretariat Dosen jika Anda ingin meminjam kembali.
            </p>
        @endif
        <p class="mt-2">Terima kasih!</p>
    </div>
    <p class="mt-4 text-right text-14 font-medium text-warning-600 dark:text-warning-500">
        TTD.<br>
        Staf Sekretariat Dosen
    </p>
</div>
