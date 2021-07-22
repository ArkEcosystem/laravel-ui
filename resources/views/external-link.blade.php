@props([
    'url',
    'text',
    'class'     => 'link font-semibold',
    'inline'    => false,
    'allowWrap' => false,
    'small'     => false,
    'noIcon'    => false,
])

<a
    href="{{ $url }}"
    class="{{ $class }} {{ $inline ? 'inline space-x-1' : 'flex items-center space-x-2' }} {{ $allowWrap ? '' : 'whitespace-nowrap' }}"
    target="_blank"
    rel="noopener nofollow noreferrer"
>
    <span>{{ $slot ?? $text }}</span>

    @unless($noIcon)
        <x-ark-icon
            name="link"
            :size="$small ? 'xs' : 'sm'"
            :class="'flex-shrink-0 '.($inline ? 'inline mr-1 -mt-1' : 'mr-2')"
        />
    @endunless
</a>
