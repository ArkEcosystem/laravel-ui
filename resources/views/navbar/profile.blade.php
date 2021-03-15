<x-ark-dropdown
    wrapper-class="relative ml-3 whitespace-nowrap"
    :dropdown-classes="$profileMenuClass ?? null"
    dusk="navbar-profile-dropdown"
>
    <x-slot name="button">
        <span class="relative inline-block avatar-wrapper">
            @isset($identifier)
                <x-ark-avatar
                    :identifier="$identifier"
                    class="w-10 h-10 border-2 border-transparent rounded-lg md:h-11 md:w-11 hover:border-theme-primary-600"
                    x-bind:class="{ 'border-theme-primary-600': dropdownOpen }"
                />
            @else
                <div class="w-10 h-10 overflow-hidden border-2 border-transparent rounded-lg md:h-11 md:w-11 hover:border-theme-primary-600">
                    {{ $profilePhoto->img('', ['class' => 'object-cover w-full h-full', 'alt' => 'Profile Avatar']) }}
                </div>
            @endisset
        </span>
    </x-slot>

    @foreach ($profileMenu as $menuItem)
        @if ($menuItem['isPost'] ?? false)
            <form method="POST" action="{{ route($menuItem['route']) }}">
                @csrf

                <button
                    type="submit"
                    class="dropdown-entry"
                    dusk="dropdown-entry-{{ Str::slug($menuItem['label']) }}"
                >
                    @if($menuItem['icon'] ?? false)
                        @svg($menuItem['icon'], 'inline w-5 mr-4')
                    @endif

                    <span class="flex-1">{{ $menuItem['label'] }}</span>
                </button>
            </form>
        @else
            <a
                href="{{ route($menuItem['route']) }}"
                class="dropdown-entry"
                dusk="dropdown-entry-{{ Str::slug($menuItem['label']) }}"
            >
                @if($menuItem['icon'] ?? false)
                    @svg($menuItem['icon'], 'inline w-5 mr-4')
                @endif

                <span class="flex-1">{{ $menuItem['label'] }}</span>
            </a>
        @endif
    @endforeach
</x-ark-dropdown>
