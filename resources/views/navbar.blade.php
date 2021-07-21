{{-- Better not to use @props as there's too many properties in included components --}}

@php
    // Exact class strings required to prevent purging
    $backdropClass = [
        'sm' => 'sm:hidden',
        'md' => 'md:hidden',
        'lg' => 'lg:hidden',
        'xl' => 'xl:hidden',
    ][$breakpoint ?? 'md'];

    $separatorBreakpointClass = [
        'sm' => 'sm:block',
        'md' => 'md:block',
        'lg' => 'lg:block',
        'xl' => 'xl:block',
    ][$breakpoint ?? 'md'];
@endphp

<header x-data="Navbar.dropdown()" x-init="init">
    <div
        x-show="openDropdown !== null || open"
        class="overflow-y-auto fixed inset-0 z-30 opacity-75 bg-theme-secondary-900 {{ $backdropClass }}"
        @click="openDropdown = null; open = false;"
        x-cloak
    ></div>

    {{-- Spacer for the sticky navbar  --}}
    <div class="mb-0.5 h-20"></div>
    <nav
        aria-label="{{ trans('ui::general.primary_navigation') }}"
        x-ref="nav"
        class="fixed top-0 z-30 w-full bg-white border-b border-theme-secondary-300 dark:bg-theme-secondary-900"
        dusk="navigation-bar"
    >
        <div class="relative z-10 bg-white navbar-container border-theme-secondary-300">
            <div class="flex relative justify-between h-21">
                @include('ark::navbar.logo')

                @isset($middle)
                    {{ $middle }}
                @endisset

                <div class="flex justify-end items-center h-full">
                    <div class="flex flex-1 justify-end items-center h-full">
                        @isset($desktop)
                            {{ $desktop }}
                        @else
                            @include('ark::navbar.items.desktop')
                        @endisset
                    </div>

                    <span class="{{ $separatorClasses ?? 'hidden pr-2 border-l ml-7 h-7 border-theme-secondary-300 dark:border-theme-secondary-800 ' . $separatorBreakpointClass }}"></span>

                    <div class="flex inset-y-0 right-0 items-center">
                        @if(is_array($navigation))
                            <x-ark-navbar-hamburger :breakpoint="$breakpoint ?? 'md'" />
                        @endif

                        @isset($content)
                            {{ $content }}
                        @else
                            @include('ark::navbar.content')
                        @endisset
                    </div>
                </div>
            </div>
        </div>

        @isset($mobile)
            {{ $mobile }}
        @else
            @include('ark::navbar.items.mobile')
        @endisset
    </nav>
</header>
