@extends('layouts.app')

@section('title', 'Form Peminjaman Perangkat')

@section('content')
    <script>
        const APP_URL = {
            brg_search: "{{ route('barang.search') }}",
            matkulSearch : "{{ route('matkul.search') }}"
        };
    </script>
    <div class="ml-1.5 mb-6 font-semibold">
        <h2 class="text-18 sm:text-20 text-gray-800 dark:text-white/90">Form Peminjaman Perangkat</h2>
        <p class="text-11 sm:text-13 text-error-500 italic">* Menunjukkan bagian wajib diisi </p>
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

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-white/3 md:p-6">
        <form id="f_peminjaman" method="POST" action="{{ route('form.peminjaman.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 text-14 sm:text-15">
                <div>
                    <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">{{ $role === 'dsn' ? 'Kode Dosen' : 'NPM' }}</label>
                    <input type="text" id="kode" name="kode" disabled
                        value="{{ $role === 'dsn' ? $user->dosen->kode_dsn : $user->mahasiswa->npm }}"
                        readonly
                        class="h-11 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 text-gray-600 dark:border-gray-700 dark:bg-white/5 dark:text-gray-400 cursor-not-allowed">
                </div>
                <div>
                    <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Nama Anda</label>
                    <input type="text" id="nama" name="nama" disabled
                        value="{{ $role === 'dsn' ? ucwords(strtolower($user->dosen->nama_dsn)) : ucwords(strtolower($user->mahasiswa->nama_mhs)) }}"
                        readonly
                        class="h-11 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 text-gray-600 dark:border-gray-700 dark:bg-white/5 dark:text-gray-400 cursor-not-allowed">
                </div>
            </div>

            <div class="text-14 sm:text-15">
                <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Lokasi Kampus<a class="text-error-500"> *</a></label>
                <input type="hidden" name="brw_region" id="brw_region_hidden">
                <select id="brw_region" name="brw_region" onchange="toggle_PACE(this.value)" autocomplete="off" required
                    class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300 cursor-pointer">
                    <option value='' disabled selected>Pilih lokasi kampus</option>
                    <option value='D'>Kampus D</option>
                    <option value='E'>Kampus E</option>
                    <option value='G'>Kampus G</option>
                    <option value='F8'>Kampus F8</option>
                </select>
            </div>

            <div class="relative text-14 sm:text-15">
                <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Mata Kuliah<a class="text-error-500"> *</a></label>
                <input type="text" id="brw_mkul" name="brw_mkul" required
                    placeholder="Ketik nama mata kuliah..." oninput="fetchMatkul(this.value)"
                    value="{{ old('brw_mkul') }}" autocomplete="off"
                    class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300">
                <div id="results_matkul"
                    class="hidden absolute z-10 mt-1 w-full overflow-hidden rounded-lg border border-gray-200 bg-white shadow-theme-lg dark:border-gray-700 dark:bg-gray-900 [&>div]:cursor-pointer [&>div]:px-4 [&>div]:py-2 [&>div]:text-gray-700 [&>div]:hover:bg-gray-50 [&>div]:dark:text-gray-300 [&>div]:dark:hover:bg-white/5">
                </div>
            </div>

            @if ($role === 'mhs')
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 text-14 sm:text-15">
                    <div>
                        <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Dosen Pengampu<a class="text-error-500"> *</a></label>
                        <input type="text" id="brw_pengajar" name="brw_pengajar" placeholder="Masukkan nama Dosen..." required oninput="checkDosen(this)"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Kelas<a class="text-error-500"> *</a></label>
                        <input type="text" id="brw_kelas" name="brw_kelas" placeholder="Contoh: 4KA15" required oninput="checkKelas(this)" maxlength="5"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300">
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 text-14 sm:text-15">
                <div>
                    <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Ruangan<a class="text-error-500"> *</a></label>
                    <input type="text" id="brw_room" name="brw_room" required
                        placeholder="Contoh: E323" autocomplete="off" onblur="autolockPAC()" oninput="checkRoom(this); maxRoom(this)" maxlength="5"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300">
                </div>
                <div>
                    <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Jam Perkuliahan<a class="text-error-500"> *</a></label>
                    <input type="text" id="brw_jamkul" name="brw_jamkul" placeholder="Contoh: 1/2/3" required oninput="checkJamkul(this)" maxlength="8"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300">
                </div>
            </div>

            @if ($role === 'mhs')
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 text-14 sm:text-15">
                    <div>
                        <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Nomor HP/WA<a class="text-error-500"> *</a></label>
                        <div class="flex items-center rounded-lg border border-gray-200 focus-within:border-blue-light-300 focus-within:ring-3 focus-within:ring-blue-light-500/10 dark:border-gray-700">
                            <span class="pl-4 pr-4 font-medium text-gray-500 dark:text-gray-400">+62</span>
                            <input type="text" id="no_hp" name="no_hp" required oninput="checkNo_HP(this)" inputmode="numeric" maxlength="14" placeholder="81234567890"
                                class="h-11 w-full rounded-lg bg-transparent pr-4 text-gray-700 focus:outline-hidden dark:text-gray-300">
                        </div>
                    </div>
                    <div>
                        <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Jaminan<a class="text-error-500"> *</a></label>
                        <select id="brw_jaminan" name="brw_jaminan" autocomplete="off" required
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300 cursor-pointer">
                            <option value='' disabled selected>Pilih jaminan peminjaman</option>
                            <option value='KTP'>KTP</option>
                            <option value='SIM'>SIM</option>
                        </select>
                    </div>
                </div>
            @endif

            <div class="border-gray-100 pt-5 dark:border-gray-700">
                <hr class="-mt-1 mb-5 dark:border-gray-700">
                <h3 class="ml-1.5 mb-4 text-17 sm:text-19 text-center font-semibold text-gray-800 dark:text-white/90">Pilih Perangkat</h3>

                <div class="space-y-5 text-14 sm:text-15">
                    <div class="relative">
                        <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Kabel HDMI<a class="text-error-500"> *</a></label>
                        <input type="text" id="search_hdmi"
                            oninput="brg_search('HDMI', '')"
                            onkeyup="brg_search('HDMI', this.value)"
                            placeholder="Ketik 'HDMI' untuk mencari..." autocomplete="off" required
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300">
                        <input type="hidden" name="kode_hdmi" id="kode_hdmi" required>
                        <div id="results_hdmi"
                            class="hidden absolute z-10 mt-1 w-full max-h-55 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-theme-lg dark:border-gray-700 dark:bg-gray-900 [&>div]:cursor-pointer [&>div]:px-4 [&>div]:py-2 [&>div]:text-16 sm:text-17 [&>div]:text-gray-700 [&>div]:hover:bg-gray-50 [&>div]:dark:text-gray-300 [&>div]:dark:hover:bg-white/5">
                        </div>
                    </div>

                    <div class="relative">
                        <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Proyektor</label>
                        <input type="text" id="search_vwr"
                            oninput="brg_search('VWR', '')"
                            onkeyup="brg_search('VWR', this.value)"
                            placeholder="Ketik 'P-' untuk mencari..." autocomplete="off"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300">
                        <input type="hidden" name="kode_vwr" id="kode_vwr">
                        <div id="results_vwr"
                            class="hidden absolute z-10 mt-1 w-full max-h-55 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-theme-lg dark:border-gray-700 dark:bg-gray-900 [&>div]:cursor-pointer [&>div]:px-4 [&>div]:py-2 [&>div]:text-16 sm:text-17 [&>div]:text-gray-700 [&>div]:hover:bg-gray-50 [&>div]:dark:text-gray-300 [&>div]:dark:hover:bg-white/5">
                        </div>
                    </div>

                    <div id="PACE" style="display:none;" class="relative">
                        <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Kartu Ruangan</label>
                        <input type="text" id="search_pac" autocomplete="off" readonly disabled
                            class="h-11 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 text-gray-600 dark:border-gray-700 dark:bg-white/5 dark:text-gray-400 cursor-not-allowed">
                        <input type="hidden" name="kode_pac" id="kode_pac" readonly>
                        <div id="results_pac"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 text-14 sm:text-15">
                <a href="{{ route('dashboard.peminjaman') }}" class="rounded-lg border border-gray-200 px-5 py-2.5 font-medium text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                    BACK
                </a>
                <button type="submit" class="rounded-lg bg-success-500 px-5 py-2.5 font-medium text-white transition-colors duration-150 hover:bg-success-600">
                    SUBMIT
                </button>
            </div>
        </form>
    </div>
@endsection
