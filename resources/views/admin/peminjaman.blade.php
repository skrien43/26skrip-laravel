@extends('layouts.app')

@section('title', 'Admin | Dashboard Peminjaman Perangkat')

@section('content')
    @php
        $stLbl = [
            'QUEUED'        => 'Pending',
            'ACTIVE'        => 'Active',
            'DISCHARGED'    => 'Returned'
        ];
    @endphp
    <div class="mb-6">
        <p class="ml-2 text-24 font-semibold text-blue-light-700 dark:text-gray-300">
            <span id="selamat"></span>, Admin {{ ucwords($user->admin->nama_adm) }}!
        </p>
        <div class="mt-3">
            <h2 class="ml-3 text-20 font-semibold text-black dark:text-gray-300">Dashboard Peminjaman Perangkat</h2>
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
    <div class="mb-8">
        @include('partials.admin.tb-mhs')
    </div>
    <div>
        @include('partials.admin.tb-dsn')
    </div>
    {{-- logout confirm --}}
        <script>
            history.pushState(null, "", location.href);
            window.addEventListener("popstate", function () {
                const keluar = confirm("Anda yakin ingin logout dari sistem?");
                if (keluar) {
                    window.location.href = "{{ route('logout') }}";
                } else {
                    history.pushState(null, "", location.href);
                }
            });
        </script>
@endsection
