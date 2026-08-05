@extends('layouts.app')

@section('title', 'Admin | Manajemen Perangkat')

@section('content')
    <div class="mb-6">
        <p class="ml-2 text-24 font-semibold text-blue-light-700 dark:text-gray-300">
            <span id="selamat"></span>, Admin {{ ucwords($user->admin->nama_adm) }}!
        </p>
        <div class="mt-3">
            <h2 class="ml-3 text-20 font-semibold text-black dark:text-gray-300">Manajemen Perangkat/Barang</h2>
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
    <div class="space-y-6">
        @if($lokasi === 'E')
            @include('partials.admin.tb-brg', [
                'kategori'=> 'PAC',
                'title' => 'Kartu Ruangan Kelas',
                'label' => 'Ruangan',
                'items' => $pac,
                'ready' => $pac_ready,
                'used'  => $pac_used,
                'rest'  => $pac_rest,
                'lokasi'=> $lokasi,
                'searchKey' => 'search_pac'
            ])
        @endif
        @include('partials.admin.tb-brg', [
            'kategori'=> 'VWR',
            'title' => 'Proyektor',
            'label' => 'Proyektor',
            'items' => $vwr,
            'ready' => $vwr_ready,
            'used'  => $vwr_used,
            'rest'  => $vwr_rest,
            'lokasi'=> $lokasi,
            'searchKey' => 'search_vwr'
        ])
        @include('partials.admin.tb-brg', [
            'kategori'=> 'HDMI',
            'title' => 'Kabel HDMI',
            'label' => 'Kabel HDMI',
            'items' => $hdmi,
            'ready' => $hdmi_ready,
            'used'  => $hdmi_used,
            'rest'  => $hdmi_rest,
            'lokasi'=> $lokasi,
            'searchKey' => 'search_hdmi'
        ])
    </div>
@endsection
