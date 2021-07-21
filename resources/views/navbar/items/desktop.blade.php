@props([
    'breakpoint' => 'md',
    'navigation' => [],
])

@php
    // Exact class strings required to prevent purging
    $breakpointClasses = [
        'sm' => 'sm:ml-6 sm:flex',
        'md' => 'md:ml-6 md:flex',
        'lg' => 'lg:ml-6 lg:flex',
        'xl' => 'xl:ml-6 xl:flex',
    ][$breakpoint];
@endphp

@if(is_array($navigation))
    <div class="hidden items-center h-full {{ $breakpointClasses }}">
        @foreach ($navigation as $navItem)
            @isset($navItem['children'])
                <a
                    href="#"
                    class="relative inline-flex justify-center items-center px-1 pt-px font-semibold leading-5 border-b-2 border-transparent text-theme-secondary-700 hover:text-theme-secondary-800 hover:border-theme-secondary-300 focus:outline-none transition duration-150 ease-in-out h-full dark:text-theme-secondary-500 dark:hover:text-theme-secondary-400
                        @if(!$loop->first) ml-8 @endif"
                    @click="openDropdown = openDropdown === '{{ $navItem['label'] }}' ? null : '{{ $navItem['label'] }}'"
                    dusk='navbar-{{ Str::slug($navItem['label']) }}'
                >
                    <span :class="{ 'text-theme-primary-600': openDropdown === '{{ $navItem['label'] }}' }">{{ $navItem['label'] }}</span>
                    <span class="ml-2 transition duration-150 ease-in-out text-theme-primary-600" :class="{ 'rotate-180': openDropdown === '{{ $navItem['label'] }}' }">@svg('chevron-down', 'h-3 w-3')</span>
                </a>
                <div x-show="openDropdown === '{{ $navItem['label'] }}'" class="absolute top-0 right-0 z-30 pb-8 mt-24 bg-white rounded-b-lg" x-cloak>
                    <div class="pb-8 mx-8 border-t border-theme-secondary-200"></div>
                    <div class="flex">
                        <div class="flex-shrink-0 w-56 border-r border-theme-secondary-300">
                            @foreach ($navItem['children'] as $childNavItem)
                                <div @mouseenter="selectedChild = {{ json_encode($childNavItem) }}">
                                    <x-ark-sidebar-link
                                        :route="$childNavItem['route'] ?: null"
                                        :name="$childNavItem['label']"
                                        :params="$childNavItem['params'] ?? []"
                                        :href="$childNavItem['href'] ?? null"
                                    />
                                </div>
                            @endforeach
                        </div>
                        <div class="flex flex-col flex-shrink-0 pr-8 pl-8 w-128">
                            <img class="w-full" :src="selectedChild ? selectedChild.image : '{{ $navItem['image'] }}'" />

                            <template x-if="selectedChild">
                                <span x-text="selectedChild.label" class="mb-2 text-xl font-semibold text-theme-secondary-900"></span>
                                <span x-text="selectedChild.description"></span>
                            </template>
                        </div>
                    </div>
                </div>
            @else
                <a
                    @if (array_key_exists('href', $navItem))
                        href="{{ $navItem['href'] }}"
                    @else
                        href="{{ route($navItem['route'], $navItem['params'] ?? []) }}"
                    @endif
                    @if (array_key_exists('attributes', $navItem))
                        @foreach($navItem['attributes'] as $attribute => $attributeValue)
                            {{ $attribute }}="{{ $attributeValue }}"
                        @endforeach
                    @endif
                    class="inline-flex items-center px-1 pt-px font-semibold leading-5 border-b-2 space-x-3
                        focus:outline-none transition duration-150 ease-in-out h-full
                        @if(array_key_exists('route', $navItem) && optional(Route::current())->getName() === $navItem['route'])
                            border-theme-primary-600 text-theme-secondary-900 dark:text-theme-secondary-400
                        @else
                            border-transparent text-theme-secondary-700 hover:text-theme-secondary-800 hover:border-theme-secondary-300 dark:text-theme-secondary-500 dark:hover:text-theme-secondary-400
                        @endif
                        @if(!$loop->first) ml-8 @endif"
                    @click="openDropdown = null;"
                    dusk='navbar-{{ Str::slug($navItem['label']) }}'
                >
                    <span>{{ $navItem['label'] }}</span>

                    @if (array_key_exists('icon', $navItem))
                        <x-ark-icon class="text-theme-primary-600" size="sm" :name="$navItem['icon']" />
                    @endif
                </a>
            @endisset
        @endforeach
    </div>
@else
    {{ $navigation }}
@endif
