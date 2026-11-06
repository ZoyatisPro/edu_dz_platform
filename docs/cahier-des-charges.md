# Cahier des charges – Plateforme d'éducation en ligne

Plateforme de gestion pour une prof de sciences en Algérie : élèves, parents, profs, séances, paiements, réseaux sociaux.

## 1. Vision

Un seul endroit pour tout gérer : au lieu de WhatsApp, Facebook, Excel et Telegram éparpillés, la prof a **sa propre plateforme à sa marque**. Les élèves et les parents ont chacun leur espace. La vidéo reste sur Zoom, Google Meet ou Telegram, au choix du prof : on ne recrée pas de système vidéo, on ajoute un bouton « Rejoindre ».

Public : préparatoire, primaire, CEM, lycée et formations. Langues : arabe (RTL) et français.

## 2. Les utilisateurs et leurs modes

Un compte peut avoir **plusieurs modes** (ex. une prof qui est aussi parent). L'utilisateur change de mode via le menu à trois lignes : Accueil, Paramètres, Espace personnel. Les modes **élève** et **parent** s'activent librement depuis l'espace personnel ; les modes **prof** et **assistant** sont attribués par l'**admin**, pour éviter qu'un élève se déclare prof.

| Mode   | Ce qu'il voit et fait                                                                                                         |
| ------ | ----------------------------------------------------------------------------------------------------------------------------- |
| Élève  | S'inscrit seul ou avec un parent. Emploi du temps, bouton Rejoindre, cours PDF, devoirs à envoyer (photo), corrections, notes |
| Prof   | Dépose cours et exercices, reçoit les photos des élèves, envoie correction et note, voit ses classes et présences             |
| Parent | Voit ses enfants : présence, notes, emploi du temps ; peut aussi suivre des cours lui-même en mode élève                      |
| Admin  | Gère utilisateurs, modes, classes, planning, accueil, statistiques                                                            |

### 2.1 Navigation et interface (inspiration Google Classroom)

- **Accueil** : des cases qui donnent accès à tout (cours, emploi du temps, devoirs, notes, annonces, réussites).
- **Services** : une section par service, à part : crèche/préparatoire, primaire, CEM, lycée, formations.
- **Annonces** : à côté de l'accueil, par service ou pour tous.
- **Espace personnel** : gestion du compte, choix du mode (élève, prof, parent, admin), mode clair/sombre, langue.
- **Assistant IA** (copilot) : volet accessible à droite sur toutes les pages, où l'utilisateur pose une question.
- **Tickets** : bouton pour signaler un problème (accès, séance, bug) avec catégorie, description et capture ; l'admin les voit dans son espace avec un statut (nouveau, en cours, résolu).

### 2.2 Inscription simple, liens parent–élève et validation des profs

**Inscription pour tous : simple, sans validation**

- N'importe qui crée un compte en quelques secondes avec son email ou son numéro de téléphone et un mot de passe (connexion par Google ou Telegram en option).
- Il arrive directement dans son espace personnel et choisit ce qu'il veut faire.

**Dans l'espace personnel : parent et élève**

- **Je suis parent** : je suis mes enfants (en créant leur compte ou avec un code à 6 chiffres) **et** je peux suivre moi-même des cours avec ce même compte, en passant en mode élève, sans créer de compte élève supplémentaire.
- **Je suis élève** : je m'inscris seul, sans parent, et je rejoins un groupe avec le code ou le lien de mon prof. Je peux relier un parent plus tard, ou jamais.
- Pour les petits (crèche, primaire), le parent crée le compte et l'enfant se connecte avec un code simple, sans email.
- Aucune validation de l'admin n'est nécessaire pour ces liens.

**Devenir prof** : la seule étape avec validation, pour protéger les élèves :

1. L'utilisateur crée un compte et choisit « prof », puis remplit ses informations (nom, matières, niveaux, contact, justificatifs si besoin).
2. Il reçoit un compte utilisateur simple, sans accès prof tant qu'il n'est pas validé.
3. L'admin reçoit une notification dans son espace personnel, vérifie, puis **accepte ou refuse**.
4. Si accepté, l'admin lui ouvre l'espace prof ; l'utilisateur est prévenu.

Règles de permissions :

- Un prof ne peut pas ajouter d'autres profs. Seuls l'admin et ses assistants le peuvent.
- Un prof peut ajouter des élèves à **ses** cours (et seulement les siens).
- Un élève accède aux cours et aux PDF des groupes dans lesquels il est inscrit.
- L'admin et ses assistants peuvent ouvrir l'espace personnel de tout le monde : lecture seule par défaut et **journal d'audit**.
- Un élève peut s'inscrire seul ; pour les plus jeunes, le parent crée le compte de l'enfant depuis son espace.

### 2.3 Publication automatique sur les réseaux

Dans l'espace admin, un bloc « Publier » : texte, annonce, vidéo ou short, puis cases à cocher pour chaque réseau. La publication part sur tous les réseaux cochés, avec une option de programmation (date et heure).

Réseaux voulus et ce qu'il faut savoir (les conditions des API changent souvent : à vérifier avant de promettre) :

| Réseau              | Faisabilité | Point d'attention                                                            |
| ------------------- | ----------- | ---------------------------------------------------------------------------- |
| Telegram            | Facile      | Bot ajouté comme admin du canal                                              |
| Facebook, Instagram | Bonne       | Page Facebook + compte Instagram pro/créateur liés, validation de l'app Meta |
| Threads             | Bonne       | API Meta, même compte développeur                                            |
| YouTube             | Bonne       | Quota d'API ; app non vérifiée = vidéos possiblement privées                 |
| TikTok              | Moyenne     | Audit de l'app exigé avant publication publique                              |

Solution pratique pour démarrer : un service intermédiaire de publication multi-réseaux (type Buffer ou Ayrshare, à comparer) plutôt que d'intégrer chaque API, puis ajouter les intégrations directes plus tard. Prévoir un résultat par réseau (publié / échec) et un bouton pour réessayer.

### 2.4 Programmation intelligente des séances

- Quand l'admin crée une séance, elle choisit le prof, la classe et la durée.
- Le système compare les emplois du temps du prof et des élèves concernés et propose les créneaux libres, **affichés en vert**.
- Le calcul des créneaux libres est fait par le code (fiable et gratuit). L'IA sert à le rendre pratique : classer les meilleurs créneaux, expliquer pourquoi, comprendre une demande en langage naturel (« mardi après 17h »).
- L'admin valide le créneau, puis la séance est créée, les rappels sont envoyés et le lien choisi par le prof (Zoom, Meet ou Telegram) est ajouté à la séance.

### 2.5 Choix de l'IA (ChatGPT, Gemini ou autre)

- Construire une **couche d'abstraction** : le reste de l'app appelle une fonction « demander à l'IA », et on peut changer de fournisseur sans tout refaire (ChatGPT, Gemini, Claude).
- Critères de choix : qualité en arabe et en français, prix par utilisateur, vitesse, disponibilité depuis l'Algérie, protection des données des mineurs.
- Faire un test avec les mêmes 20 questions de cours sur chaque modèle avant de décider.
- Limiter l'assistant : réponses centrées sur les cours de la plateforme, quota de questions par utilisateur par jour pour maîtriser le coût, filtre adapté aux enfants, aucune donnée personnelle envoyée inutilement.

### 2.6 Gestion des utilisateurs par l'admin

Depuis son espace personnel, l'admin dispose d'un onglet **Utilisateurs** :

- **Barre de recherche** : par nom, avec suggestions en cours de frappe.
- **Filtres** : par type (élève, prof, parent, admin), par service (crèche/préparatoire, primaire, CEM, lycée, formations), par classe et par statut du compte (actif, en attente de validation, suspendu).
- **Fiche utilisateur** : informations, classes, enfants liés (pour un parent), dernière connexion.
- **Accès à son espace personnel** : l'admin voit exactement ce que voit l'utilisateur, en lecture seule par défaut ; modifier un contenu demande un clic de confirmation.
- **Modification des permissions** : ajouter ou retirer un mode (prof, élève, parent, admin) pour cet utilisateur.
- **Actions rapides** : suspendre, réactiver, réinitialiser le mot de passe, inscrire dans une classe, retirer d'une classe.

Garde-fous à prévoir :

- Chaque consultation de compte et chaque changement de permission est enregistré dans un **journal d'audit** (qui, quoi, quand).
- Message de confirmation avant tout changement de permission, et notification à l'utilisateur concerné.
- Impossible de retirer le **dernier admin** du système.
- Le compte admin est protégé par une double vérification (code SMS ou application d'authentification), car il ouvre l'accès à toutes les données.
- Si l'admin donne le mode prof à un compte, il le fait depuis la validation d'une demande d'inscription ou depuis la fiche utilisateur, jamais depuis le compte de l'utilisateur lui-même.

### 2.7 Règles d'accès : qui voit quoi

| Mode   | Peut accéder à                                                                                                 | Limites                                                                                         |
| ------ | -------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------- |
| Admin  | Tous les espaces personnels (comme ses assistants)                                                             | Lecture seule par défaut, journal d'audit, double vérification de connexion                     |
| Parent | L'espace personnel de ses enfants (emploi du temps, cours, devoirs, notes), et ses propres cours en mode élève | Pour ses enfants : seulement les enfants liés, en lecture seule ; aucun accès aux autres élèves |
| Prof   | Ses groupes, et dans ces groupes les devoirs rendus et les notes de sa matière                                 | Pas l'espace personnel complet des élèves ; ne voit ni les autres profs ni leurs groupes        |
| Élève  | Son espace et les contenus de ses groupes                                                                      | Rien d'autre                                                                                    |

Pour que ce soit pratique et sûr :

- Le parent ajoute son enfant depuis son espace personnel, en **créant son compte** ou en le reliant avec un **code à 6 chiffres** que l'enfant génère dans son espace. Aucune validation de l'admin.
- Un parent avec plusieurs enfants passe de l'un à l'autre par un sélecteur en haut de son espace (« Voir l'espace de : Sara / Yacine »).
- Chaque prof ne voit que ce qui concerne ses propres groupes.
- Toute consultation par l'admin ou ses assistants est enregistrée.

### 2.8 Groupes de cours (envoi à tout le groupe)

Un groupe n'est **pas un chat** : c'est une classe de cours du prof (par exemple « 2AS Sciences – Groupe A ») qui sert à envoyer des contenus à tous les élèves d'un coup.

**Création du groupe par le prof**

- Nom, service et niveau, matière, horaire habituel, plateforme de séance par défaut (Zoom, Meet ou Telegram) et lien.
- Un prof peut avoir plusieurs groupes ; un élève peut appartenir à plusieurs groupes.

**Inviter des élèves**

- Par **lien d'invitation** ou **code / QR**, à partager où il veut.
- Ou par invitation directe (nom, numéro ou email) depuis la barre de recherche.
- L'élève rejoint en un clic ; le parent lié est prévenu.
- Le lien peut expirer et être désactivé à tout moment.
- Un prof ne peut inviter que des élèves, jamais d'autres profs.

**Envoyer au groupe : un seul clic, tous reçoivent**

- Types d'envoi : cours PDF, exercice, devoir avec date limite, QCM, annonce, lien de séance, invitation à une séance.
- Chaque envoi apparaît dans l'espace de chaque élève du groupe, avec une notification. Le parent peut le recevoir aussi (option).
- Programmation de l'envoi (date et heure), brouillons et modèles réutilisables.
- Les élèves qui rejoignent le groupe plus tard retrouvent les anciens cours.

**Suivi pour le prof**

- « Vu par 18 / 24 », « rendu par 15 / 24 » pour chaque envoi.
- Relance en un clic des élèves qui n'ont pas ouvert ou rendu.
- QCM corrigés automatiquement, avec la note enregistrée dans les notes.

**Pas de chat, mais des échanges utiles**

- L'élève peut poser une question en commentaire privé sous un devoir ou un cours, visible seulement par le prof.
- L'assistant IA répond aux questions de révision.

**Fin d'année**

- Archivage du groupe, avec possibilité de le réutiliser l'année suivante avec ses contenus.

**Données à prévoir** : groupe, membres du groupe, invitation, envoi, accusé de lecture (vu / rendu), lien parent–enfant.

### 2.9 Assistants de l'admin

- L'admin ajoute des **assistants** depuis l'onglet Utilisateurs : il cherche la personne (ou elle s'inscrit d'abord), puis lui donne le mode « Assistant ».
- Un assistant a **les mêmes droits que l'admin** : valider les profs, gérer les utilisateurs et leurs permissions, modifier l'accueil, publier sur les réseaux, traiter les tickets, programmer les séances.
- Retrait des droits d'un assistant en un clic, à tout moment.
- Recommandé, sans compliquer l'usage : chaque action d'un assistant apparaît dans le journal d'audit (qui a fait quoi), le compte de l'admin principal ne peut pas être supprimé par un assistant, et les assistants ont aussi la double vérification de connexion.

### 2.10 Formations et cursus (inspiration Grafikart)

Une formation est un cours structuré en chapitres et leçons, avec vidéo, description et commentaires. Le **cursus** est le parcours d'apprentissage affiché sous forme de graphe interactif.

**Page d'une formation**

- En-tête : titre, service et niveau, matière, nombre de leçons, durée, prof.
- Description, objectifs (« ce que tu vas apprendre ») et prérequis.
- Liste des chapitres et leçons, avec une coche sur ceux déjà terminés.
- Lecteur de leçon : vidéo YouTube intégrée, description de la leçon, PDF et exercices à télécharger, bouton « Leçon suivante ».
- Commentaires sous chaque leçon.
- Progression en pourcentage et bouton « Reprendre là où j'ai arrêté ».

**Vidéos : YouTube, sans créer de système vidéo**

- Le prof colle le lien d'une vidéo YouTube (non répertoriée) dans la leçon ; la plateforme l'affiche avec le lecteur intégré.
- La fin de la vidéo peut marquer la leçon comme vue (API du lecteur YouTube).
- Limite : un lien non répertorié peut être partagé. Pour des contenus vraiment réservés, on pourra passer plus tard à Vimeo ou Bunny Stream.

**Commentaires**

- Un fil de discussion par leçon, avec réponses, visible seulement par les inscrits au groupe ou à la formation (pas public, à cause des mineurs).
- Le prof et l'admin modèrent : épingler, masquer, signaler.
- Le prof est prévenu d'une nouvelle question, et l'élève quand on lui répond.
- Ce n'est pas un chat : chaque fil est rattaché à une leçon précise.

**Cursus : le parcours interactif**

- Une page « Cursus » avec un graphe animé : chaque case est une formation ou un chapitre, reliée aux suivantes dans l'ordre d'apprentissage (exemple : Mathématiques 1AS → 2AS → 3AS, ou Physique : mécanique → électricité → ondes).
- Chaque case est cliquable : un panneau s'ouvre avec la description, la durée, les prérequis, une vidéo de présentation et le bouton « Commencer ».
- Couleurs selon la progression de l'élève : terminé, en cours, à venir.
- Animations douces au survol et à l'ouverture.
- Sur téléphone, le graphe devient un parcours vertical par étapes, facile à parcourir au doigt.
- Le prof ou l'admin crée un cursus en glisser-déposer : ajouter des formations, les relier, les ordonner.

**Technologies** : React Flow (ou D3.js) pour le graphe, Framer Motion pour les animations, lecteur YouTube intégré (iframe + API IFrame), MySQL pour la progression et les commentaires.

**Données à prévoir** : formation, chapitre, leçon (avec lien vidéo), commentaire, progression de l'élève, cursus, étape de cursus (liens entre formations).

**Quand le construire** : dès que les cours PDF et les groupes fonctionnent. Le cursus est aussi une très bonne pièce de démonstration pour convaincre la prof, car il est visuel et valorisant pour son image.

### 2.11 Catalogue, niveaux et filières (inspiration Moodle)

Organisation des contenus, de haut en bas : **Service → Niveau → Filière (الشعبة, au lycée) → Matière → Formation → Chapitre → Leçon ou activité**.

| Service               | Niveaux                                          | Filières                                                       |
| --------------------- | ------------------------------------------------ | -------------------------------------------------------------- |
| Crèche / préparatoire | Préparatoire                                     | Aucune                                                         |
| Primaire              | 1AP à 5AP                                        | Aucune                                                         |
| CEM                   | 1AM à 4AM (préparation du BEM)                   | Aucune                                                         |
| Lycée                 | 1AS, 2AS, 3AS (préparation du BAC)               | Tronc commun en 1AS, filières en 2AS et 3AS (liste ci-dessous) |
| Formations            | Libres : adultes, langues, informatique, soutien | Aucune                                                         |

**Filières du lycée (الشعب)** : la liste suivante est celle utilisée en 2AS, d'après [ce site éducatif algérien](https://eddirasa.com/ens-sec/2as/maths/) et [ce tableau des coefficients](https://eddirasa.com/?p=10345). À confirmer avec la prof et le programme officiel du ministère.

- 1AS : جذع مشترك علوم وتكنولوجيا (tronc commun sciences et technologie) et جذع مشترك آداب (tronc commun lettres).
- 2AS et 3AS, six filières, la première étant : علوم تجريبية (sciences expérimentales).
- رياضيات (mathématiques).
- تقني رياضي (technique mathématique), avec ses options : هندسة كهربائية (génie électrique), هندسة ميكانيكية (génie mécanique), هندسة مدنية (génie civil).
- تسيير واقتصاد (gestion et économie).
- آداب وفلسفة (lettres et philosophie).
- لغات أجنبية (langues étrangères).

**Niveau et filière dans le profil**

- L'élève choisit son niveau et, au lycée, sa filière à l'inscription ou plus tard dans son espace personnel. Un parent le fait pour chacun de ses enfants.
- L'accueil et le catalogue montrent d'abord les formations de sa filière ; il peut changer à tout moment (changement d'année, de filière).

**Ce qu'on reprend de Moodle (en gardant l'essentiel, sans la complexité)**

- Catégories de cours : service, niveau, filière, matière.
- Inscription à un cours par **clé d'inscription** (code), par invitation du prof, ou librement si l'admin l'autorise.
- Activités : devoirs à rendre, quiz (QCM corrigés automatiquement), ressources (PDF, liens, vidéos YouTube), commentaires de leçon.
- **Carnet de notes** par élève et par matière, visible par l'élève et par son parent.
- **Suivi d'avancement** : leçons terminées et barre de progression.
- **Calendrier unique** : séances, dates limites de devoirs, examens.
- **Cohortes** : groupes d'élèves par niveau ou filière, inscrits en une fois à plusieurs cours.
- Badges et certificats de fin de formation.
- Rôle parent, comme dans Moodle : il suit ses enfants.
- Rapports pour l'admin : élèves inactifs, moyennes, devoirs non rendus.

**Ajout de formations par l'admin**

- Onglet **Catalogue** dans l'espace admin (et assistants), avec un bouton « Ajouter une formation ».
- Champs : titre, service, niveau, filière(s) concernée(s) (une formation peut viser plusieurs filières), matière, prof responsable, description, objectifs, prérequis, image, vidéo de présentation, durée, dates, nombre de places (option).
- Visibilité : **publique** (dans le catalogue, inscription libre ou sur demande) ou **privée** (sur invitation du prof).
- Statut : brouillon, publiée, archivée.
- L'admin peut aussi **ajouter ou modifier les services, niveaux, filières et matières** proposés : ce sont des listes modifiables, car les programmes évoluent.
- Le prof gère le contenu de ses formations (leçons, PDF, vidéos) ; seuls l'admin et ses assistants les publient dans le catalogue.
- L'élève parcourt le catalogue avec des filtres (service, niveau, filière, matière) et ouvre la page de la formation (voir 2.10).

**Données à prévoir** : service, niveau, filière, matière, formation (liée à un ou plusieurs niveaux et filières), inscription, cohorte, carnet de notes.

## 3. Phase 1 – Le plus simple, le plus utile, le plus attirant

Objectif : que la prof voie un gain dès la première semaine. Cette phase suffit pour une démo convaincante.

### 3.1 Comptes et menu de modes

- Inscription ouverte à tous en quelques secondes (email ou téléphone), sans validation.
- Dans son espace personnel : lier un compte parent ou, pour un parent, créer le compte de son enfant ou le relier avec un code.
- Menu à trois lignes avec sélecteur de mode.
- Chaque mode a son tableau de bord personnel.
- Seul le mode prof demande une validation de l'admin ou de ses assistants.

### 3.2 Classes et emploi du temps

- Classes par niveau : préparatoire, primaire, CEM (1AM à 4AM), lycée (1AS à 3AS), formations.
- Séances programmées : date, heure, matière, prof, groupe.
- Vue semaine/jour claire pour l'élève et le parent.
- Rappels automatiques avant la séance.

### 3.3 Bouton « Rejoindre la séance »

- Le prof choisit pour chaque séance la plateforme (Zoom, Google Meet ou Telegram) et colle le lien, ou garde un lien permanent pour sa classe.
- Le bouton « Rejoindre » apparaît dans l'emploi du temps de l'élève inscrit à cette classe.
- Au clic, l'élève est **redirigé directement** vers le lien (l'application s'ouvre si elle est installée).
- Aucun système vidéo n'est créé : la plateforme programme, rappelle et redirige.
- La présence est notée au moment du clic.

### 3.4 Cours PDF

- Le prof dépose un PDF dans une classe, avec titre et chapitre.
- L'élève de cette classe peut le lire ou le télécharger.
- Filigrane avec le nom de l'élève pour limiter le partage.

### 3.5 Devoirs et corrections (la fonction « waouh »)

- Le prof publie un exercice avec une date limite.
- L'élève envoie une **photo** de son travail depuis le téléphone (compression automatique avant l'envoi).
- Le prof voit les envois dans une liste, écrit une correction, met une note, renvoie à l'élève.
- L'élève et le parent sont prévenus.

### 3.6 Affichage des notes

- Notes par élève, par matière et par trimestre.
- Moyenne automatique.
- Le parent voit les notes de ses enfants.

## 4. Phase 2 – Vitrine et suivi

### 4.1 Vitrine de réussites (accueil modifiable)

- L'admin modifie l'accueil depuis son espace : ajouter, modifier, réordonner et masquer des blocs.
- Blocs possibles : élève qui a réussi (prénom, résultat, citation, photo), témoignage de parent, résultats d'examens (BEM, BAC), mot de la prof, vidéo de présentation, chiffres clés.
- Modèles prêts à remplir, pour que l'admin n'ait pas à tout composer.
- Aperçu avant publication et date d'affichage programmable.
- Pour les mineurs : accord écrit du parent avant d'afficher un nom ou une photo.

### 4.2 Présence

- Le prof coche la présence, ou la présence est enregistrée quand l'élève clique sur Rejoindre.
- Le parent est averti en cas d'absence.

### 4.3 Notifications

- Rappels de séance, nouvelle note, nouveau cours.
- Canaux : notification dans l'app, email, Telegram (via un bot, lien de compte optionnel), SMS ou WhatsApp pour les parents.

## 5. Phase 3 – Croissance et professionnalisation

### 5.1 Gestion des réseaux sociaux

- Calendrier de publications.
- Programmation de posts sur Facebook et Instagram (API Meta), Telegram ; TikTok selon ce que son API permet.
- Statistiques centralisées.
- Le planning de la plateforme peut générer automatiquement une annonce (« Séance de maths demain 18h »).

### 5.2 Intelligence artificielle

- Assistant de révision pour les élèves, basé sur les cours de la prof.
- Génération d'exercices, de quiz et de corrigés.
- Résumés et fiches de révision.
- Génération de textes de posts et d'idées de contenu pour les réseaux.
- Chatbot qui répond aux parents (tarifs, horaires, inscription).
- Repérage des élèves en baisse de résultats.
- L'IA aide la prof, elle ne remplace jamais sa validation.

### 5.3 Motivation des élèves

- Points, badges, classement par classe (optionnel, pour ne pas décourager).
- Examens blancs et suivi de progression.
- Certificats pour les formations.

### 5.4 Marketing

- Page publique professionnelle (présentation, niveaux, tarifs, témoignages, inscription).
- Parrainage : un élève invite un ami et obtient une réduction.
- SEO et blog.

### 5.5 Application mobile

- Commencer par un site installable (PWA).
- Puis application Flutter si le nombre d'élèves le justifie (notifications push fiables, présence sur Google Play).

### 5.6 Vidéos enregistrées

- Aucun système vidéo n'est créé ni hébergé. Pour garder un replay, la prof ajoute un lien externe (Zoom, Google Drive, YouTube non répertorié) à la séance.

## 6. Structure des données (simplifiée)

- **Utilisateur** : nom, contact, mot de passe, langue.
- **Mode (rôle)** : élève, prof, parent, admin ; un utilisateur peut en avoir plusieurs.
- **Lien parent–enfant** : un parent peut avoir plusieurs enfants, et un élève peut n'avoir aucun parent lié.
- **Service**, **niveau** et **classe** : service, niveau, matière, prof responsable.
- **Inscription** : élève ↔ classe.
- **Séance** : classe, date, heure, plateforme choisie (Zoom, Meet, Telegram), lien.
- **Cours** : fichier PDF, classe, chapitre.
- **Devoir** et **rendu** : énoncé, photo de l'élève, correction, note.
- **Note** : élève, matière, trimestre, valeur.
- **Annonce** et **bloc d'accueil** : texte, image, service concerné, ordre d'affichage, visible ou non.
- **Ticket** : auteur, catégorie, description, statut.
- **Notification** : destinataire, canal, contenu.
- **Publication sociale** : contenu, réseaux cochés, date de programmation, résultat par réseau.
- **Journal d'audit** : qui a consulté ou modifié quoi, et quand.

## 7. Technologies recommandées

| Besoin                      | Choix                                                                                                                                                                                  |
| --------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Interface                   | React + Vite + Tailwind CSS + shadcn/ui (design system inspiré de Vercel), relié à Laravel par Inertia.js (recommandé), arabe/français, RTL                                            |
| Serveur                     | Laravel, framework PHP très répandu (rapide pour ce type d'app)                                                                                                                        |
| Base de données             | MySQL (compatible avec l'hébergement mutualisé)                                                                                                                                        |
| Fichiers (PDF, photos)      | Stockage objet derrière un CDN (Cloudflare R2, Backblaze B2 ou Bunny), avec liens temporaires signés pour les fichiers privés                                                          |
| Séances                     | Liens Zoom, Google Meet ou Telegram (aucun système vidéo)                                                                                                                              |
| Notifications               | Bot Telegram, SMS, WhatsApp Business API, email                                                                                                                                        |
| IA                          | API Claude ou autre modèle                                                                                                                                                             |
| Hébergement                 | Hébergement mutualisé PHP/MySQL au départ (cron chaque minute) + Cloudflare ; petit VPS plus tard si le nombre d'élèves augmente                                                       |
| Mobile                      | PWA d'abord, Flutter ensuite                                                                                                                                                           |
| Permissions et sécurité     | Laravel avec spatie/laravel-permission : rôles + permissions par objet (un parent ne voit que ses enfants, un prof que ses groupes), 2FA par application pour l'admin, journal d'audit |
| Groupes et envois           | File de tâches Laravel (base de données) lancée par un cron chaque minute : un envoi crée une notification par élève, avec envois programmés                                           |
| Recherche d'utilisateurs    | Recherche MySQL (index FULLTEXT) sur le nom, le type et la classe, avec pagination                                                                                                     |
| Notifications dans l'app    | Notifications push du navigateur (PWA) et rafraîchissement léger régulier ; pas de temps réel ni de visioconférence dans l'app                                                         |
| Déploiement et surveillance | Sauvegardes automatiques de MySQL et des fichiers, Sentry pour les erreurs, UptimeRobot, site de test sur sous-domaine, déploiement par GitHub Actions ou envoi manuel                 |
| Cursus interactif           | React Flow (ou D3.js) pour le graphe, Framer Motion pour les animations                                                                                                                |
| Vidéos des leçons           | Lecteur YouTube intégré (liens non répertoriés), API IFrame pour la progression                                                                                                        |

Alternative pour démarrer vite : partir de Moodle et personnaliser.

## 8. Stockage et coûts

- Un PDF fait environ 1 à 10 Mo : 500 PDF ≈ 2,5 Go, c'est peu.
- Les photos de devoirs sont ce qui pèse le plus : compression avant l'envoi (environ 300 Ko), suppression des anciennes photos après quelques mois.
- Coûts à prévoir : hébergement, stockage, SMS/WhatsApp, API IA, abonnement éventuel du prof à Zoom ou Meet.

## 9. Sécurité et conformité

- Modes attribués uniquement par l'admin.
- Une seule session active par compte pour limiter le partage.
- Filigrane sur les PDF.
- Sauvegardes automatiques régulières.
- Protection des mineurs et des données personnelles (loi algérienne 18-07).
- Contrat clair sur la propriété du code, du domaine et des données.

## 10. Risques

- Partage des comptes et copie des PDF.
- Lien de séance transmis à des non-inscrits : le lien n'apparaît que pour les inscrits, et le prof peut le changer à tout moment.
- Résistance au changement : interface très simple, formation de la prof.
- Coûts d'API (IA, SMS) qui augmentent avec le nombre d'élèves.
- Limite ou panne de la plateforme choisie (Zoom gratuit limité en durée) : prévoir un lien de secours (Meet ou Telegram).
- Photos d'élèves mineurs sur l'accueil : accord écrit du parent.
- Projet trop gros dès le départ : respecter les phases.

## 11. Argumentaire pour convaincre la prof

1. Montrer une **démo visuelle** de la phase 1 (planning, bouton Rejoindre, devoirs avec photo).
2. Parler de ses gains : moins d'appels, moins de saisie manuelle, image professionnelle.
3. Proposer un **prix de lancement** ou une période d'essai avec une classe pilote.
4. Faire un mini-audit de sa situation actuelle : combien de temps elle perd par semaine, combien d'impayés.
5. Présenter les phases 2 et 3 comme évolutions futures, pas comme obligations.

## 12. Modèle de revenus possibles pour toi

- Paiement unique pour la création + abonnement mensuel de maintenance et d'hébergement.
- Options payantes ensuite : IA, gestion des réseaux sociaux, application mobile.

## 13. Ordre de développement

1. Comptes, modes et menu
2. Validation des profs et gestion des utilisateurs par l'admin
3. Classes, emploi du temps, bouton Rejoindre (redirection)
4. Cours PDF
5. Devoirs par photo et corrections
6. Notes
7. Accueil modifiable et vitrine de réussites
8. Notifications et tickets
9. Réseaux sociaux et IA
10. Application mobile

## 14. Liste de vérification avant de commencer

- [ ] Inscription ouverte à tous, sans validation, puis liens parent–élève depuis l'espace personnel.
- [ ] Modes : élève, prof, parent, admin, assistant ; élève et parent activés librement, prof et assistant attribués par l'admin.
- [ ] Un prof ne peut pas ajouter d'autres profs, mais peut ajouter des élèves à ses cours.
- [ ] L'admin et ses assistants ont les mêmes droits (recherche, permissions, accueil, réseaux, tickets), avec journal d'audit.
- [ ] Accueil en cases, annonces, services séparés, espace personnel avec mode clair/sombre.
- [ ] L'admin modifie l'accueil et ajoute des exemples de réussite.
- [ ] Tickets pour signaler un problème.
- [ ] Bouton « Rejoindre » : redirection directe vers Zoom, Meet ou Telegram selon le choix du prof, sans système vidéo.
- [ ] Cours PDF, devoirs par photo, corrections, notes.
- [ ] Publication multi-réseaux depuis l'espace admin.
- [ ] Assistant IA à droite sur toutes les pages.

* [ ] Un parent suit ses enfants et peut aussi suivre des cours lui-même avec le même compte ; un élève peut s'inscrire sans parent.

- [ ] Formations avec leçons, vidéo YouTube, description, PDF et commentaires, plus un cursus interactif cliquable.

* [ ] Niveaux et filières du lycée (الشعب) listés et modifiables par l'admin ; catalogue de formations géré par l'admin.

- [ ] Système de test : tests automatiques, parcours E2E, matrice des droits, benchmark et suivi de l'évolution à chaque version (section 15).

* [ ] Seul l'admin accède à tous les espaces ; les parents voient l'espace de leurs enfants ; les profs ne voient que leurs groupes.
* [ ] Groupes de cours avec invitation par lien, code ou QR, et envoi unique à tous les élèves (sans chat).
* [ ] Suivi « vu / rendu » et relance en un clic pour le prof.

### Décisions encore à prendre avec la cliente

- Fournisseur d'IA (ChatGPT, Gemini ou autre) après test.
- Réseaux prioritaires pour la publication automatique.
- Nom de domaine et identité visuelle de « منصة الأستاذة هاجر ».
- Politique pour les élèves mineurs (accord du parent, photos, données conservées).
- Plateformes de séance proposées aux profs et qui paie leurs abonnements éventuels.
- Budget mensuel pour l'hébergement, les SMS et l'IA.

## 15. Tests, benchmark et suivi de l'évolution

Objectif : chaque modification est testée automatiquement, et les performances sont comparées à la version précédente pour repérer tout de suite ce qui casse ou ralentit. Il s'agit ici des tests du logiciel, pas des quiz des élèves (ceux-ci sont dans les activités des formations).

### 15.1 Les niveaux de tests, du plus simple au plus complet

| Niveau                      | Outil                                          | Ce qu'on vérifie                                                                             | Quand                                                 |
| --------------------------- | ---------------------------------------------- | -------------------------------------------------------------------------------------------- | ----------------------------------------------------- |
| Analyse du code             | Pint, Larastan (PHPStan), ESLint, TypeScript   | Style, types, erreurs évidentes                                                              | À chaque push                                         |
| Tests unitaires             | Pest (ou PHPUnit)                              | Règles isolées : droits, créneaux libres en vert, notes, progression                         | À chaque push                                         |
| Tests de fonctionnalité     | Pest + base de test                            | Pages et actions avec la base : inscription, groupes, envois, liens signés, webhook Telegram | À chaque push                                         |
| Tests des composants        | Vitest + Testing Library                       | Formulaires, menu de modes, affichage RTL, graphe du cursus                                  | À chaque push                                         |
| Tests de bout en bout (E2E) | Playwright                                     | Parcours complets dans un vrai navigateur, sur téléphone et ordinateur                       | Parcours clés à chaque pull request, tous chaque nuit |
| Accessibilité et visuel     | axe-core, captures Playwright                  | Contrastes, clavier, mode sombre, arabe RTL                                                  | Chaque nuit                                           |
| Sécurité                    | Matrice rôle × page, composer audit, npm audit | Qui peut voir quoi, dépendances vulnérables                                                  | À chaque push et chaque nuit                          |

### 15.2 Parcours E2E à écrire en priorité

1. Inscription ouverte, choix du niveau et de la filière, rejoindre un groupe avec un code, ouvrir une leçon.
2. Bouton « Rejoindre » : redirection vers le bon lien (Zoom, Meet ou Telegram).
3. Inscription d'un prof, acceptation par l'admin, création d'un groupe, envoi d'un PDF vu par les élèves.
4. Parent : lier un enfant avec un code, voir ses notes, passer en mode élève.
5. Élève sans parent : inscription et suivi d'une formation.
6. Admin : recherche d'un utilisateur, changement de permission, trace dans le journal d'audit, ajout d'un assistant.
7. Admin : ajout d'une formation au catalogue et affichage côté élève, publication multi-réseaux avec de faux réseaux.
8. Cursus : clic sur une case, panneau avec description et vidéo.

Chaque parcours tourne sur mobile et ordinateur, en mode clair et sombre, en français et en arabe (RTL).

### 15.3 La matrice des droits : le test le plus important

Un test automatique parcourt chaque rôle (visiteur, élève, parent, prof, assistant, admin) et chaque page ou action, et vérifie le résultat attendu : accès ou refus. Cas clés :

- Un parent ne voit pas les enfants des autres.
- Un prof ne voit pas les groupes d'un autre prof.
- Un fichier privé sans lien signé est refusé.
- Seuls l'admin et ses assistants ouvrent tous les espaces, et chaque ouverture est enregistrée.

### 15.4 Benchmark : mesurer la performance

| Mesure                  | Outil                                                            | Scénario                                                                                                     | Seuil de départ (à ajuster)                           |
| ----------------------- | ---------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------ | ----------------------------------------------------- |
| Charge du serveur       | k6                                                               | 100 à 300 élèves se connectent, ouvrent l'emploi du temps et cliquent « Rejoindre » dans les mêmes 5 minutes | Temps de réponse p95 sous 1 s, moins de 1 % d'erreurs |
| Envoi à un groupe       | k6 + file de tâches                                              | Un prof envoie un PDF à 200 élèves                                                                           | Tous notifiés en moins de 2 minutes                   |
| Base de données         | Journal des requêtes lentes, EXPLAIN, détection des requêtes N+1 | Catalogue, groupes, recherche admin                                                                          | Aucune requête N+1, moins de 20 requêtes par page     |
| Pages sur téléphone     | Lighthouse CI en réseau 4G lent                                  | Accueil, formation, cursus                                                                                   | Performance mobile ≥ 85                               |
| Poids du code front     | Analyse du bundle Vite                                           | Chaque build                                                                                                 | Pas plus de +10 % sans raison                         |
| Algorithme des créneaux | Test chronométré                                                 | Trouver un créneau pour 300 élèves                                                                           | Moins d'1 seconde                                     |
| Assistant IA            | Banc d'essai de 20 questions de cours, en arabe et en français   | À chaque changement de modèle ou de consigne                                                                 | Note donnée par la prof, comparée à la précédente     |

Ne lance pas de test de charge sur l'hébergement mutualisé de production : c'est souvent interdit et risque de le faire tomber. Utilise un site de test équivalent, ou un volume réduit.

### 15.5 Suivi de l'évolution

- Chaque exécution enregistre ses chiffres (temps de réponse, score Lighthouse, poids du code, requêtes par page, durée des tests) avec la date et la version.
- Chaque résultat est comparé à la dernière version stable : la mise en ligne est **bloquée** si une mesure se dégrade au-delà d'un seuil (par exemple temps de réponse +20 %, code front +10 %, score Lighthouse −5 points).
- Un rapport simple (tableau et courbes) est publié après chaque exécution de nuit, pour voir l'évolution semaine après semaine.
- Tout bug corrigé devient un test, pour qu'il ne revienne pas.

### 15.6 Automatisation avec GitHub Actions

- À chaque push : analyse du code, tests unitaires, de fonctionnalité et de composants (quelques minutes).
- À chaque pull request : plus les parcours E2E essentiels.
- Chaque nuit : tous les E2E (mobile, ordinateur, clair et sombre, FR et AR), accessibilité, visuel, benchmarks, audit des dépendances.
- Avant la mise en ligne : suite complète sur le site de test, puis test rapide après le déploiement en production (accueil, connexion, point de contrôle de santé).
- Les tests tournent dans GitHub Actions, pas sur l'hébergement mutualisé ; on ne déploie que si tout est vert.
- En production : Sentry pour les erreurs, UptimeRobot pour savoir si le site répond.

### 15.7 Données de test

- Jeux de données fictifs (factories et seeders) : élèves, parents, profs, groupes, formations.
- **Jamais de vraies données d'élèves** dans les tests ou sur le site de test, pour protéger les mineurs.

### 15.8 Quand le mettre en place

- Dès le premier jour : analyse du code, tests unitaires et matrice des droits.
- Avec le MVP : les 8 parcours E2E.
- Avant la première vraie classe : un test de charge.
- Ensuite : benchmarks automatiques et suivi de l'évolution à chaque version.
