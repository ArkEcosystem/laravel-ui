<?php

declare(strict_types=1);

namespace ARKEcosystem\UserInterface\Components;

use ARKEcosystem\UserInterface\Mail\ContactFormSubmitted;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Contact Form Livewire Component.
 */
final class ContactForm extends Component
{
    use WithFileUploads;
    use WithHoney;

    public $name;
    public $email;
    public $subject;
    public $message;
    public $attachment;

    protected function rules(): array
    {
        $rules = [
            'name'           => ['required', 'max:64'],
            'email'          => ['required', 'email'],
            'subject'        => ['required', 'string', Rule::in($this->getSubjects())],
            'message'        => ['required', 'max:2048'],
            'attachment'     => ['nullable', 'mimes:pdf', 'max:1024'],
        ];

        if ($this->subject === 'desktop_wallet_plugin_report') {
            $rules['plugin_id'] = ['string', 'max:64'];
            $rules['plugin_version'] = ['string', 'max:64'];
        }

        return $rules;
    }

    public function mount(): void
    {
        $this->name = old('name', '');
        $this->email = old('email', '');
        $this->subject = old('subject', $this->getSubjects()->first());
        $this->message = old('message', '');
    }

    public function render(): View
    {
        return ViewFacade::make('ark::livewire.contact-form');
    }

    public function updatedSubject(): void
    {
        if ($this->subject === 'desktop_wallet_plugin_report') {
            $this->message .= "Hi, I would like to report a plugin. The ID is " . request('plugin_id') . " and version is " . request('plugin_version') . ". The issue I've encountered is ...";
        }
    }

    public function submit(): void
    {
        $data = $this->validate();

        $mail = new ContactFormSubmitted(Arr::except($data, ['attachment']));

        if ($data['subject'] === 'job_application') {
            if (request()->has('attachment')) {
                $attachment = request()->file('attachment');

                $mail->attach($attachment->getRealPath(), [
                    'as'   => $attachment->getClientOriginalName(),
                    'mime' => 'application/pdf',
                ]);
            }
        }

        Mail::send($mail);

        $this->emit('toastMessage', [trans('ui::messages.contact'), 'success']);
    }

    private function getSubjects(): Collection
    {
        return collect(config('web.contact.subjects'))->pluck('value');
    }
}
