@props([
    'total',
    'triggerDusk' => null,
    'triggerClass' => '',
    'collapsedClass' => '',
    'expandedClass' => '',
    'collapsed' => null,
    'expanded' => null,
    'placeholder' => null,
    'placeholderCount' => 1,
])

<ol data-expandable
    x-data="{ expanded: false }"
    :class="{ 'show-all': expanded }"
    style="--expandable-total-count: {{ $total }};"
    {{ $attributes }}
>
    {{ $slot }}

    @isset ($placeholder)
        @for ($i = 0; $i < $placeholderCount; $i++)
            <li data-placeholder wire:key="expandable-placeholder-{{ Str::random(6) }}">
                {{ $placeholder }}
            </li>
        @endfor
    @endisset

    <button data-trigger
        class="{{ $triggerClass }}"
        @click="expanded = !expanded"
        @isset($triggerDusk) dusk="{{ $triggerDusk }}" @endisset
        x-cloak
    >
        <span data-collapsed class="{{ $collapsedClass }}" x-show="!expanded">
            {{ $collapsed }}
        </span>
        <span data-expanded class="{{ $expandedClass }}" x-show="expanded">
            {{ $expanded }}
        </span>
    </button>
</ol>
