@extends('layouts.app')

@section('title', 'Form Pemindahan Ruangan')

@section('content')
    <script>
        const APP_URL = {
            jadwalDosen  : "{{ route('jadwal.dosen') }}",
            ruanganKosong: "{{ route('ruangan.kosong') }}",
        };
    </script>
    <div class="ml-1.5 mb-6 font-semibold">
        <h2 class="text-18 sm:text-20 text-gray-800 dark:text-white/90">Form Pemindahan Ruangan</h2>
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
        <form id="f-pemindahan" method="POST" action="{{ route('form.pemindahan.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 text-14 sm:text-15">
                <div>
                    <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Kode Dosen</label>
                    <input type="text" id="kode" value="{{ $user->dosen->kode_dsn }}" readonly disabled
                        class="h-11 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 text-gray-600 dark:border-gray-700 dark:bg-white/5 dark:text-gray-400 cursor-not-allowed">
                </div>
                <div>
                    <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Nama Anda</label>
                    <input type="text" id="nama" value="{{ ucwords(strtolower($user->dosen?->nama_dsn)) }}" readonly disabled
                        class="h-11 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 text-gray-600 dark:border-gray-700 dark:bg-white/5 dark:text-gray-400 cursor-not-allowed">
                </div>
            </div>
            <div class="text-14 sm:text-15">
                <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Mata Kuliah<a class="text-error-500"> *</a></label>
                <select id="mv_mkul" autocomplete="off" required onchange="onMatkulChange(this)"
                    class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300 cursor-pointer">
                    <option value='' disabled selected>Pilih Mata Kuliah</option>
                </select>
            </div>

            <input type="hidden" name="mv_mkul"     id="mv_mkul_val">
            <input type="hidden" name="room_id"     id="room_id">
            <input type="hidden" name="mv_kelas"    id="mv_kelas">
            <input type="hidden" name="mv_jamkul"   id="mv_jamkul">
            <input type="hidden" name="mv_tgl_mkul" id="mv_tgl_mkul">

            <div class="border-gray-100 pt-5 dark:border-gray-700">
                <hr class="-mt-1 mb-5 dark:border-gray-700">
                <h3 class="ml-1.5 mb-5 text-center font-semibold text-17 sm:text-19 text-black dark:text-white/90">Pilih Ruangan Pengganti</h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 text-14 sm:text-15">
                    <div>
                        <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Lokasi Kampus<a class="text-error-500"> *</a></label>
                        <select id="mv_region" name="mv_region" required autocomplete="off"
                            onchange="onRegionChange(this.value)"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300 cursor-pointer">
                            <option value='' disabled selected>Pilih lokasi kampus</option>
                            <option value='D'>Kampus D</option>
                            <option value='E'>Kampus E</option>
                            <option value='G'>Kampus G</option>
                            <option value='F8'>Kampus F8</option>
                        </select>
                    </div>
                    <div>
                        <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Gedung<a class="text-error-500"> *</a></label>
                        <select id="mv_gd" name="mv_gd" required autocomplete="off"
                            onchange="onGedungChange(this.value)" disabled
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 disabled:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:disabled:bg-white/5 cursor-pointer">
                            <option value='' disabled selected>Pilih gedung</option>
                        </select>
                    </div>
                    <div>
                        <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Lantai<a class="text-error-500"> *</a></label>
                        <select id="mv_floor" name="mv_floor" required autocomplete="off"
                            onchange="onLantaiChange(this.value)" disabled
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 disabled:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:disabled:bg-white/5 cursor-pointer">
                            <option value='' disabled selected>Pilih lantai</option>
                        </select>
                    </div>
                    <div>
                        <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Ruangan<a class="text-error-500"> *</a></label>
                        <select id="room_replace" name="room_replace" required autocomplete="off" disabled
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 disabled:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:disabled:bg-white/5 cursor-pointer">
                            <option value='' disabled selected>Pilih ruangan</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="border-gray-100 pt-5 dark:border-gray-700">
                <hr class="-mt-1 mb-5 dark:border-gray-700">
                <div class="text-14 sm:text-15">
                    <label class="ml-1.5 mb-1.5 block font-medium text-gray-700 dark:text-gray-300">Alasan Pemindahan<a class="text-error-500"> *</a></label>
                    <textarea id="mv_reason" name="mv_reason" rows="5" maxlength="1000" placeholder="Masukkan alasan pemindahan ruangan..." required autocomplete="off"
                        class="w-full rounded-lg border border-gray-200 bg-transparent p-3 text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-700 dark:text-gray-300"></textarea>
                    @error('mv_reason')
                        <p class="text-red-500 text-16 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            
            <div class="flex items-center gap-3 pt-3">
                <a href="{{ route('dashboard.pemindahan') }}" class="rounded-lg border border-gray-200 px-5 py-2.5 font-medium text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                    BACK
                </a>
                <button type="submit" class="rounded-lg bg-success-500 px-5 py-2.5 text-14 sm:text-15 font-medium text-white transition-colors duration-150 hover:bg-success-600">
                    SUBMIT
                </button>
            </div>
        </form>
    </div>
@endsection
