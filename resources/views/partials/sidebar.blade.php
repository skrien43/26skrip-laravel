@php
    use App\Helpers\MenuHelper;
    $menuGroups = MenuHelper::getMenuGroups();
@endphp

<aside id="sidebar"
    class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-linear-to-b from-blue-light-950 via-blue-light-700 to-blue-light-600 dark:from-gray-950 dark:via-gray-800 dark:to-gray-700 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999"
    x-data="{
        openSubmenus: {},
        init() {
            this.initializeActiveMenus();
        },
        initializeActiveMenus() {
            @foreach ($menuGroups as $groupIndex => $menuGroup)
                @foreach ($menuGroup['items'] as $itemIndex => $item)
                    @if (isset($item['subItems']))
                        @foreach ($item['subItems'] as $subItem)
                            @if ($subItem['route'] && request()->routeIs($subItem['route']))
                                this.openSubmenus['{{ $groupIndex }}-{{ $itemIndex }}'] = true;
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        },
        isOpen() {
            return this.$store.sidebar.isExpanded || this.$store.sidebar.isHovered || this.$store.sidebar.isMobileOpen;
        },
        toggleSubmenu(groupIndex, itemIndex) {
            if (!this.isOpen()) return;
            const key = groupIndex + '-' + itemIndex;
            this.openSubmenus[key] = !this.openSubmenus[key];
        },
        isSubmenuOpen(groupIndex, itemIndex) {
            return this.openSubmenus[groupIndex + '-' + itemIndex] || false;
        }
    }"
    :class="{
        'w-62.5': isOpen(),
        'w-20': !isOpen(),
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">

    <div class="pt-8 pb-7 flex"
        :class="isOpen() ? 'justify-start' : 'xl:justify-center'">
        <a href="{{ route('admin.peminjaman') }}">
            <span x-show="isOpen()" class="font-bold text-18 text-gray-100">
                Sekretariat Dosen
            </span>
            <span x-show="!isOpen()" class="font-bold text-18 text-gray-100">
                SD
            </span>
        </a>
    </div>

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav class="mb-6">
            <div class="flex flex-col gap-4">
                @foreach ($menuGroups as $groupIndex => $menuGroup)
                    <div>
                        <h2 class="mb-4 text-14 uppercase flex leading-5 text-gray-100 font-medium"
                            :class="isOpen() ? 'justify-start' : 'lg:justify-center'">
                            <template x-if="isOpen()">
                                <span>{{ $menuGroup['title'] }}</span>
                            </template>
                        </h2>

                        <ul class="flex flex-col gap-1">
                            @foreach ($menuGroup['items'] as $itemIndex => $item)
                                <li>
                                    @if (isset($item['subItems']))
                                        @php
                                            $groupHasActive = collect($item['subItems'])->contains(fn($sub) => $sub['route'] && request()->routeIs($sub['route']));
                                        @endphp
                                        <button
                                            @click="toggleSubmenu({{ $groupIndex }}, {{ $itemIndex }})"
                                            :tabindex="isOpen() ? 0 : -1"
                                            class="menu-item group w-full"
                                            :class="[
                                                (!isOpen() && {{ $groupHasActive ? 'true' : 'false' }}) ? 'menu-item-active' : 'menu-item-inactive',
                                                !isOpen() ? 'pointer-events-none cursor-default' : '']">
                                            <span class="-ml-1 text-16 {{ $groupHasActive ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                                                {!! MenuHelper::getIconSvg($item['icon']) !!}
                                            </span>
                                            <span x-show="isOpen()" class="menu-item-text text-16">
                                                {{ $item['name'] }}
                                            </span>
                                            <svg x-show="isOpen()"
                                                class="ml-auto w-5 h-5 transition-transform duration-200 text-16"
                                                :class="{ 'rotate-180 text-white': isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div x-show="isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) && isOpen()">
                                            <ul class="mt-2 space-y-1 ml-9">
                                                @foreach ($item['subItems'] as $subItem)
                                                    <li>
                                                        <a href="{{ $subItem['route'] ? route($subItem['route']) : '#' }}"
                                                            class="menu-dropdown-item {{ $subItem['route'] && request()->routeIs($subItem['route']) ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                                            {{ $subItem['name'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        @php
                                            $isActive = $item['route'] && request()->routeIs($item['route']);
                                        @endphp
                                        <a href="{{ $item['route'] ? route($item['route']) : '#' }}"
                                            @click="if (!isOpen()) $event.preventDefault()"
                                            :tabindex="isOpen() ? 0 : -1"
                                            class="menu-item group {{ $isActive ? 'menu-item-active' : 'menu-item-inactive' }}"
                                            :class="!isOpen() ? 'pointer-events-none cursor-default' : ''">
                                            <span class="-ml-1 text-16 {{ $isActive ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                                                {!! MenuHelper::getIconSvg($item['icon']) !!}
                                            </span>
                                            <span x-show="isOpen()" class="menu-item-text text-16">
                                                {{ $item['name'] }}
                                            </span>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </nav>
    </div>
</aside>
