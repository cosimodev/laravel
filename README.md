# Internal Academy

Piattaforma di formazione aziendale per la gestione di workshop interni.

## Stack

- **Backend:** Laravel 13 + PHP 8.4
- **Frontend:** Vue 3 + Inertia.js + Tailwind CSS v4
- **Database:** SQLite (dev) / MySQL (prod)
- **Auth:** Laravel Breeze

## Installazione

```bash
git clone https://github.com/cosimodev/laravel.git internal-academy
cd internal-academy

composer install
cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed

npm install
npm run build
```

## Utenti di test (seeder)

| Ruolo    | Email                  | Password   |
|----------|------------------------|------------|
| Admin    | admin@academy.test     | password   |
| Employee | employee@academy.test  | password   |

## Comandi

```bash
# Esegui i test
php artisan test

# Invia reminder per workshop di domani
php artisan academy:remind

# Dev server frontend
npm run dev
```

## Funzionalita

- **Auth con ruoli** (admin/employee) e redirect post-login basato sul ruolo
- **CRUD Workshop** (Admin): creazione, modifica, eliminazione, lista paginata
- **Registrazione Workshop** (Employee): iscrizione, waiting list FIFO, cancellazione
- **No sovrapposizioni orarie**: blocco registrazione se conflitto temporale
- **Waiting List FIFO**: auto-promozione alla cancellazione di un confermato
- **Dashboard Admin**: statistiche in tempo reale con polling ogni 5s
- **Comando Reminder**: `php artisan academy:remind` per email promemoria
- **47 test** con copertura completa su auth, CRUD, registrazioni, overlap, reminder

## Struttura

```
app/
  Enums/UserRole.php          # Enum admin|employee
  Models/Workshop.php         # Modello workshop con logica waiting list
  Models/Registration.php     # Modello registrazione
  Http/Controllers/Admin/     # CRUD workshop + dashboard stats
  Http/Controllers/Employee/  # Vista workshop + registrazione
  Http/Middleware/EnsureRole.php
  Console/Commands/AcademyRemind.php
  Mail/WorkshopReminder.php

resources/js/Pages/
  Admin/Workshops/            # Index, Form, Show
  Admin/Dashboard.vue         # Stats + grafico
  Employee/Workshops/         # Index, Show (con registrazione)
```
