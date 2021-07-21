@auth
    @isset($navbarNotifications)
        {{ $navbarNotifications }}
    @endisset

    @isset($notifications)
        @include('ark::navbar.notifications', ['class' => $notificationsButtonClasses ?? '' ])
    @endisset

    @isset($profile)
        {{ $profile }}
    @else
        @include('ark::navbar.profile')
    @endisset
@else
    <div class="flex items-center ml-5 sm:ml-4 sm:space-x-6">
        @if(Route::has('register'))
            <a href="{{ route('register') }}" class="hidden font-semibold sm:block link">@lang('actions.sign_up')</a>
        @endif

        @if(Route::has('login'))
            <a href="{{ route('login') }}" class="button-secondary">@lang('actions.sign_in')</a>
        @endif
    </div>
@endauth
