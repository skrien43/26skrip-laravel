@php
    $searchKey = $searchKey ?? 'search';
@endphp

<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 md:p-6">
    <div class="mb-4 flex items-center justify-between">
        <h4 class="ml-3 text-20 font-semibold text-black dark:text-gray-300">{{ $title }}</h4>
        <div class="flex text-right items-center">
            <a class="mr-3 text-14 font-medium text-gray-400">(Data tahun {{ now()->year }})</a>
            <button type="button" onclick="refreshPage()"
                title="Refresh halaman"
                class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-15 font-medium transition-colors duration-150 bg-blue-light-700 text-white hover:bg-blue-light-900">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="hidden sm:inline">Refresh</span>
            </button>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    {{-- maintenance cards --}}
        <div class="rounded-xl bg-gray-50 p-5 dark:bg-white/3 md:p-6">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 bg-error-100 rounded-xl dark:bg-gray-800">
                    <svg class="fill-error-600 dark:fill-error-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <span class="text-16 font-medium text-gray-500 dark:text-gray-400">{{ $label }} dalam Perbaikan</span>
            </div>
            <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-error-600 dark:text-error-500">
                {{ ($rest ?? 0) > 0 ? $rest : '-' }}
            </h4>
        </div>
    {{-- using cards --}}
        <div class="rounded-xl bg-gray-50 p-5 dark:bg-white/3 md:p-6">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 bg-warning-100 rounded-xl dark:bg-gray-800">
                    <svg class="fill-warning-500" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM16.59 16.59L11 13V7H12.5V12.25L17.5 15.15L16.59 16.59Z" fill="" />
                    </svg>
                </div>
                <span class="text-16 font-medium tracking-wide text-gray-500 dark:text-gray-400">{{ $label }} Digunakan</span>
            </div>
            <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-warning-500">
                {{ ($used ?? 0) > 0 ? $used : '-' }}
            </h4>
        </div>
    {{-- all cards --}}
        <div class="rounded-xl bg-gray-50 p-5 dark:bg-white/3 md:p-6">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 bg-success-100 rounded-xl dark:bg-gray-800 shrink-0">
                    <svg class="fill-success-600 dark:fill-success-400" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.4" d="M8 3a2 2 0 0 0-2 2v1H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-1V5a2 2 0 0 0-2-2H8z"/>
                        <path d="M9 3h6a1 1 0 0 1 1 1v2H8V4a1 1 0 0 1 1-1z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16 12.7l-4.3 4.3-2.4-2.4a1 1 0 1 1 1.4-1.4l1 1 3.6-3.6a1 1 0 1 1 1.4 1.4z"/>
                    </svg>
                </div>
                <span class="text-16 font-medium tracking-wide text-gray-500 dark:text-gray-400">Total {{ $label }} Tersedia</span>
            </div>
            <h4 class="mb-2 mt-2 text-title-sm text-center font-bold text-success-600 dark:text-success-500">
                {{ ($ready ?? 0) > 0 ? $ready : '-' }}
            </h4>
        </div>
    </div>

    {{-- add barang --}}
        @if ($kategori !== 'PAC')
            <div class="mt-5">
                <form id="f-tambah_brg-{{ $kategori }}" method="POST" action="{{ route('brg.store') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    @csrf
                    <input type="hidden" name="brg_cat" value="{{ $kategori }}">
                    <div class="flex-1">
                        <label class="ml-1 mb-1.5 block text-16 font-medium text-gray-600 dark:text-gray-400">Kode {{ $label }}</label>
                        <input id="brg_kode-{{ $kategori }}" name="brg_kode" placeholder="Masukkan kode {{ $label }} baru..." required
                            class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3.5 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                    </div>
                    <div class="flex-1">
                        <label class="ml-1 mb-1.5 block text-16 font-medium text-gray-600 dark:text-gray-400">Nama {{ $label }}</label>
                        <input id="brg_name-{{ $kategori }}" name="brg_name" placeholder="Masukkan nama {{ $label }} baru..." required
                            class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3.5 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                    </div>
                    <button type="submit" class="h-10 whitespace-nowrap rounded-lg bg-blue-light-700 px-4 text-sm font-medium text-white transition-colors duration-150 hover:bg-blue-light-900">
                        TAMBAH
                    </button>
                </form>
            </div>
        @endif

    {{-- table --}}
        <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                <form method="GET" class="w-full max-w-70">
                    <div class="relative flex items-center gap-2">
                        <div class="relative flex-1">
                            <input type="text" name="{{ $searchKey }}" value="{{ request($searchKey) }}" placeholder="Cari kode barang..."
                                class="h-10 w-full rounded-lg border border-gray-200 bg-transparent pl-9 pr-4 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                        </div>
                        @if (request($searchKey))
                            <a href="{{ url()->current() }}" title="Hapus pencarian" onclick="saveScrollPos()"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-error-700 text-error-500 hover:bg-error-100 dark:hover:bg-error-100/50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-300">
                    <thead class="text-14 font-medium">
                        <tr class="border-b border-gray-100 bg-gray-50/60 dark:border-gray-800 dark:bg-white/2">
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">NO</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">KODE</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">NAMA</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">STATUS</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">UPDATED</th>
                            <th class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-14 font-medium divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($items as $index => $row)
                            @php $noUrut = $items->firstItem() + $index; @endphp
                            <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-white/2">
                                <td class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">{{ $noUrut }}</td>
                                <td class="px-5 py-3 text-center text-16 font-mono">
                                    <span id="kode-view-{{ $row->brg_id }}" class="text-gray-700 dark:text-gray-300">{{ $row->brg_kode }}</span>
                                    <input type="text" id="kode-edit-{{ $row->brg_id }}" name="brg_kode"
                                        form="form-edit-{{ $row->brg_id }}" autocomplete="off"
                                        value="{{ $row->brg_kode }}" style="display:none"
                                        class="h-9 w-50 rounded-lg border border-gray-200 bg-transparent px-3 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300"><br>
                                    @if($row->brg_cat !== 'PAC')
                                        <select id="lokasi-edit-{{ $row->brg_id }}" name="brg_region"
                                                form="form-edit-{{ $row->brg_id }}" style="display:none"
                                                class="font-outfit h-9 w-50 rounded-lg border border-gray-200 bg-transparent px-3 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                                            <option value="D"  {{ $row->brg_region === 'D'  ? 'selected' : '' }}>Kampus D</option>
                                            <option value="E"  {{ $row->brg_region === 'E'  ? 'selected' : '' }}>Kampus E</option>
                                            <option value="G"  {{ $row->brg_region === 'G'  ? 'selected' : '' }}>Kampus G</option>
                                            <option value="F8" {{ $row->brg_region === 'F8' ? 'selected' : '' }}>Kampus F8</option>
                                        </select>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span id="nama-view-{{ $row->brg_id }}" class="font-medium text-gray-800 dark:text-white/90">{{ $row->brg_name }}</span>
                                    <input type="text" id="nama-edit-{{ $row->brg_id }}" name="brg_name"
                                        form="form-edit-{{ $row->brg_id }}" autocomplete="off"
                                        value="{{ $row->brg_name }}" style="display:none"
                                        class="mb-2 h-9 w-50 rounded-lg border border-gray-200 bg-transparent px-3 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @php
                                        $status = match($row->brg_status) {
                                            'READY' => 'READY',
                                            'USED'  => 'USED',
                                            'REST'  => 'REST',
                                        };
                                        $statusBadge = match($row->brg_status) {
                                            'READY' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                            'USED' => 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-500',
                                            'REST' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
                                            default => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
                                        };
                                    @endphp
                                    <span id="status-view-{{ $row->brg_id }}" class="inline-flex items-center rounded-full py-0.5 px-2.5 text-xs font-medium {{ $statusBadge }}">
                                        {{ $status }}
                                    </span>
                                    <select id="status-edit-{{ $row->brg_id }}" name="brg_status"
                                            form="form-edit-{{ $row->brg_id }}" style="display:none"
                                            class="h-9 w-50 rounded-lg border border-gray-200 bg-transparent px-3 text-sm text-gray-700 focus:border-blue-light-300 focus:outline-hidden focus:ring-3 focus:ring-blue-light-500/10 dark:border-gray-800 dark:text-gray-300">
                                        <option value="READY" {{ $row->brg_status === 'READY' ? 'selected' : '' }}>Ready</option>
                                        <option value="REST"  {{ $row->brg_status === 'REST'  ? 'selected' : '' }}>Rest</option>
                                    </select>
                                </td>
                                <td class="px-5 py-3 text-center text-gray-500 dark:text-gray-400">
                                    {{ tglID($row->brg_updated) }}, {{ timeID($row->brg_updated) }}
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <form id="form-edit-{{ $row->brg_id }}" action="{{ route('brg.update', $row->brg_id) }}"
                                        method="POST" style="display:inline-block">
                                        @csrf
                                        @method('PUT')
                                    </form>

                                    <button type="button" id="btn-edit-{{ $row->brg_id }}"
                                        onclick="toggleEdit({{ $row->brg_id }})"
                                        @if($row->brg_status === 'USED') disabled @endif
                                        class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition-colors duration-150 enabled:hover:border-blue-light-300 enabled:hover:bg-blue-light-50 enabled:hover:text-blue-light-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-blue-light-500/10 disabled:opacity-50 disabled:cursor-not-allowed">
                                        EDIT
                                    </button>
                                    <button type="submit" form="form-edit-{{ $row->brg_id }}"
                                        id="btn-simpan-{{ $row->brg_id }}" style="display:none"
                                        class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition-colors duration-150 hover:border-success-300 hover:bg-success-50 hover:text-success-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-success-500/10">
                                        SIMPAN
                                    </button>
                                    <button type="button" id="btn-batal-{{ $row->brg_id }}"
                                        onclick="toggleEdit({{ $row->brg_id }})" style="display:none"
                                        class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition-colors duration-150 hover:border-red-200 hover:text-red-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-red-500/10">
                                        BATAL
                                    </button>

                                    @if($row->brg_cat !== 'PAC')
                                        <form id="form-delete-{{ $row->brg_id }}" action="{{ route('brg.destroy', $row->brg_id) }}" method="POST" style="display:inline-block"
                                            onsubmit="return confirm('Yakin hapus data perangkat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" id="btn-hapus-{{ $row->brg_id }}"
                                                @if($row->brg_status === 'USED') disabled @endif
                                                class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                                HAPUS
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">Belum ada data perangkat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    {{-- page handle --}}
        @if ($items->hasPages())
            <div class="mt-4 flex items-center justify-between border-t border-gray-100 px-2 pt-4 dark:border-gray-800">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} data
                </p>
                <div class="flex items-center gap-1">
                    {{-- Previous --}}
                    @if ($items->onFirstPage())
                        <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Prev</span>
                    @else
                        <a href="{{ $items->previousPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Prev</a>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $current = $items->currentPage();
                        $last = $items->lastPage();
                    @endphp

                    {{-- Halaman pertama --}}
                    @if ($current > 2)
                        <a href="{{ $items->url(1) }}"
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
                            <a href="{{ $items->url($page) }}"
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
                        <a href="{{ $items->url($last) }}"
                            class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                            {{ $last }}
                        </a>
                    @endif

                    {{-- Next --}}
                    @if ($items->hasMorePages())
                        <a href="{{ $items->nextPageUrl() }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Next</a>
                    @else
                        <span class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-300 dark:border-gray-800 dark:text-gray-600">Next</span>
                    @endif
                </div>
            </div>
        @endif
</div>
