<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders validation messages in the visitor locale', function (string $locale) {
    app()->setLocale($locale);

    $expected = __('validation.required', [
        'attribute' => __('validation.attributes.email'),
    ]);

    $this->withSession(['locale' => $locale])
        ->post(route('register.store'), [])
        ->assertSessionHasErrors('email');

    expect(session('errors')->first('email'))->toBe($expected);
})->with(['fr', 'ar']);
