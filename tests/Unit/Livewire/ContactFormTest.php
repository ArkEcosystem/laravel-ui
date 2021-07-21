<?php

use ARKEcosystem\UserInterface\Components\ContactForm;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

it('can automatically fill the message of the contact form if the subject is related to desktop wallet plugin report', function () {
    $config = [
        'subjects' => [
            [
                'label' => 'Technical Support',
                'value' => 'technical_support',
            ],
            [
                'label' => 'Desktop Wallet Plugin Report',
                'value' => 'desktop_wallet_plugin_report',
            ],
        ],
    ];

    Config::set('web.contact.subjects', Arr::get($config, 'subjects'));

    Livewire::test(ContactForm::class)
        ->assertSet('subject', 'technical_support')
        ->assertSet('message', null)
        ->set('subject', 'desktop_wallet_plugin_report')
        ->assertSet('message', "Hi, I would like to report a plugin. The ID is  and version is . The issue I've encountered is ...");
});

it('can submit the contact form', function () {
    Mail::fake();

    $config = [
        'subjects' => [
            [
                'label' => 'Technical Support',
                'value' => 'technical_support',
            ],
            [
                'label' => 'Desktop Wallet Plugin Report',
                'value' => 'desktop_wallet_plugin_report',
            ],
        ],
    ];

    Config::set('web.contact.subjects', Arr::get($config, 'subjects'));

    $instance = Livewire::test(ContactForm::class)
        ->set('name', null)
        ->call('submit')
        ->assertHasErrors(['name'])
        ->set('name', 'foo')
        ->call('submit')
        ->assertHasNoErrors(['name'])
        ->set('email', null)
        ->call('submit')
        ->assertHasErrors(['email'])
        ->set('email', 'foo@gmail.com')
        ->call('submit')
        ->assertHasNoErrors(['email'])
        ->set('subject', null)
        ->call('submit')
        ->assertHasErrors(['subject'])
        ->set('subject', 'technical_support')
        ->call('submit')
        ->assertHasNoErrors(['subject'])
        ->set('message', null)
        ->call('submit')
        ->assertHasErrors(['message'])
        ->set('message', 'fooBar')
        ->call('submit')
        ->assertHasNoErrors(['message'])
        ;

    $instance->assertEmitted('toastMessage');

    Mail::assertQueued(\ARKEcosystem\UserInterface\Mail\ContactFormSubmitted::class);
});