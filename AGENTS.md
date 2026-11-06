# edu-dz-platform

## Projet

Plateforme éducative en ligne pour une professeure de sciences en Algérie.
Nom technique : `edu_dz_platform` (`APP_NAME`). Nom affiché : « منصة الأستاذة هاجر » (`APP_DISPLAY_NAME`), configurable.
Utilisateurs : élèves, parents, profs, assistants et admin.
Services : crèche/préparatoire, primaire (1AP à 5AP), CEM (1AM à 4AM), lycée (1AS tronc commun ; 2AS et 3AS par filière), formations libres.
Le cahier des charges complet est dans `docs/cahier-des-charges.md` : **le lire en premier**.
Les prompts de développement sont dans `docs/prompts.md`.

## Stack

- Backend : Laravel 13 (PHP 8.3+), MySQL 8.
- Frontend : Inertia.js 3 + React 19 + TypeScript + Tailwind CSS 4 + shadcn/ui, design system sobre inspiré de Vercel.
- Build : Vite+ (`vp`, alias `vite-plus`) via le kit React officiel de Laravel.
- Tests : Pest (PHP), Larastan (PHPStan), Pint, Vitest + Testing Library, Playwright.
- Paquets prévus : spatie/laravel-permission, spatie/laravel-activitylog, Laravel Socialite, Fortify (2FA), React Flow, Framer Motion.

## Contraintes d'hébergement (mutualisé PHP/MySQL)

- Pas de Redis, pas de WebSockets, pas de worker permanent.
- File de tâches Laravel avec le pilote base de données, lancée par un cron chaque minute
  (`schedule:run` et `queue:work --stop-when-empty`).
- Cache en fichier ou base de données. Les appels longs (IA, réseaux sociaux, envois à un groupe)
  passent toujours par la file.
- Fichiers (PDF, photos) sur un disque compatible S3 derrière un CDN (Cloudflare R2), via le système
  de fichiers de Laravel. Les fichiers privés sont servis par des liens temporaires signés.
  Disque local en développement.
- Aucune vidéo hébergée : on stocke des liens externes (YouTube, Zoom, Google Meet, Telegram) et on
  affiche miniatures et boutons.

## Règles métier clés

- Un utilisateur peut avoir plusieurs modes : élève, parent, prof, assistant, admin.
- Inscription ouverte à tous (email ou téléphone), sans validation. Les modes élève et parent
  s'activent librement depuis l'espace personnel.
- Le mode prof exige la validation de l'admin ou d'un assistant. Les modes assistant et admin sont
  attribués par l'admin.
- Un assistant a les mêmes droits que l'admin. Toutes ses actions sont dans le journal d'audit, et
  il ne peut pas supprimer le compte de l'admin principal. Impossible de retirer le dernier admin.
- Un parent suit ses enfants en lecture seule et peut aussi suivre des cours lui-même avec le même
  compte (mode élève). Un élève peut n'avoir aucun parent. Lien parent–enfant : code à 6 chiffres ou
  compte enfant créé par le parent.
- Accès : l'admin et ses assistants voient tous les espaces (lecture seule par défaut, audit). Un
  parent voit seulement ses enfants. Un prof voit seulement ses groupes. Un élève voit son espace et
  les contenus de ses groupes.
- Un prof ne peut pas ajouter d'autres profs, seulement inviter des élèves dans ses groupes.
- Groupe de cours : ce n'est pas un chat. Le prof y envoie des contenus (cours PDF, exercice, devoir,
  QCM, annonce, lien de séance) que tous les élèves reçoivent, avec suivi vu/rendu.
- Hiérarchie des contenus : Service > Niveau > Filière (lycée) > Matière > Formation > Chapitre >
  Leçon. Les listes de services, niveaux, filières et matières sont modifiables par l'admin.
- Le bouton « Rejoindre » redirige vers le lien de la séance (Zoom, Meet ou Telegram) choisi par le
  prof.

## Conventions

- Code, noms de tables, classes et commits en anglais. Interface en français et en arabe via les
  fichiers de traduction, avec RTL et mode clair/sombre. Mobile d'abord.
- Tables en snake_case pluriel. Classes en PascalCase. Composants React en PascalCase, hooks en
  camelCase avec `use`.
- Autorisation avec des Policies. Validation avec des Form Requests. Peu de logique dans les
  contrôleurs : utiliser des classes Action ou Service.
- Commits courts au format conventionnel (`feat:`, `fix:`, `docs:`, `test:`, `chore:`).
- Données de test avec factories et seeders, jamais de vraies données d'élèves.

## Qualité

- Chaque fonctionnalité vient avec ses tests (unitaires et de fonctionnalité, dont la matrice des
  droits).
- Avant de terminer une tâche : lancer Pint, Larastan, ESLint, `tsc`, et toute la suite de tests,
  et corriger ce qui échoue.
- Accessibilité : contrastes, navigation au clavier, affichage RTL vérifié.
- En cas de doute sur un choix important, poser la question avant de coder.

## Commandes utiles

```bash
composer install && npm install      # installer
cp .env.example .env && php artisan key:generate
php artisan migrate --seed           # base de données
composer dev                         # serveur + vite + queue + logs
composer test                        # Pint + Larastan + tests PHP
npm run types:check                  # tsc
npm run lint                         # ESLint
npm run test                         # Vitest
npm run e2e                          # Playwright
npm run build                        # build de production
```

## Interdits

- Aucun système de paiement ni de virement.
- Aucune visioconférence ni hébergement de vidéos dans l'application.
- Pas de chat entre utilisateurs.
- Pas de WebSockets, de Redis ni de worker permanent.
- Pas de secrets dans le dépôt (clés dans `.env`, avec un `.env.example` à jour).
