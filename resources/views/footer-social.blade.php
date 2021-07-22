@props([
    'networks' => [
        [
            'icon' => 'brands.discord',
            'url' => trans('ui::urls.discord')
        ],
        [
            'icon' => 'brands.twitter',
            'url' => trans('ui::urls.twitter')
        ],
        [
            'icon' => 'brands.linkedin',
            'url' => trans('ui::urls.linkedin')
        ],
        [
            'icon' => 'brands.facebook',
            'url' => trans('ui::urls.facebook')
        ],
        [
            'icon' => 'brands.reddit',
            'url' => trans('ui::urls.reddit')
        ],
        [
            'icon' => 'brands.youtube',
            'url' => trans('ui::urls.youtube')
        ],
        [
            'icon' => 'brands.github',
            'url' => trans('ui::urls.github')
        ],
        [
            'icon' => 'brands.telegram',
            'url' => trans('ui::urls.telegram')
        ],
    ],
])

<div class="flex space-x-5">
    @foreach($networks as $network)
        <x-ark-social-link :url="$network['url']" :icon="$network['icon']" data-safe-external />
    @endforeach
</div>
