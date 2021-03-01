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
    'showMore' => false,
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

    @isset($showMore)
        {{ $showMore }}
    @endisset

    <button data-trigger
        class="{{ $triggerClass }}"
        @click="expanded = !expanded"
        @isset($triggerDusk) dusk="{{ $triggerDusk }}" @endisset
    >
        <span data-collapsed class="{{ $collapsedClass }}" x-show="!expanded">
            {{ $collapsed }}
        </span>
        <span data-expanded class="{{ $expandedClass }}" x-show="expanded" x-cloak>
            {{ $expanded }}
        </span>
    </button>
</ol>
