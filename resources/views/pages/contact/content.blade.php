@props([
    'socialIconHoverClass' => 'hover:bg-theme-danger-400 hover:text-white',
    'discordUrl' => trans('ui::urls.discord'),
    'helpTitle' => trans('ui::pages.contact.let_us_help.title'),
    'helpDescription' => trans('ui::pages.contact.let_us_help.description'),
    'additionalTitle' => trans('ui::pages.contact.additional_support.title'),
    'additionalDescription' => trans('ui::pages.contact.additional_support.description'),
    'formTitle' => trans('ui::pages.contact.form.title'),
    'formDescription' => trans('ui::pages.contact.form.description'),
    'contactNetworks' => [
        'twitter' => trans('ui::urls.twitter'),
        'facebook' => trans('ui::urls.facebook'),
        'reddit' => trans('ui::urls.reddit'),
        'linkedin' => trans('ui::urls.linkedin'),
    ],
])

<div class="flex flex-col space-y-16 lg:flex-row lg:space-y-0 contact-content">
    <div class="flex-1 space-y-8 lg:pr-6 lg:w-1/2 lg:border-r border-theme-secondary-300">
        <div class="pb-8 border-b border-dashed border-theme-secondary-300">
            <h3>{{ $helpTitle }}</h3>

            <div class="mt-4 paragraph-description">
                {{ $helpDescription }}
            </div>
        </div>

        <div class="pb-8 border-b border-dashed border-theme-secondary-300">
            <h3>{{ $additionalTitle }}</h3>

            <div class="mt-4 paragraph-description">
                {{ $additionalDescription }}
            </div>

            <div class="flex flex-col mt-6 space-y-3 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-2">
                <a href="@lang('ui::urls.documentation')" target="_blank" rel="noopener noreferrer" class="button-secondary">@lang('ui::actions.documentation')</a>

                <span class="font-semibold leading-none text-center">@lang('ui::general.or')</span>

                <a href="{{ $discordUrl}}" target="_blank" rel="noopener nofollow noreferrer" class="button-secondary">
                    <div class="flex justify-center items-center space-x-2 w-full">
                        @svg('brands.outline.discord', 'w-5 h-5')
                        <span>@lang('ui::actions.discord')</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="space-y-3 text-theme-secondary-900">
            <div class="font-bold">@lang('ui::pages.contact.social.subtitle')</div>

            <div class="flex space-x-3">
                @foreach($contactNetworks as $name => $url)
                    <x-ark-social-square hover-class="{{ $socialIconHoverClass }}" :url="$url" :icon="'brands.outline.' . $name" />
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex flex-col flex-1 lg:pl-6">
        <h3>{{ $formTitle }}</h3>
        <div class="mt-4">{{ $formDescription }}</div>

        <livewire:contact-form />
    </div>
</div>
