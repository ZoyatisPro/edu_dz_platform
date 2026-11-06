<?php

/**
 * Flatten a nested translation array into dotted keys.
 *
 * @param  array<string, mixed>  $translations
 * @return array<int, string>
 */
function translationKeys(array $translations, string $prefix = ''): array
{
    $keys = [];

    foreach ($translations as $key => $value) {
        $fullKey = $prefix === '' ? $key : "{$prefix}.{$key}";

        if (is_array($value)) {
            $keys = [...$keys, ...translationKeys($value, $fullKey)];

            continue;
        }

        $keys[] = $fullKey;
    }

    return $keys;
}

it('keeps the same translation keys in french and arabic', function () {
    $french = translationKeys(require lang_path('fr/app.php'));
    $arabic = translationKeys(require lang_path('ar/app.php'));

    expect($arabic)->toEqualCanonicalizing($french);
});

it('never leaves an empty translation string', function () {
    foreach (['fr', 'ar'] as $locale) {
        $translations = require lang_path("{$locale}/app.php");

        array_walk_recursive($translations, function (mixed $value, string $key): void {
            expect($value)->toBeString()->not->toBeEmpty();
        });
    }
});
