@props([
    'name',
    'errors',
    'class'          => '',
    'id'             => null,
    'model'          => null,
    'hideLabel'      => false,
    'label'          => null,
    'tooltip'        => null,
    'required'       => false,
    'maxlength'      => null,
    'rows'           => 10,
    'usersInContext' => [],
    'endpoint'       => '/api/users/autocomplete',
    'placeholder'    => '',
    'plainText'      => true,
])

<div
    x-data="UserTagger(
        '{{ $endpoint }}',
        {{ json_encode($usersInContext) }},
        {{ $maxlength === null ? 'null' : $maxlength }},
        {{ $plainText ? 'true' : 'false' }}
    )"
    x-init="init"
    class="{{ $class }} ark-user-tagger--input ark-user-tagger--plain-text"
>
    <div class="input-group">
        @unless ($hideLabel)
            @include('ark::inputs.includes.input-label', [
                'name'     => $name,
                'errors'   => $errors,
                'id'       => $id ?? $name,
                'label'    => $label,
                'tooltip'  => $tooltip,
                'required' => $required,
            ])
        @endunless

        <div class="input-wrapper" >
            <input
                x-ref="input"
                type="hidden"
                id="{{ $id ?? $name }}"
                wire:model="{{ $model ?? $name }}"
                name="{{ $name }}"
            />

            <div
                wire:ignore
                x-ref="editor"
                style="min-height: {{ $rows * 30 }}px; word-break: break-word; white-space: pre-wrap;"
                class="input-text @error($name) input-text--error @enderror"
                data-placeholder="{{ $placeholder }}"
            >{{ $slot ?? '' }}</div>
        </div>

        @include('ark::inputs.includes.input-error')
    </div>
</div>
