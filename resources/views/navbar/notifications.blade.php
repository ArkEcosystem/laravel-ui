<div class="{{ $class ?? ''}}" @click="livewire.emit('markNotificationsAsSeen')">
    <x-ark-dropdown wrapper-class="mx-1 md:relative" dropdown-classes="mt-4 {{ $dropdownClasses ?? '' }}" button-class="relative">
        <x-slot name="button">
            @svg('notification', 'h-5 w-5 transition-default text-theme-secondary-600 hover:text-theme-primary-700')

            @isset($notificationsIndicator)
                {{ $notificationsIndicator }}
            @else
                @livewire('notifications-indicator')
            @endif
        </x-slot>

        {{ $notifications }}
    </x-ark-dropdown>
</div>
