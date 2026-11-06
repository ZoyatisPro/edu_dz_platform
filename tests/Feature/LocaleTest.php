<?php

it('switches the interface to arabic and right-to-left', function () {
    $this->from('/')
        ->post('/locale/ar')
        ->assertRedirect('/')
        ->assertSessionHas('locale', 'ar');

    $this->withSession(['locale' => 'ar'])
        ->get('/')
        ->assertOk()
        ->assertSee('dir="rtl"', false)
        ->assertSee('lang="ar"', false)
        ->assertInertia(fn ($page) => $page
            ->where('locale', 'ar')
            ->where('direction', 'rtl')
            ->where('supportedLocales.1.code', 'ar')
        );
});

it('returns to the previous locale', function () {
    $this->withSession(['locale' => 'ar'])
        ->from('/')
        ->post('/locale/fr')
        ->assertRedirect('/')
        ->assertSessionHas('locale', 'fr');

    $this->withSession(['locale' => 'fr'])
        ->get('/')
        ->assertOk()
        ->assertSee('dir="ltr"', false)
        ->assertSee('lang="fr"', false);
});

it('rejects unsupported locales', function () {
    $this->post('/locale/de')->assertNotFound();
});

it('ignores an unsupported locale stored in the session', function () {
    $this->withSession(['locale' => 'de'])
        ->get('/')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('locale', 'fr'));
});
