@props([
    'init' => false,
    'xData' => '{}',
    'class' => '',
    'widthClass' => 'max-w-2xl',
    'title' => null,
    'titleClass' => 'inline-block pb-2 font-bold dark:text-theme-secondary-200',
    'buttons' => null,
    'buttonsStyle' => 'modal-buttons',
    'closeButtonOnly' => false,
    'escToClose' => true,
    'name' => '',
])

<div
    {{ $attributes }}
    x-ref="modal"
    data-modal
    x-cloak
    @if($init)
    x-data="Modal.alpine({{ $xData }}, '{{ $name }}')"
    x-init="init"
    @endif
    @if(!$closeButtonOnly && $escToClose)
    @keydown.escape="hide"
    tabindex="0"
    @endif
    x-show="shown"
    class="flex overflow-y-auto fixed inset-0 z-50 md:py-10 md:px-8"
>
    <div class="fixed inset-0 opacity-75 dark:opacity-50 bg-theme-secondary-900 dark:bg-theme-secondary-800"></div>

    <div
        class="modal-content-wrapper md:m-auto w-full {{ $class }} {{ $widthClass }}"
        @if(!$closeButtonOnly)
        @click.away="hide"
        @endif
    >
        <div class="modal-content custom-scroll {{ $widthClass }}">
            <div class="p-8 sm:p-10">
                @if(!$closeButtonOnly)
                <button
                    class="modal-close"
                    @click="hide"
                >
                    <x-ark-icon name="close" size="md" class="m-auto" />
                </button>
                @endif

                @if ($title)
                    <h1 class="{{ $titleClass }}">
                        {{ $title }}
                    </h1>
                @endif

                {{ $description }}

                @if($buttons)
                    <div class="mt-8 text-right {{ $buttonsStyle }}">
                        {{ $buttons }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
