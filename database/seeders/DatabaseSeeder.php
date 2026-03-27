<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@academy.test',
        ]);

        $employee = User::factory()->employee()->create([
            'name' => 'Employee User',
            'email' => 'employee@academy.test',
        ]);

        // Create some sample workshops
        Workshop::factory()->count(5)->create([
            'created_by' => $admin->id,
        ]);
    }
}
