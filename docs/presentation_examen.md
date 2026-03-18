# 🎯 Présentation Examen : ISI BURGER - Gestion des Commandes

**Auteur:** [Marième Ndiaye]  
**Projet:** Application web Laravel pour restaurant ISI BURGER  
**Date:** [Date examen]  
**Repo:** `/home/user/Bureau/isi-burger`

---

## 🥐 Slide 1: Introduction & Objectifs

**ISI BURGER** : Application web complète pour automatiser la gestion des commandes burgers.

### Objectifs :
- Catalogue produits + filtres
- Commandes clients multi-burgers (stock)
- Suivi statuts + paiements cash
- Dashboard stats (Chart.js)
- Emails + PDF factures
- Rôles sécurisés (client/gestionnaire)

### Stack :
```
PHP 8.2 | Laravel 10 | MySQL 8 | Tailwind | Chart.js | Docker | Jenkins CI
```

![Architecture](https://via.placeholder.com/800x400/ffe4c4/8b4513?text=Laravel+MVC+%F0%9F%94%A7)

---

## 🛒 Slide 2: Fonctionnalités Client

**Rôle `client`** (email: `client@isiburger.test` / `password`)

- `/catalogue` : Liste burgers + filtres (nom, prix max, catégorie)
- `/catalogue/{id}` : Détails + commande
- Panier multi-burgers (quantité, stock check → blocage si 0)
- `/mes-commandes` : Historique personnel
- Email confirmation auto

**Exemple commande** : Big Mac x2 + Royale x1 → stock--, created_at timestamp.

---

## 👨‍💼 Slide 3: Fonctionnalités Gestionnaire

**Rôle `gestionnaire`** (email: `gestionnaire@isiburger.test` / `password`)

- **CRUD Burgers** : `/burgers` (create/edit/archive/destroy), champs: nom/prix/image/desc/stock/cat
- **CRUD Catégories** : `/categories`
- **Commandes** : `/admin/commandes` (liste), `/admin/commandes/{id}` (détails)
- **Actions** : Update statut (`en_attente`→`prete`→PDF), Payer (cash, unique)
- Notification email nouvelle commande

---

## 📊 Slide 4: Dashboard & Stats

**/admin/dashboard** (gestionnaire only) :

### KPIs du jour :
| Métrique | Exemple | Query |
|----------|---------|-------|
| Commandes | 12 | `Commande::whereDate('created_at', today())->count()` |
| Payées | 8 | `Paiement::whereDate(today())->count()` |
| Recette | 45 000 FCFA | `Paiement::sum('montant')` |

### Charts interactifs (Chart.js + AJAX) :
- Bar : Commandes/mois (`/admin/dashboard/commandes-data?mois=3`)
- Pie : Produits/catégorie (joins commande_burger/burgers/categories)

**Code clé** (DashboardController.php) :
```php
$recetteJour = Paiement::whereDate('date_paiement', today())->sum('montant');
```

---

## 🗄️ Slide 5: Architecture MVC & Modèles

### Modèles & Relations Eloquent :
```
User (hasMany) → Commande (hasOne) → Paiement
                 ↓ (belongsToMany, pivot: quantite/prix_unitaire)
Category (hasMany) → Burger (belongsTo)
```

**Exemples** :
```php
// Burger.php
public function category() { return $this->belongsTo(Category::class); }
public function commandes() { return $this->belongsToMany(Commande::class, 'commande_burger'); }

// Commande.php
protected $fillable = ['user_id', 'statut'];
```

**Migrations** : 10 tables (users, burgers, categories, commandes, paiements, commande_burger pivot).

---

## 🔗 Slide 6: Routes & Middleware Sécurité

**routes/web.php** (extraits) :
```php
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/catalogue', [CatalogueController::class, 'index']);
    Route::post('/commandes/store', [CommandeController::class, 'store']);
});

Route::middleware(['role:gestionnaire'])->group(function () {
    Route::resource('burgers', BurgerController::class);
    Route::post('/admin/commandes/{commande}/payer', [CommandeController::class, 'enregistrerPaiement']);
});
```

**Sécurité** :
- Spatie Permission (roles/permissions)
- `EnsureUserAppRole` middleware
- Stock check avant commande
- Paiement unique (hasOne)

---

## ✉️ Slide 7: Notifications & PDF

- **Nouvelle commande** : Email gestionnaires (`NouvelleCommandeNotification`)
- **Statut `prete`** : Facture PDF auto (`resources/views/pdf/facture.blade.php` + DomPDF)
- **Emails** : `resources/views/emails/facture.blade.php`

**Exemple** : Commande #123 → PDF avec burgers/quantités/total → joint email.

---

## 🐳 Slide 8: Déploiement Docker

**docker-compose.yml** :
```
app (Laravel+Apache) :8002
db (MySQL) 
phpmyadmin :8082
```

**Lancer** :
```bash
docker compose up -d --build
docker compose exec app php artisan migrate --seed
```

**URLs** :
- App : http://localhost:8002
- DB : http://localhost:8082 (isi_burger / isi_user / passer)

Permissions auto (storage/cache chown www-data).

---

## 🚀 Slide 9: CI/CD - Jenkins & GitHub

- **GitHub Actions** : `.github/workflows/ci.yml` (tests, Docker build)
- **Jenkins** : `Jenkinsfile` + webhook (docs/JENKINS_WEBHOOK.md)
  - Pull branche `Marieme_Ndiaye_burger`
  - composer install + migrate + test + Docker build

**Seeders** : Roles/Users/Burgers/Commandes (comptes test prêts).

---

## 🧪 Slide 10: Tests & Qualité

- **Tests** : `tests/Feature/` (ProfileTest, Auth, ExampleTest)
- **CI** : `php artisan test` dans pipelines
- **Seeders** : DB prête en 1 cmd
- **Validations** : Form Requests (StoreBurgerRequest, etc.)

**Exemple run** : `docker compose exec app php artisan test`

---

## 🎮 Slide 11: Démo Live

1. **Login client** : client@isiburger.test → Catalogue → Commander → Mes commandes
2. **Login gestionnaire** : gestionnaire@isiburger.test → Dashboard stats → Commandes → Update/Payer
3. **Vérif** : Stock--, recette++, charts update, email/PDF

**Astuce examen** : `docker compose up` avant !

---

## ✅ Slide 12: Conclusion & Améliorations

**Réalisé** :
- MVP complet (CRUD, stats, notifs)
- Prod-ready (Docker/CI)
- UX moderne (Tailwind/Charts)

**Améliorations futures** :
- Paiements Stripe/MOMO
- Push notifications
- Stock auto-reappro
- API mobile (Sanctum)

**Merci pour votre attention ! Questions ?** 🥇

---

*Références : README.md, docs/TODO.md. Projet open: /home/user/Bureau/isi-burger*

