# edu-dz-platform

Plateforme éducative en ligne pour une professeure de sciences en Algérie : cours, groupes,
exercices, devoirs et séances en direct, en français et en arabe.

- Nom affiché : **منصة الأستاذة هاجر** (`APP_DISPLAY_NAME`)
- Nom technique : `edu_dz_platform` (`APP_NAME`)
- Cahier des charges : [`docs/cahier-des-charges.md`](docs/cahier-des-charges.md)
- Prompts de développement : [`docs/prompts.md`](docs/prompts.md)

## Stack

| Couche   | Choix                                                         |
| -------- | ------------------------------------------------------------- |
| Backend  | Laravel 13, PHP 8.3+, MySQL 8                                 |
| Frontend | Inertia.js 3, React 19, TypeScript, Tailwind CSS 4, shadcn/ui |
| Build    | Vite+ (`vp`) via le kit React officiel de Laravel             |
| Tests    | Pest, Larastan, Pint, Vitest, Testing Library, Playwright     |

## Installation

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

# Renseigner DB_DATABASE, DB_USERNAME et DB_PASSWORD dans .env
php artisan migrate --seed

composer dev
```

Le site est disponible sur <http://localhost:8000>. Le point de santé est `/up`.

## Commandes

```bash
composer dev          # serveur PHP + Vite + queue + logs
composer test         # Pint + Larastan + Pest
npm run types:check   # TypeScript
npm run lint          # ESLint
npm run test          # Vitest
npm run e2e           # Playwright
npm run build         # build de production
```

## Internationalisation

L'interface est disponible en français (`fr`) et en arabe (`ar`). Le choix est conservé en session
et dans un cookie, puis appliqué par le middleware `SetLocale`. Le sens d'affichage (`ltr`/`rtl`) et
les traductions sont partagés avec Inertia depuis `HandleInertiaRequests`.

Les traductions vivent dans `lang/fr` et `lang/ar` : interface (`app.php`), messages de validation,
authentification, mots de passe et pagination. Des tests vérifient que les deux langues exposent
exactement les mêmes clés et couvrent tous les messages utilisés par le framework.

## Thème

Le mode clair/sombre est géré par `useAppearance` : préférence système par défaut, choix stocké dans
`localStorage` et dans un cookie pour éviter le flash au chargement.

## Hébergement mutualisé

Pas de Redis, pas de WebSockets, pas de worker permanent : la queue utilise la base de données et le
planificateur Laravel lance `queue:work --stop-when-empty` chaque minute via cron.

```cron
* * * * * cd /path/to/edu_dz_platform && php artisan schedule:run >> /dev/null 2>&1
```

Aucune vidéo n'est hébergée : seuls des liens externes (Zoom, Google Meet, Telegram, YouTube) sont
enregistrés. Aucun paiement n'est intégré.
