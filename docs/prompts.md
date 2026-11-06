# Prompts OpenCode – edu-dz-platform

Colle le contexte du projet dans `AGENTS.md`, puis donne les prompts à OpenCode **un par un**, dans l'ordre ; après chacun, lance les tests, relis et fais un commit.

## Mode d'emploi

1. Crée le dépôt privé `edu-dz-platform` et ajoute `docs/cahier-des-charges.md` (export du cahier des charges en Markdown).
2. Mets le bloc « AGENTS.md » ci-dessous dans un fichier `AGENTS.md` à la racine : OpenCode le lit à chaque session.
3. Donne le Prompt 1, attends la fin, vérifie que les tests passent, fais un commit, puis passe au suivant.
4. Ne donne jamais plusieurs prompts à la fois : un agent travaille mieux sur une tâche précise.
5. Quand OpenCode propose un choix important (bibliothèque, structure de base de données), relis avant d'accepter.

## AGENTS.md (contexte permanent)

```markdown
# edu-dz-platform

## Projet

Plateforme éducative en ligne pour une professeure de sciences en Algérie. Le nom affiché est configurable (APP_NAME, par défaut « منصة الأستاذة هاجر »). Utilisateurs : élèves, parents, profs, admin et assistants.
Services : crèche/préparatoire, primaire (1AP à 5AP), CEM (1AM à 4AM), lycée (1AS tronc commun ; 2AS et 3AS par filière), formations libres.
Le cahier des charges complet est dans docs/cahier-des-charges.md : le lire en premier.

## Stack

- Backend : Laravel (PHP 8.2+), MySQL.
- Frontend : Inertia.js + React + TypeScript + Vite + Tailwind CSS + shadcn/ui (kit de démarrage React officiel de Laravel), design system sobre inspiré de Vercel.
- Tests : Pest, Larastan (PHPStan), Pint, Vitest + Testing Library, Playwright.
- Paquets prévus : spatie/laravel-permission, spatie/laravel-activitylog, Laravel Socialite, Fortify (double vérification), React Flow, Framer Motion.

## Contraintes d'hébergement (mutualisé PHP/MySQL)

- Pas de Redis, pas de WebSockets, pas de worker permanent.
- File de tâches Laravel avec le pilote base de données, lancée par un cron chaque minute (schedule:run et queue:work --stop-when-empty).
- Cache en fichier ou base de données. Les appels longs (IA, réseaux sociaux, envois à un groupe) passent toujours par la file.
- Fichiers (PDF, photos) sur un disque compatible S3 derrière un CDN (Cloudflare R2), via le système de fichiers de Laravel. Les fichiers privés sont servis par des liens temporaires signés. Disque local en développement.
- Aucune vidéo hébergée : on stocke des liens externes (YouTube, Zoom, Google Meet, Telegram) et on affiche miniatures et boutons.

## Règles métier clés

- Un utilisateur peut avoir plusieurs modes : élève, parent, prof, assistant, admin.
- Inscription ouverte à tous (email ou téléphone), sans validation. Les modes élève et parent s'activent librement depuis l'espace personnel.
- Le mode prof exige la validation de l'admin ou d'un assistant. Les modes assistant et admin sont attribués par l'admin.
- Un assistant a les mêmes droits que l'admin. Toutes ses actions sont dans le journal d'audit, et il ne peut pas supprimer le compte de l'admin principal. Impossible de retirer le dernier admin.
- Un parent suit ses enfants en lecture seule et peut aussi suivre des cours lui-même avec le même compte (mode élève). Un élève peut n'avoir aucun parent. Lien parent–enfant : code à 6 chiffres ou compte enfant créé par le parent.
- Accès : l'admin et ses assistants voient tous les espaces (lecture seule par défaut, audit). Un parent voit seulement ses enfants. Un prof voit seulement ses groupes. Un élève voit son espace et les contenus de ses groupes.
- Un prof ne peut pas ajouter d'autres profs, seulement inviter des élèves dans ses groupes.
- Groupe de cours : ce n'est pas un chat. Le prof y envoie des contenus (cours PDF, exercice, devoir, QCM, annonce, lien de séance) que tous les élèves reçoivent, avec suivi vu/rendu.
- Hiérarchie des contenus : Service > Niveau > Filière (lycée) > Matière > Formation > Chapitre > Leçon. Les listes de services, niveaux, filières et matières sont modifiables par l'admin.
- Le bouton « Rejoindre » redirige vers le lien de la séance (Zoom, Meet ou Telegram) choisi par le prof.

## Conventions

- Code, noms de tables, classes et commits en anglais. Interface en français et en arabe via les fichiers de traduction, avec RTL et mode clair/sombre. Mobile d'abord.
- Tables en snake_case pluriel. Classes en PascalCase. Composants React en PascalCase, hooks en camelCase avec use.
- Autorisation avec des Policies. Validation avec des Form Requests. Peu de logique dans les contrôleurs : utiliser des classes Action ou Service.
- Commits courts au format conventionnel (feat:, fix:, docs:, test:, chore:).
- Données de test avec factories et seeders, jamais de vraies données d'élèves.

## Qualité

- Chaque fonctionnalité vient avec ses tests (unitaires et de fonctionnalité, dont la matrice des droits).
- Avant de terminer une tâche : lancer Pint, Larastan, ESLint, tsc et toute la suite de tests, et corriger ce qui échoue.
- Accessibilité : contrastes, navigation au clavier, affichage RTL vérifié.
- En cas de doute sur un choix important, poser la question avant de coder.

## Interdits

- Aucun système de paiement ni de virement.
- Aucune visioconférence ni hébergement de vidéos dans l'application.
- Pas de chat entre utilisateurs.
- Pas de WebSockets, de Redis ni de worker permanent.
- Pas de secrets dans le dépôt (clés dans .env, avec un .env.example à jour).
```

## Prompt 1 – Initialisation du projet

```markdown
Lis AGENTS.md et docs/cahier-des-charges.md. Initialise le projet edu-dz-platform.

1. Crée un projet Laravel avec le kit de démarrage React (Inertia, TypeScript, Tailwind, shadcn/ui), configuré pour MySQL.
2. Mets en place l'internationalisation français et arabe : sélecteur de langue, attribut dir=rtl pour l'arabe, fichiers de traduction, police arabe lisible (Cairo ou Tajawal) et une police latine sobre.
3. Mets en place le mode clair/sombre avec des variables de design (couleurs neutres, bleu profond et vert turquoise en accent, coins arrondis), dans l'esprit du design system de Vercel.
4. Installe et configure : Pest, Larastan, Pint, Vitest, Playwright, ESLint.
5. Configure la file de tâches en pilote base de données, le planificateur Laravel, et le point de contrôle de santé /up.
6. Crée un workflow GitHub Actions qui lance l'analyse du code et les tests à chaque push.
7. Crée un .env.example complet (base de données, stockage S3/R2, mail, APP_NAME, Turnstile, clés des fournisseurs) et un README court avec les commandes de démarrage.

Critères de réussite : le site démarre en local, la page d'accueil s'affiche en français et en arabe (RTL), le mode sombre fonctionne, et tous les tests et outils d'analyse passent. Ne fais rien d'autre.
```

## Prompt 2 – Comptes, modes et espace personnel

```markdown
Implémente les comptes et les modes.

- Inscription ouverte sans validation (email et mot de passe ; numéro de téléphone en champ facultatif ; prévoir une interface pour une vérification SMS future, sans fournisseur pour l'instant). Emplacement pour Cloudflare Turnstile et limitation des tentatives. Vérification d'email.
- Modes avec spatie/laravel-permission : student, parent, teacher, assistant, admin. Un utilisateur peut avoir plusieurs modes. student et parent s'activent librement depuis l'espace personnel ; teacher passe par une demande (statut pending) ; assistant et admin ne sont attribués que par un admin.
- Interface : menu à trois lignes avec Accueil, Paramètres, Espace personnel ; sélecteur de mode ; page Paramètres (langue, mode clair/sombre, mot de passe, double vérification par application via Fortify pour admin et assistant).
- Seeders : rôles, un admin de démonstration, quelques utilisateurs fictifs.
- Tests : inscription, activation des modes, refus d'attribution du mode admin ou assistant par un non-admin, et un premier squelette de matrice des droits (visiteur, élève, parent, prof, assistant, admin).
```

## Prompt 3 – Liens parent–enfant

```markdown
Implémente la liaison parent–enfant.

- Un parent peut créer le compte de son enfant depuis son espace (prénom et niveau suffisent, pas d'email obligatoire, connexion de l'enfant par code ou mot de passe simple).
- Un élève peut générer un code à 6 chiffres (usage unique, expiration courte) ; un parent le saisit pour se lier. Aucune validation de l'admin.
- Un élève peut n'avoir aucun parent. Un parent peut avoir plusieurs enfants, avec un sélecteur en haut de son espace.
- Le parent voit l'espace de ses enfants en lecture seule, et peut passer en mode élève pour suivre des cours avec son propre compte.
- Tests : un parent ne voit jamais un enfant non lié, le code expire et ne sert qu'une fois, un élève sans parent fonctionne normalement.
```

## Prompt 4 – Niveaux, filières et catalogue

```markdown
Implémente la structure des contenus et le catalogue.

- Tables : services, levels, tracks (filières), subjects, formations (liée à un ou plusieurs niveaux et filières), chapters, lessons.
- Seeders : services (crèche/préparatoire, primaire, CEM, lycée, formations) ; niveaux 1AP à 5AP, 1AM à 4AM, 1AS, 2AS, 3AS ; filières du lycée : tronc commun sciences et technologie, tronc commun lettres (1AS) ; sciences expérimentales, mathématiques, technique mathématique (génie électrique, mécanique, civil), gestion et économie, lettres et philosophie, langues étrangères (2AS et 3AS). Libellés en français et en arabe. Toutes ces listes sont modifiables par l'admin.
- Profil : l'élève choisit son niveau et, au lycée, sa filière, modifiables à tout moment ; un parent le fait pour chaque enfant.
- Espace admin « Catalogue » : ajouter, modifier, publier et archiver une formation (titre, service, niveau, filières, matière, prof responsable, description, objectifs, prérequis, image, vidéo de présentation, dates, places, visibilité publique ou privée). Statuts : brouillon, publiée, archivée. Seuls l'admin et les assistants publient.
- Page catalogue côté élève avec filtres (service, niveau, filière, matière), qui montre d'abord les formations de sa filière.
- Tests : filtres, publication réservée, listes modifiables.
```

## Prompt 5 – Espace admin et validation des profs

```markdown
Implémente l'espace admin pour la gestion des utilisateurs.

- Onglet Utilisateurs : barre de recherche par nom (index FULLTEXT MySQL, pagination), filtres par type, service, classe et statut du compte (actif, en attente, suspendu).
- Fiche utilisateur : informations, modes, groupes, enfants liés, dernière connexion ; actions : suspendre, réactiver, réinitialiser le mot de passe, ajouter ou retirer un mode.
- Voir l'espace de n'importe quel utilisateur en lecture seule avec un bandeau visible ; chaque consultation et chaque changement de permission est enregistré dans le journal d'audit (spatie/laravel-activitylog), avec confirmation avant tout changement et notification à l'utilisateur concerné.
- Assistants : l'admin donne ou retire le mode assistant en un clic. Un assistant a les mêmes droits que l'admin, mais ne peut pas supprimer le compte de l'admin principal ; impossible de retirer le dernier admin.
- Validation des profs : une demande de mode teacher crée une notification pour l'admin et les assistants, qui acceptent ou refusent ; l'utilisateur est prévenu.
- Tests : droits de l'admin et de l'assistant, refus pour les autres, protection du dernier admin, journal d'audit rempli, flux de validation d'un prof.
```

## Prompt 6 – Groupes de cours et envois

```markdown
Implémente les groupes de cours (pas un chat).

- Un prof crée un groupe (nom, service, niveau, filière, matière, horaire habituel, plateforme de séance par défaut et lien). Un prof peut avoir plusieurs groupes ; un élève peut appartenir à plusieurs groupes.
- Invitations : lien, code et QR code ; invitation directe par nom ou email ; l'élève rejoint en un clic et le parent lié est prévenu. Liens qui expirent et se désactivent. Un prof ne peut inviter que des élèves.
- Envoi au groupe : types cours PDF, exercice, devoir avec date limite, QCM, annonce, lien de séance, invitation à une séance. Un envoi crée un élément dans l'espace de chaque membre et une notification, via la file de tâches. Envois programmés, brouillons et modèles. Les nouveaux membres retrouvent les anciens contenus.
- Suivi : « vu par X sur Y » et « rendu par X sur Y », avec relance en un clic des élèves qui n'ont pas ouvert ou rendu. Archivage de fin d'année.
- Pas de chat : seulement des commentaires privés élève vers prof sous un devoir ou un cours.
- Tests : un prof ne voit que ses groupes, la diffusion atteint tous les membres, les liens expirés sont refusés, la matrice des droits est mise à jour.
```

## Prompt 7 – Formations, leçons et fichiers

```markdown
Implémente les pages de formation et les leçons.

- Page de formation : en-tête, description, objectifs, prérequis, liste des chapitres et leçons avec coches, pourcentage de progression, bouton « Reprendre là où j'ai arrêté ».
- Leçon : lien de vidéo YouTube (extraire l'identifiant, afficher la miniature puis le lecteur intégré), description, PDF et exercices à télécharger, bouton « Leçon suivante ». Marquer la leçon comme vue à la fin de la vidéo ou manuellement.
- Fichiers : envoi des PDF vers le disque S3/R2 (disque local en développement), jamais publics ; téléchargement par lien temporaire signé après vérification des droits.
- Commentaires par leçon, visibles seulement par les inscrits, avec réponses, modération (épingler, masquer, signaler) et notifications au prof et à l'élève.
- Tests : accès aux fichiers privés refusé sans droit ou avec un lien expiré, progression, commentaires visibles seulement par les inscrits.
```

## Prompt 8 – Devoirs, QCM et notes

```markdown
Implémente les devoirs, les QCM et le carnet de notes.

- Devoir : énoncé et date limite ; l'élève envoie une ou plusieurs photos (compression côté navigateur avant l'envoi) ; le prof voit la liste des rendus, écrit une correction, met une note et la renvoie ; l'élève et le parent sont prévenus.
- QCM : création par le prof, correction automatique, note enregistrée automatiquement.
- Carnet de notes par élève, matière et trimestre, avec moyenne, visible par l'élève et son parent.
- Fichiers des photos privés, avec suppression automatique des anciennes photos après une durée configurable.
- Tests : correction automatique des QCM, calcul des moyennes, visibilité des notes selon le rôle.
```

## Prompt 9 – Séances, calendrier et bouton Rejoindre

```markdown
Implémente les séances et le calendrier.

- Séance : groupe, date, heure, durée, plateforme (Zoom, Google Meet ou Telegram) et lien. Calendrier unique (séances, dates limites de devoirs, examens), vue jour et semaine, mobile d'abord.
- Bouton « Rejoindre » : une route qui vérifie l'appartenance au groupe, enregistre la présence, puis redirige vers le lien. N'accepter que des hôtes autorisés (zoom.us, meet.google.com, t.me) pour éviter toute redirection dangereuse.
- Rappels automatiques avant la séance via la file de tâches.
- Programmation intelligente : un service PHP pur et testé qui calcule les créneaux libres à partir des emplois du temps du prof et des élèves, affichés en vert. Prévoir une interface pour qu'une IA classe ou explique les créneaux plus tard, sans l'implémenter maintenant.
- Tests : calcul des créneaux (dont un test chronométré pour 300 élèves), refus d'accès à un non-membre, redirection limitée aux hôtes autorisés.
```

## Prompt 10 – Accueil, annonces et tickets

```markdown
Implémente l'accueil, les annonces et les tickets.

- Accueil en cases cliquables (services, emploi du temps, cours, devoirs, notes, annonces, signaler un problème), bloc de la prochaine séance avec le bouton « Rejoindre », et annonces à côté.
- Annonces par service ou pour tous, gérées par l'admin et les assistants.
- Accueil modifiable par l'admin : blocs de réussites (prénom, résultat, citation, photo avec accord des parents), témoignages, résultats d'examens, mot de la prof, vidéo de présentation, chiffres clés ; ajout, édition, ordre, masquage, aperçu, date d'affichage.
- Tickets pour signaler un problème : catégorie, description, capture facultative ; liste côté admin avec statut (nouveau, en cours, résolu).
- Tests : seuls l'admin et les assistants modifient l'accueil, un utilisateur ne voit que ses tickets.
```

## Prompt 11 – Cursus interactif

```markdown
Implémente la page Cursus, inspirée de Grafikart.

- Graphe animé avec React Flow : chaque case est une formation ou un chapitre, reliée aux suivantes. Au clic, un panneau latéral affiche la description, la durée, les prérequis, une vidéo de présentation (YouTube) et le bouton « Commencer ».
- Couleurs selon la progression de l'élève : terminé, en cours, à venir. Animations douces avec Framer Motion, qui respectent la préférence de mouvement réduit.
- Sur téléphone, le graphe devient un parcours vertical par étapes.
- Éditeur pour l'admin et les assistants : ajouter des formations, les relier et les ordonner en glisser-déposer.
- Tests de composants et un test E2E du clic sur une case.
```

## Prompt 12 – Publication sur les réseaux sociaux

```markdown
Implémente la publication multi-réseaux dans l'espace admin.

- Formulaire : texte, annonce, image ou lien de vidéo, cases à cocher pour Telegram, Facebook, Instagram, Threads, YouTube et TikTok, avec programmation de la date et de l'heure.
- Une interface commune « SocialNetworkAdapter » ; un job par réseau dans la file de tâches ; résultat enregistré par réseau (publié, échec, message d'erreur) ; bouton pour réessayer.
- Implémente réellement Telegram (bot, canal) ; pour les autres réseaux, crée les adaptateurs avec un faux fournisseur testable, prêts à recevoir les vraies API (tokens stockés chiffrés, renouvellement prévu).
- Tests avec de faux réseaux : succès, échec partiel, nouvel essai.
```

## Prompt 13 – Assistant IA

```markdown
Implémente l'assistant IA.

- Volet latéral accessible à droite sur toutes les pages, où l'utilisateur pose une question.
- Une interface commune « AiProvider » avec des adaptateurs interchangeables (OpenAI, Gemini, Claude) choisis dans la configuration.
- Les appels passent par la file de tâches ou une réponse en flux courte, avec délai maximum ; quota de questions par utilisateur et par jour ; réponses centrées sur les contenus de la plateforme ; filtre adapté aux enfants ; aucune donnée personnelle envoyée sans nécessité.
- Banc d'essai : un script qui pose 20 questions de cours (français et arabe) à chaque fournisseur et enregistre les réponses pour une notation par la prof.
- Tests avec un faux fournisseur : quota, délai dépassé, filtre.
```

## Prompt 14 – Tests de bout en bout et benchmark

```markdown
Mets en place la suite de tests complète et le benchmark (section 15 du cahier des charges).

- Playwright : les 8 parcours prioritaires, sur mobile et ordinateur, en mode clair et sombre, en français et en arabe (RTL), avec contrôles d'accessibilité (axe-core) et captures visuelles.
- Matrice des droits complète : chaque rôle × chaque page ou action, avec l'attendu (accès ou refus).
- k6 : scénarios de charge (100 à 300 élèves qui ouvrent l'emploi du temps et cliquent « Rejoindre » ; envoi à un groupe de 200 élèves). Lighthouse CI en réseau 4G lent. Contrôle du poids du code front. Détection des requêtes N+1.
- Chaque exécution enregistre ses chiffres avec la date et la version, les compare à la dernière version stable, et fait échouer le workflow si une mesure se dégrade au-delà des seuils (temps de réponse +20 %, code front +10 %, score Lighthouse −5 points).
- Workflows GitHub Actions : analyse et tests rapides à chaque push, parcours E2E essentiels à chaque pull request, suite complète et benchmarks chaque nuit.
```

## Prompt 15 – Mise en production sur hébergement mutualisé

```markdown
Prépare la mise en production sur un hébergement mutualisé PHP/MySQL.

- Guide de déploiement dans docs/deploiement.md : dossier racine pointant sur public/, version de PHP, extensions nécessaires, cron chaque minute, variables du fichier .env, commandes de mise à jour (migrations, optimisation du cache de configuration et des routes), procédure si SSH ou Composer sont indisponibles (construire en local et envoyer le dossier vendor et les fichiers compilés).
- Sécurité : en-têtes de sécurité, limitation de débit, Cloudflare Turnstile sur l'inscription et la connexion, sessions sécurisées, sauvegardes automatiques de MySQL et des fichiers avec un test de restauration documenté.
- Surveillance : Sentry pour les erreurs, point de contrôle de santé pour UptimeRobot.
- Pages légales : conditions d'utilisation et politique de confidentialité, avec une section pour les mineurs et l'accord des parents.
- Site de test sur un sous-domaine, avec des données fictives uniquement.
```

## Conseils pendant le développement

- Si OpenCode dépasse la tâche demandée, rappelle-lui : « Fais seulement ce que dit le prompt. »
- Si un test échoue, donne-lui le message d'erreur complet et demande de corriger la cause, pas le test.
- Après chaque prompt, lance toi-même l'application et teste le parcours à la main sur ton téléphone, en français et en arabe.
- Garde `AGENTS.md` à jour : quand une décision change, change-la d'abord là.
