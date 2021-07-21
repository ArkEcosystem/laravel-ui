<div>
    <form id="contact-form" class="flex flex-col flex-1 space-y-8" wire:submit.prevent="submit" enctype="multipart/form-data">
        @csrf

        <x-honey-pot />

        <div class="flex flex-col space-y-8 sm:flex-row sm:space-y-0 sm:space-x-3">
            <x-ark-input
                name="name"
                :label="trans('ui::forms.name')"
                autocomplete="name"
                class="w-full"
                :errors="$errors"
            />

            <x-ark-input
                type="email"
                name="email"
                :label="trans('ui::forms.email')"
                autocomplete="email"
                class="w-full"
                :errors="$errors"
            />
        </div>

        <x-ark-select
            :label="trans('ui::forms.subject')"
            :errors="$errors"
            name="subject"
        >
            @foreach(config('web.contact.subjects') as $contactSubject)
                <option value="{{ $contactSubject['value'] }}">{{ $contactSubject['label'] }}</option>
            @endforeach
        </x-ark-select>

        <x-ark-textarea
            name="message"
            :label="trans('ui::forms.message')"
            rows="3"
            class="w-full"
            :errors="$errors"
            :placeholder="trans('ui::pages.contact.message_placeholder')"
        />

        @if($this->subject === 'job_application')
            <div>
                <x-ark-input
                    type="file"
                    name="attachment"
                    :label="trans('ui::forms.attachment_pdf')"
                    class="w-full"
                    :errors="$errors"
                    accept="application/pdf"
                />
            </div>
        @endif

        <div class="flex relative flex-col flex-1 justify-end">
            <button
                type="submit"
                class="button-primary"
            >
                @lang('ui::actions.send')
            </button>
        </div>
    </form>
</div>
