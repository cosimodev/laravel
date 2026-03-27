<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->employee(),
            'workshop_id' => Workshop::factory(),
            'status' => 'confirmed',
            'position' => null,
        ];
    }

    public function waiting(int $position = 1): static
    {
        return $this->state(fn () => [
            'status' => 'waiting',
            'position' => $position,
        ]);
    }
}
