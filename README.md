# TaskManager (Laravel MVP)

Application interne simple de gestion de taches avec isolation par utilisateur.

## Fonctionnalites

- Authentification : inscription, connexion, deconnexion
- CRUD complet des taches :
  - creer
  - lister
  - modifier
  - supprimer (avec confirmation)
- Changement rapide de statut depuis la liste
- Filtrage des taches :
  - par statut
  - par categorie
- Pagination de la liste
- Dashboard avec compteur par statut
- Securite :
  - toutes les routes protegees par `auth`
  - verification de propriete avant update/delete/status (`403` si non proprietaire)

## Stack technique

- Laravel 13
- PHP 8.3
- MySQL
- Blade + Eloquent ORM
- Laravel Breeze (auth)
- Laravel Telescope (debug)
- Laravel Debugbar (dev)

## Prerequis

- PHP >= 8.3
- Composer
- Node.js + npm
- MySQL
- Laragon (ou autre stack locale)

## Installation

```bash
git clone https://github.com/it0h4r/TaskManager.git
cd TaskManager
composer install
npm install
cp .env.example .env
php artisan key:generate
```

## Configuration base de donnees (.env)

Configurer les variables DB dans `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskmanager
DB_USERNAME=root
DB_PASSWORD=
```

## Migrations et seeders

```bash
php artisan migrate
php artisan db:seed
```

Seeder categorie :
- Travail
- Personnel
- Urgent
- Formation

Compte test (si non cree manuellement) :
- email: `test@example.com`
- password: `password`

## Lancement du projet

```bash
npm run build
php artisan serve
```

Puis ouvrir :
- `http://127.0.0.1:8000`

## Routes principales

- `/login`
- `/register`
- `/tasks`
- `/tasks/create`
- `/dashboard`
- `/telescope` (en environnement dev)

## Securite appliquee

- `@csrf` sur tous les formulaires
- validation `request()->validate(...)`
- isolation des donnees utilisateur :
  - un utilisateur voit seulement ses taches
- protection de propriete :
  - `edit`, `update`, `destroy`, `updateStatus` verifient `task.user_id === auth()->id()`
  - sinon `abort(403)`

## Debugging (evaluation)

### Telescope
- Ouvrir `/telescope`
- Verifier :
  - requete HTTP (`POST /tasks`)
  - payload
  - queries SQL
  - exceptions

### Debugbar
- Verifier le nombre de requetes sur `/tasks`
- Detecter N+1
- Eager loading applique dans la liste : `Task::with('category')`

## Branches Git

- `feature/auth`
- `feature/database`
- `feature/task-crud`
- `feature/filters`
- `feature/debugging`
- `feature/readme`

## Ameliorations possibles

- Policies Laravel pour la propriete (au lieu de checks manuels)
- Tests Feature supplementaires pour CRUD + filtres + securite
- UI/UX plus avancee (badges statut, tri, recherche)
