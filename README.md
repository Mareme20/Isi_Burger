# ISI BURGER - Gestion des Commandes (Laravel)

Application web Laravel pour la gestion des commandes du restaurant **ISI BURGER**.

## Objectif
Automatiser:
- la gestion des burgers (catalogue + stock),
- les commandes client,
- les paiements en caisse,
- le suivi des statuts,
- les statistiques,
- l'envoi d'emails (confirmation + facture PDF).

## Stack technique
- PHP 8.2
- Laravel 10
- MySQL 8
- Spatie Laravel Permission (roles)
- DomPDF (factures PDF)
- Chart.js (statistiques)
- Docker / Docker Compose
- GitHub Actions (CI)
- Jenkinsfile + webhook GitHub (option DevOps interne)

## Fonctionnalites principales

### 1) Produits (Burgers)
- Ajouter / Modifier / Archiver / Supprimer un burger
- Champs: `nom`, `prix`, `image`, `description`, `stock`, `category_id`
- Blocage des commandes si stock insuffisant

### 2) Commandes

#### Cote client
- Consulter le catalogue
- Filtrer par nom, prix max, categorie
- Voir le detail d'un burger
- Commander plusieurs burgers
- Voir ses commandes

#### Cote gestionnaire
- Lister toutes les commandes
- Voir les details d'une commande
- Modifier le statut:
  - `en_attente`
  - `en_preparation`
  - `prete` (envoi facture PDF par email)
  - `annulee`
  - `payee` (apres encaissement)

### 3) Paiements
- Encaissement en especes par le gestionnaire
- Une commande ne peut etre payee qu'une seule fois

### 4) Authentification / Roles
- Role `gestionnaire`
- Role `client`
- Controle d'acces par middleware Spatie

### 5) Statistiques
- Commandes du jour
- Commandes payees du jour
- Recette journaliere
- Nombre de commandes par mois (Chart.js)
- Produits par categorie (Chart.js)

### 6) Notifications / Automatisation
- Email de confirmation apres commande
- Notification email aux gestionnaires pour nouvelle commande
- Facture PDF envoyee quand la commande passe a `prete`

---

## Installation locale (sans Docker)

### Prerequis
- PHP 8.2+
- Composer
- MySQL
- Node.js (si build frontend)

### Etapes
```bash
git clone https://github.com/Mareme20/Isi_Burger.git
cd Isi_Burger

cp .env.example .env
composer install
php artisan key:generate
```

Configurer la base dans `.env`, puis:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Application: `http://127.0.0.1:8002`

---

## Installation avec Docker

### Lancer les services
```bash
docker compose up -d --build
```

### Migrations + seeders
```bash
docker compose exec app php artisan migrate --seed --force
docker compose exec app php artisan optimize:clear
```

### URL utiles
- App Laravel: `http://localhost:8002`
- phpMyAdmin: `http://localhost:8082`

### Notes Docker importantes
- Les permissions `storage` et `bootstrap/cache` sont corrigees au demarrage du conteneur `app`.
- Les images burgers sont servies via route Laravel `media/burger/{id}/image`.

---

## Comptes de test (seeders)
- Gestionnaire:
  - Email: `gestionnaire@isiburger.test`
  - Mot de passe: `password`
- Client:
  - Email: `client@isiburger.test`
  - Mot de passe: `password`

---

## Scripts utiles

### Laravel
```bash
php artisan migrate
php artisan db:seed
php artisan test
php artisan optimize:clear
```

### Docker
```bash
docker compose up -d --build
docker compose down
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
docker compose logs -f app
```

---

## CI - GitHub Actions
Fichier: `.github/workflows/ci.yml`

Pipeline:
1. Checkout code
2. Setup PHP 8.2
3. Install dependencies Composer
4. Prepare Laravel (SQLite CI)
5. Migrate
6. Tests (`php artisan test`)
7. Build image Docker

---

## Jenkins + Webhook GitHub (DevOps interne)
- Pipeline Jenkins: `Jenkinsfile`
- Guide webhook local: `docs/JENKINS_WEBHOOK.md`

Le pipeline Jenkins couvre:
- Pull depuis GitHub (branche `nom_prenom_burger`)
- Installation des dependances Laravel
- Preparation de l'app
- Migrations
- Build image Docker

---

## Structure principale
- `app/Http/Controllers/`
  - `BurgerController.php`
  - `CatalogueController.php`
  - `CommandeController.php`
  - `DashboardController.php`
- `resources/views/`
  - `catalogue/`
  - `admin/commandes/`
  - `pdf/facture.blade.php`
- `database/seeders/`
  - `RoleSeeder.php`
  - `UserSeeder.php`
  - `BurgerSeeder.php`
  - `CommandeSeeder.php`

---

## Depannage rapide

### Images burgers ne s'affichent pas (Docker)
```bash
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan storage:link
```
Puis recharge navigateur (`Ctrl+F5`).

### Erreur role `gestionnaire` inexistant
```bash
docker compose exec app php artisan db:seed --class=RoleSeeder --force
docker compose exec app php artisan permission:cache-reset
```

### Erreur 403 role
- Verifier que l'utilisateur a role `client` ou `gestionnaire`.

### Erreur logs permissions
- Redemarrer `app` (les permissions sont appliquees au boot):
```bash
docker compose restart app
```

---
pour lancer ngrok
ngrok http 8000

## Auteur
Projet realise pour le module interne ISI - Gestion des commandes ISI BURGER.
