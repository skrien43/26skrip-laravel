@php
    $role = $role ?? session('role');
    $isAdmin = $role === 'adm';
@endphp

<footer class="border-t border-gray-200 {{ $isAdmin ? 'bg-white' : 'bg-blue-light-800' }} dark:border-gray-700 dark:bg-gray-800">
    <div class="px-4 py-6 mx-auto max-w-(--breakpoint-2xl) md:px-6">
        <div class="{{ $isAdmin ? 'text-black' : 'text-gray-300' }} dark:text-gray-300 flex flex-col items-center text-14 font-medium justify-between gap-4 sm:flex-row">
            <div>
                Copyright © {{ now()->year }} 10122877-Skripsi.
            </div>
            <div class="flex flex-col items-center gap-2 sm:flex-row sm:gap-4">
                <div class="flex items-center gap-3">
                    <a href="https://gunadarma.ac.id/" target="_blank" class="transition-colors duration-150 {{ $isAdmin ? 'hover:text-blue-light-700' : 'hover:text-indigo-400' }} dark:transition-colors dark:duration-150 dark:hover:text-indigo-500">UG MainSite</a>
                    <a href="https://baak.gunadarma.ac.id/" target="_blank" class="transition-colors duration-150 {{ $isAdmin ? 'hover:text-blue-light-700' : 'hover:text-indigo-400' }} dark:transition-colors dark:duration-150 dark:hover:text-indigo-500">UG BAAK</a>
                    <a href="https://staffsite.gunadarma.ac.id/" target="_blank" class="transition-colors duration-150 {{ $isAdmin ? 'hover:text-blue-light-700' : 'hover:text-indigo-400' }} dark:transition-colors dark:duration-150 dark:hover:text-indigo-500">UG StaffSite</a>
                    <a href="https://studentsite.gunadarma.ac.id/" target="_blank" class="transition-colors duration-150 {{ $isAdmin ? 'hover:text-blue-light-700' : 'hover:text-indigo-400' }} dark:transition-colors dark:duration-150 dark:hover:text-indigo-500">UG StudentSite</a>
                </div>
            </div>
        </div>
    </div>
</footer>
