@props([
    'breakpoint' => 'md',
    'navigation' => [],
])

@php
    // Exact class strings required to prevent purging
    $breakpointClass = [
        'sm' => 'sm:hidden',
        'md' => 'md:hidden',
        'lg' => 'lg:hidden',
        'xl' => 'xl:hidden',
    ][$breakpoint];
@endphp

<div x-cloak :class="{'block': open, 'hidden': !open}" class="border-t-2 border-theme-secondary-200 {{ $breakpointClass }}">
    <div class="pt-2 pb-4 rounded-b-lg">
        @if(isset($navbarNotificationsMobile) || isset($notifications))
            <div class="flex justify-center items-center py-0.5 px-2 my-4 mx-8 rounded border shadow-sm md:hidden border-theme-secondary-300">
                @isset($navbarNotificationsMobile)
                    {{ $navbarNotificationsMobile }}
                @endisset

                @if(isset($navbarNotificationsMobile) && isset($notifications))
                    <span class="mx-4 h-5 border-r border-theme-secondary-300 dark:border-theme-secondary-800"></span>
                @endif

                @isset($notifications)
                    @include('ark::navbar.notifications')
                @endisset
            </div>
        @endisset

        @foreach ($navigation as $navItem)
            <x-ark-sidebar-link
                :href="$navItem['href'] ?? null"
                :route="$navItem['route'] ?? null"
                :name="$navItem['label']"
                :params="$navItem['params'] ?? []"
                :icon="isset($navItem['icon']) ? $navItem['icon'] : false"
            />
        @endforeach
    </div>
</div>
