<div class="{{ $class ?? '' }}">
    <div class="input-group">
        <label for="{{ $id ?? $name }}" class="items-center input-label @error($name) input-label--error @enderror">
            {{ ($label ?? '') ? $label : trans('forms.' . $name) }}
            @if(isset($tooltip))<div class="input-tooltip" data-tippy-content="{{ $tooltip }}">?</div>@endif
        </label>

        <div class="input-wrapper">
            <input
                type="{{ $type ?? 'text' }}"
                id="{{ $id ?? $name }}"
                class="input-text @error($name) input-text--error @enderror {{ $inputClass ?? '' }}"
                wire:model="{{ $model ?? $name }}"
                {{-- @TODO: remove --}}
                @isset($keydownEnter) wire:keydown.enter="{{ $keydownEnter }}" @endisset
                {{-- @TODO: remove --}}
                @isset($max) maxlength="{{ $max }}" @endisset
                {{ $attributes->except(['class', 'errors', 'id', 'max', 'model', 'slot', 'slotClass', 'type', 'wire:model', 'wire:keydown.enter']) }}
            />
            @error($name) @include('ark::inputs.input-error') @enderror
        </div>

        @error($name)
            <p class="input-help--error">{{ $message }}</p>
        @enderror
    </div>
</div>
