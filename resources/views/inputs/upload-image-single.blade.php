@props([
    'id',
    'image'                     => null,
    'dimensions'                => 'w-48 h-48',
    'uploadText'                => trans('ui::forms.upload-image.upload_image'),
    'deleteTooltip'             => trans('ui::forms.upload-image.delete_image'),
    'minWidth'                  => 148,
    'minHeight'                 => 148,
    'maxWidth'                  => 4000,
    'maxHeight'                 => 4000,
    'width'                     => 450,
    'height'                    => 450,
    'maxFilesize'               => '2MB',
    'acceptMime'                => 'image/jpg,image/jpeg,image/bmp,image/png',
    'readonly'                  => false,
    'uploadErrorMessage'        => null,
    'withCrop'                  => false,
    'cropOptions'               => "{}",
    'cropTitle'                 => trans('ui::modals.crop-image.title'),
    'cropMessage'               => trans('ui::modals.crop-image.message'),
    'cropModalWidth'            => 'max-w-xl',
    'cropCancelButton'          => trans('ui::actions.back'),
    'cropSaveButton'            => trans('ui::actions.save'),
    'cropCancelButtonClass'     => 'button-secondary flex items-center justify-center',
    'cropSaveButtonClass'       => 'button-primary flex items-center justify-center',
    'cropSaveIcon'              => false,
    'cropFillColor'             => '#fff',
    'cropImageSmoothingEnabled' => true,
    'cropImageSmoothingQuality' => 'high',
    'cropEndpoint'              => route('cropper.upload-image'),
])

<div
    @if($withCrop)
    x-data="CropImage(
        {{ $cropOptions }},
        @entangle($attributes->wire('model')),
        'image-single-upload-{{ $id }}',
        'image-single-crop-{{ $id }}',
        'crop-modal-{{ $id }}',
        {{ $minWidth }},
        {{ $minHeight }},
        {{ $maxWidth }},
        {{ $maxHeight }},
        {{ $width }},
        {{ $height }},
        '{{ $maxFilesize }}',
        '{{ $cropFillColor }}',
        {{ $cropImageSmoothingEnabled }},
        '{{ $cropImageSmoothingQuality }}',
        '{{ $cropEndpoint }}',
    )"
    x-init="init"
    @else
    x-data="{ isUploading: false, select() { document.getElementById('image-single-upload-{{ $id }}').click(); } }"
    @endif
    x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false; livewire.emit('uploadError', '{{ $uploadErrorMessage }}');"
    class="relative {{ $dimensions }}"
>
    <div class="rounded-xl w-full h-full @unless ($image) p-2 border-2 border-dashed border-theme-primary-100 @endif">
        <div
            @if ($image)
            style="background-image: url('{{ $image }}')"
            @endif
            class="inline-block w-full h-full bg-center bg-no-repeat bg-cover rounded-xl bg-theme-primary-50 @unless($readonly) cursor-pointer hover:bg-theme-primary-100 transition-default @endunless"
            @unless($readonly)
            @click.self="select"
            role="button"
            @endunless
        >
            @unless($readonly)
                <input
                    id="image-single-upload-{{ $id }}"
                    type="file"
                    class="sr-only"
                    accept="{{ $acceptMime }}"
                    @if($withCrop)
                    @change="validateImage"
                    @else
                    wire:model="{{ $attributes->wire('model') }}"
                    @endif
                />
            @endunless
        </div>

        @if (!$image && !$readonly)
            <div
                dusk="crop-upload-button"
                wire:key="upload-button"
                class="flex absolute top-2 right-2 bottom-2 left-2 flex-col justify-center items-center space-y-2 rounded-xl cursor-pointer pointer-events-none"
                role="button"
            >
                <div class="text-theme-primary-500">
                    <x-ark-icon name="upload-cloud" size="lg"/>
                </div>

                <div class="font-semibold text-theme-secondary-900">{{ $uploadText }}</div>

                <div class="text-xs font-semibold text-theme-secondary-500">
                    @lang('ui::forms.upload-image.min_size', [$minWidth, $minHeight])
                </div>
                <div class="text-xs font-semibold text-theme-secondary-500">
                    @lang('ui::forms.upload-image.max_filesize', [$maxFilesize])
                </div>
            </div>
        @endif


        @unless($readonly)
            <div
                wire:key="delete-button-{{ $id }}"
                class="rounded-xl absolute top-0 opacity-0 hover:opacity-100 transition-default w-full h-full
                    @unless ($image) hidden @endunless"

            >
                <div
                    class="absolute top-0 w-full h-full rounded-xl opacity-70 pointer-events-none border-6 border-theme-secondary-900 transition-default"></div>

                <div
                    class="absolute top-0 right-0 p-1 -mt-2 -mr-2 rounded cursor-pointer bg-theme-danger-100 text-theme-danger-500"
                    wire:click="deleteImageSingle"
                    data-tippy-hover="{{ $deleteTooltip }}"
                >
                    <x-ark-icon name="close" size="sm"/>
                </div>
            </div>

            <div x-show="isUploading" x-cloak>
                <x-ark-loading-spinner class="right-0 bottom-0 left-0 rounded-xl" :dimensions="$dimensions"/>
            </div>
        @endunless
    </div>

    <x-ark-js-modal
        name="crop-modal-{{ $id }}"
        class="w-full max-w-2xl text-left"
        title-class="header-2"
        x-data="{
            onHidden: () => {
                Livewire.emit('discardCroppedImage');
            },
        }"
        init
    >
        @slot('title')
            {{ $cropTitle }}
        @endslot

        @slot('description')
            @if($cropMessage)
                <div class="mt-3">
                    {!! $cropMessage !!}
                </div>
            @endif

            <div class="-mx-8 mt-8 sm:mt-10 sm:-mx-10 h-75">
                <img id="image-single-crop-{{ $id }}" src="" alt="">
            </div>
        @endslot

        @slot('buttons')
            <button class="{{ $cropCancelButtonClass }}" @click="hide"  dusk="crop-cancel-button">
                {{ $cropCancelButton }}
            </button>

            <button class="{{ $cropSaveButtonClass }}" @click="Livewire.emit('saveCroppedImage')" dusk="crop-save-button">
                @if($cropSaveIcon)
                    <x-ark-icon :name="$cropSaveIcon" size="sm" class="inline my-auto mr-2"/>
                @endif

                {{ $cropSaveButton }}
            </button>
        @endslot
    </x-ark-js-modal>
</div>
