# Conception (MCD / MLD) - TaskManager (MVP)

## Objectif
Application interne simple: chaque employe se connecte et gere ses propres taches.
Isolation obligatoire: un utilisateur ne voit/modifie/supprime que ses taches.

---

## MCD (Modele Conceptuel de Donnees)

### Entites

**User**
- id
- name
- email
- password

**Category**
- id
- name

**Task**
- id
- title
- description
- status
- due_date
- created_at
- updated_at

### Relations + cardinalites

- Un **User** possede **0..n Task**
- Une **Task** appartient a **1 User**

- Une **Category** possede **0..n Task**
- Une **Task** appartient a **1 Category**

Representation:

- USER (1) ---- (0,n) TASK
- CATEGORY (1) - (0,n) TASK

---

## MLD (Modele Logique de Donnees - MySQL)

### Table: users (Laravel par defaut)
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- name VARCHAR(255) NOT NULL
- email VARCHAR(255) NOT NULL UNIQUE
- email_verified_at TIMESTAMP NULL (si active)
- password VARCHAR(255) NOT NULL
- remember_token VARCHAR(100) NULL
- created_at TIMESTAMP NULL
- updated_at TIMESTAMP NULL

### Table: categories
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- name VARCHAR(255) NOT NULL
- created_at TIMESTAMP NULL
- updated_at TIMESTAMP NULL

Notes:
- Categories globales (ex: Travail, Personnel, Urgent, Formation).
- Option possible plus tard: categories par user (ajouter user_id).

### Table: tasks
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- user_id BIGINT UNSIGNED NOT NULL (FK -> users.id)
- category_id BIGINT UNSIGNED NOT NULL (FK -> categories.id)
- title VARCHAR(255) NOT NULL
- description TEXT NULL
- status VARCHAR(50) NOT NULL
- due_date DATE NULL
- created_at TIMESTAMP NULL
- updated_at TIMESTAMP NULL

Contraintes:
- FK tasks.user_id references users.id (on delete cascade recommande)
- FK tasks.category_id references categories.id (on delete restrict ou cascade, a choisir)

Index recommande:
- index(tasks.user_id)
- index(tasks.category_id)
- index(tasks.status)

---

## Regles metier (a respecter dans le code)

### Isolation / securite
- Toutes les requetes "taches" sont filtrees par l'utilisateur connecte:
  - `where('user_id', auth()->id())`
- Avant update/delete/changement de statut:
  - `if ($task->user_id !== auth()->id()) abort(403);`

### Statuts (valeurs stockees)
On stocke des valeurs stables (en anglais) et on affiche en francais dans Blade:
- todo -> A faire
- in_progress -> En cours
- done -> Termine

---

## Mapping Eloquent attendu
- User hasMany Task
- Task belongsTo User
- Task belongsTo Category
- Category hasMany Task

