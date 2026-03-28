<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;

/**
 * Seeder principale — crea gli utenti di test e alcuni workshop di esempio.
 *
 * Credenziali di accesso:
 *   Admin:    admin@academy.test    / password
 *   Employee: employee@academy.test / password
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Marco Bianchi',
            'email' => 'admin@academy.test',
        ]);

        User::factory()->employee()->create([
            'name' => 'Sara Colombo',
            'email' => 'employee@academy.test',
        ]);

        Workshop::create([
            'title' => 'Introduzione a Vue 3 e Composition API',
            'description' => "Workshop pratico per imparare le basi di Vue 3 con la Composition API.\n\nArgomenti trattati:\n- Reactive refs e computed\n- Lifecycle hooks\n- Composables personalizzati\n- Integrazione con Inertia.js\n\nPortare il proprio laptop con Node.js installato.",
            'date_time' => now()->addDays(3)->setHour(10)->setMinute(0),
            'duration_minutes' => 120,
            'capacity' => 15,
            'created_by' => $admin->id,
        ]);

        Workshop::create([
            'title' => 'Laravel Eloquent: tecniche avanzate',
            'description' => "Sessione di approfondimento su Eloquent ORM per chi usa gia Laravel quotidianamente.\n\nTemi principali:\n- Query scoping e subquery\n- Relazioni polimorfiche\n- Eager loading ottimizzato\n- Gestione delle transazioni\n\nConsigliata esperienza base con Laravel.",
            'date_time' => now()->addDays(5)->setHour(14)->setMinute(30),
            'duration_minutes' => 90,
            'capacity' => 12,
            'created_by' => $admin->id,
        ]);

        Workshop::create([
            'title' => 'Git workflow e code review efficaci',
            'description' => "Come strutturare un flusso di lavoro Git nel team e condurre code review che migliorano davvero la qualita del codice.\n\nContenuti:\n- Branching strategy (trunk-based vs GitFlow)\n- Commit atomici e messaggi chiari\n- Pull request: cosa guardare e come commentare\n- Risoluzione conflitti senza stress",
            'date_time' => now()->addDays(7)->setHour(9)->setMinute(0),
            'duration_minutes' => 60,
            'capacity' => 20,
            'created_by' => $admin->id,
        ]);

        Workshop::create([
            'title' => 'Tailwind CSS: dal prototipo alla produzione',
            'description' => "Costruiamo insieme un\'interfaccia completa usando Tailwind CSS v4.\n\nProgramma:\n- Utility-first: filosofia e vantaggi\n- Design system con @theme\n- Componenti responsive e dark mode\n- Ottimizzazione per produzione\n\nNessun prerequisito, adatto a tutti i livelli.",
            'date_time' => now()->addDays(10)->setHour(15)->setMinute(0),
            'duration_minutes' => 90,
            'capacity' => 18,
            'created_by' => $admin->id,
        ]);

        Workshop::create([
            'title' => 'Testing in PHP: da zero a coverage completa',
            'description' => "Impariamo a scrivere test che danno fiducia nel codice senza rallentare lo sviluppo.\n\nArgomenti:\n- PHPUnit e Pest: differenze e quando usarli\n- Feature test vs unit test\n- Mocking e database testing\n- Integrazione con CI/CD\n\nPortare un progetto Laravel su cui fare pratica.",
            'date_time' => now()->addDays(14)->setHour(10)->setMinute(0),
            'duration_minutes' => 120,
            'capacity' => 10,
            'created_by' => $admin->id,
        ]);

        Workshop::create([
            'title' => 'Sicurezza web: OWASP Top 10 per sviluppatori',
            'description' => "Panoramica pratica sulle vulnerabilita piu comuni e come prevenirle nel codice di tutti i giorni.\n\nTemi:\n- SQL Injection e XSS\n- CSRF e gestione sessioni\n- Autenticazione sicura\n- Best practice Laravel per la sicurezza\n\nWorkshop consigliato a tutto il team di sviluppo.",
            'date_time' => now()->addDays(18)->setHour(11)->setMinute(0),
            'duration_minutes' => 90,
            'capacity' => 25,
            'created_by' => $admin->id,
        ]);
    }
}
