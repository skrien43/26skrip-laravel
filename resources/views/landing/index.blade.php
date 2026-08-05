<!DOCTYPE html>
<html lang="EN" class="h-full">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sekretariat Dosen XYZ</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-gray-50">

        {{-- header --}}
            <header class="flex w-full items-center justify-between px-4 py-3 md:px-6 border-b border-gray-200 bg-linear-to-r from-blue-light-800 via-blue-light-500 to-gray-200 dark:border-gray-800 dark:bg-gray-900">
                <div class="mt-1.5 mb-1.5 flex items-center gap-3">
                    <span class=" text-20 font-bold text-white">Sekretariat Dosen XYZ</span>
                </div>
                <div class="mt-1.5 mb-1.5 flex items-center gap-3">
                    <a id="btn_Log" href="{{ url('/login') }}"
                        class="rounded-lg bg-blue-light-700 px-4 py-2 text-16 font-medium text-white transition-colors duration-150 hover:bg-blue-light-800">
                        Masuk
                    </a>
                </div>
            </header>

        <main class="flex-1">
        {{-- center --}}
            <section class="mx-auto max-w-(--breakpoint-2xl) px-4 py-16 text-center md:px-6">
                <h1 class="mb-2 text-24 sm:text-30 font-bold text-gray-800">
                    Selamat Datang!
                </h1>
                <h2 class="mb-4 text-20 sm:text-26 font-bold text-gray-800">
                    di Sistem Peminjaman Perangkat &amp; Pemindahan Ruangan<br>
                    Sekretariat Dosen Universitas XYZ
                </h2>
                <p class="mb-4 text-14 sm:text-16 font-normal mx-auto mt-3 max-w-2xl text-gray-600">
                    Layanan bagi mahasiswa dan dosen untuk mengajukan peminjaman perangkat penunjang perkuliahan
                    serta pemindahan ruangan mengajar secara daring melalui Sekretariat Dosen.
                </p>
                <button id="btn_CTA"
                    class="mt-6 inline-block rounded-lg bg-blue-light-700 px-4 py-2 text-16 font-medium text-white transition-colors duration-150 hover:bg-blue-light-800">
                    Masuk ke Sistem
                </button>
            </section>
        {{-- room empty --}}
            <section class="mx-auto max-w-(--breakpoint-2xl) px-4 pb-12 md:px-6">
                <div class="mt-10 rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                    <div class="mb-4 sm:-mt-2 sm:flex sm:flex-1 sm:flex-items sm:justify-between sm:items-center">
                        <h2 class="sm:ml-2 mb-2 text-16 sm:text-18 font-semibold text-gray-800 dark:text-white/90">Jumlah ruangan kelas kosong terkini</h2>
                        <p class="sm:ml-2 sm:mr-2 mt-2 mb-2 text-14 sm:text-15 sm:text-right font-medium text-gray-800 dark:text-white/90">{{ $dateNOW }}<br>Jam perkuliahan ke-
                            @if ($jamNow)
                                {{ $jamNow }} ({{ $listJam[$jamNow] }})
                            @else
                                x (--:--)
                            @endif
                        </p>
                    </div>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        @foreach ($room_emp as $loc => $data)
                            <div class="rounded-xl bg-gray-100 p-4 text-center">
                                <p class="text-14 font-semibold uppercase tracking-wide text-gray-700 dark:text-white/90">Kampus {{ $loc }}</p>
                                <p class="mt-1 text-32 sm:text-title-sm font-bold text-blue-light-700">
                                    {{ $data['kosong'] > 0 ? $data['kosong'] : 'FULL' }}<span class="text-14 font-medium text-gray-500">/{{ $data['total'] }}</span>
                                </p>
                                <p class="-mb-1 mt-1 text-14 text-gray-500">ruangan kosong</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            <section class="mx-auto max-w-(--breakpoint-2xl) px-4 pb-16 md:px-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                    <h2 class="mb-3 text-16 sm:text-18 font-semibold text-gray-800">Panduan Penggunaan</h2>
                    <ul class="list-disc space-y-1.5 pl-5 text-14 sm:text-16 text-gray-600">
                        <li>Login menggunakan NPM (mahasiswa), Kode Dosen (dosen), atau akun staf (admin).</li>
                        <li>Ajukan peminjaman perangkat atau pemindahan ruangan melalui menu yang tersedia setelah login.</li>
                        <li>Tunjukkan kode OTP dan jaminan peminjaman kepada staf Sekretariat Dosen saat pengambilan dan pengembalian perangkat.</li>
                    </ul>
                </div>
            </section>
        </main>
        @include('partials.footer')
    </body>
</html>
