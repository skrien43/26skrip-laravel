<!DOCTYPE html>
<html lang="EN" class="h-full">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Sekretariat Dosen XYZ')</title>

    {{-- theme dark/light --}}
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('theme', {
                    theme: 'light',
                    init() {
                        const savedTheme = localStorage.getItem('theme');
                        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                        this.theme = savedTheme || systemTheme;
                        this.updateTheme();
                    },
                    toggle() {
                        this.theme = this.theme === 'light' ? 'dark' : 'light';
                        localStorage.setItem('theme', this.theme);
                        this.updateTheme();
                    },
                    updateTheme() {
                        const html = document.documentElement;
                        const body = document.body;
                        if (this.theme === 'dark') {
                            html.classList.add('dark');
                            body.classList.add('dark', 'bg-gray-900');
                        } else {
                            html.classList.remove('dark');
                            body.classList.remove('dark', 'bg-gray-900');
                        }
                    }
                });

                @if($role === 'adm')
                Alpine.store('sidebar', {
                    isExpanded: localStorage.getItem('sidebarExpanded') !== null
                        ? localStorage.getItem('sidebarExpanded') === 'true'
                        : window.innerWidth >= 1280,
                    isMobileOpen: false,
                    isHovered: false,
                    toggleExpanded() {
                        this.isExpanded = !this.isExpanded;
                        this.isMobileOpen = false;
                        localStorage.setItem('sidebarExpanded', this.isExpanded);
                    },
                    toggleMobileOpen() {
                        this.isMobileOpen = !this.isMobileOpen;
                    },
                    setMobileOpen(val) {
                        this.isMobileOpen = val;
                    },
                    setHovered(val) {}
                });
                @endif
            });
        </script>
    {{-- dark prevent flash --}}
        <script>
            (function() {
                const savedTheme = localStorage.getItem('theme');
                const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                const theme = savedTheme || systemTheme;
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    document.body.classList.add('dark', 'bg-gray-900');
                } else {
                    document.documentElement.classList.remove('dark');
                    document.body.classList.remove('dark', 'bg-gray-900');
                }
            })();
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="flex min-h-screen flex-col bg-gray-50 dark:bg-gray-900 overflow-x-hidden" x-data="{ 'loaded': true }">
        {{-- preloader --}}
        <x-common.preloader/>
        {{-- preloader end --}}

        @if($role === 'adm')
            <div class="flex min-h-screen flex-1 xl:flex-row">
                @include('layouts.backdrop')
                @include('partials.sidebar')
                <div class="flex min-h-screen min-w-0 flex-1 flex-col transition-all duration-300 ease-in-out"
                    :class="{
                        'xl:ml-62.5': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                        'xl:ml-20': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                        'ml-0': $store.sidebar.isMobileOpen
                    }">
                    @include('partials.navbar')
                    <div class="min-w-0 flex-1 p-4 mx-auto w-full max-w-(--breakpoint-2xl) md:p-6">
                        @yield('content')
                    </div>
                    @include('partials.footer')
                </div>
            </div>
        @else
            <div class="flex min-h-screen flex-1 flex-col">
                @include('partials.navbar')
                <div class="min-w-0 flex-1 mx-auto w-full max-w-(--breakpoint-2xl) px-4 py-6 md:px-6 md:py-8">
                    @yield('content')
                </div>
                @include('partials.footer')
            </div>
        @endif
    </body>
</html>
