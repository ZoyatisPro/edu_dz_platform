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

/**
 * Translation files shipped with the application, mirroring the framework ones.
 *
 * @return array<int, string>
 */
function applicationTranslationFiles(): array
{
    return ['app', 'auth', 'passwords', 'pagination', 'validation'];
}

/**
 * Keys from the framework English translation file, ignoring the "custom"
 * placeholder that applications are not required to copy.
 *
 * @return array<int, string>
 */
function frameworkTranslationKeys(string $file): array
{
    $english = translationKeys(require base_path("vendor/laravel/framework/src/Illuminate/Translation/lang/en/{$file}.php"));

    return array_values(array_filter(
        $english,
        fn (string $key): bool => ! str_starts_with($key, 'custom.'),
    ));
}

it('keeps the same translation keys in french and arabic', function (string $file) {
    $french = translationKeys(require lang_path("fr/{$file}.php"));
    $arabic = translationKeys(require lang_path("ar/{$file}.php"));

    expect($arabic)->toEqualCanonicalizing($french);
})->with(applicationTranslationFiles());

it('covers every key used by the framework', function (string $file) {
    $english = frameworkTranslationKeys($file);
    $french = translationKeys(require lang_path("fr/{$file}.php"));
    $arabic = translationKeys(require lang_path("ar/{$file}.php"));

    expect(array_diff($english, $french))->toBe([])
        ->and(array_diff($english, $arabic))->toBe([]);
})->with(['auth', 'passwords', 'pagination', 'validation']);

it('never leaves an empty translation string', function (string $file) {
    foreach (['fr', 'ar'] as $locale) {
        $translations = require lang_path("{$locale}/{$file}.php");

        array_walk_recursive($translations, function (mixed $value): void {
            expect($value)->toBeString()->not->toBeEmpty();
        });
    }
})->with(applicationTranslationFiles());

it('translates framework messages in both locales', function (string $locale) {
    app()->setLocale($locale);

    expect(trans('auth.failed'))->not->toBe('auth.failed')
        ->and(trans('passwords.sent'))->not->toBe('passwords.sent')
        ->and(trans('validation.required'))->not->toBe('validation.required')
        ->and(trans('validation.attributes.email'))->not->toBe('validation.attributes.email');
})->with(['fr', 'ar']);
