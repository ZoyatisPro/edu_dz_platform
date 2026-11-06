<?php

use Inertia\Testing\AssertableInertia as Assert;

it('renders the landing page in french by default', function () {
    $this->get('/')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('welcome')
            ->where('locale', 'fr')
            ->where('direction', 'ltr')
            ->where('displayName', config('app.display_name'))
            ->has('translations.welcome.title')
            ->has('supportedLocales', 2)
        );
});

it('exposes the health check endpoint', function () {
    $this->get('/up')->assertOk();
});
