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
        // Utente admin — può creare e gestire i workshop
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@academy.test',
        ]);

        // Utente employee — può iscriversi ai workshop
        User::factory()->employee()->create([
            'name' => 'Employee User',
            'email' => 'employee@academy.test',
        ]);

        // 5 workshop di esempio creati dall'admin, con date future casuali
        Workshop::factory()->count(5)->create([
            'created_by' => $admin->id,
        ]);
    }
}
