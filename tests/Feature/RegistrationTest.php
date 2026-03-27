<?php

namespace Tests\Feature;

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private User $employee;
    private Workshop $workshop;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employee = User::factory()->employee()->create();
        $this->workshop = Workshop::factory()->create(['capacity' => 2]);
    }

    public function test_employee_can_register_to_workshop(): void
    {
        $this->actingAs($this->employee)
            ->post(route('employee.workshops.register', $this->workshop))
            ->assertRedirect();

        $this->assertDatabaseHas('registrations', [
            'user_id' => $this->employee->id,
            'workshop_id' => $this->workshop->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_registration_goes_to_waiting_when_full(): void
    {
        // Fill capacity
        Registration::factory()->count(2)->create([
            'workshop_id' => $this->workshop->id,
            'status' => 'confirmed',
        ]);

        $this->actingAs($this->employee)
            ->post(route('employee.workshops.register', $this->workshop))
            ->assertRedirect();

        $this->assertDatabaseHas('registrations', [
            'user_id' => $this->employee->id,
            'workshop_id' => $this->workshop->id,
            'status' => 'waiting',
            'position' => 1,
        ]);
    }

    public function test_cannot_register_twice(): void
    {
        Registration::factory()->create([
            'user_id' => $this->employee->id,
            'workshop_id' => $this->workshop->id,
        ]);

        $this->actingAs($this->employee)
            ->post(route('employee.workshops.register', $this->workshop));

        $this->assertDatabaseCount('registrations', 1);
    }

    public function test_fifo_promotion_on_cancel(): void
    {
        // Fill with 2 confirmed
        $confirmed = Registration::factory()->create([
            'workshop_id' => $this->workshop->id,
            'status' => 'confirmed',
        ]);

        Registration::factory()->create([
            'workshop_id' => $this->workshop->id,
            'status' => 'confirmed',
        ]);

        // Add waiting
        $waiting = Registration::factory()->waiting(1)->create([
            'workshop_id' => $this->workshop->id,
        ]);

        // Cancel first confirmed
        $this->actingAs($confirmed->user)
            ->delete(route('employee.registrations.destroy', $confirmed))
            ->assertRedirect();

        $waiting->refresh();
        $this->assertEquals('confirmed', $waiting->status);
        $this->assertNull($waiting->position);
    }

    public function test_cancel_registration(): void
    {
        $registration = Registration::factory()->create([
            'user_id' => $this->employee->id,
            'workshop_id' => $this->workshop->id,
        ]);

        $this->actingAs($this->employee)
            ->delete(route('employee.registrations.destroy', $registration))
            ->assertRedirect();

        $this->assertDatabaseMissing('registrations', ['id' => $registration->id]);
    }

    public function test_overlap_prevents_registration(): void
    {
        $existingWorkshop = Workshop::factory()->create([
            'date_time' => now()->addDays(5)->setHour(10)->setMinute(0),
            'duration_minutes' => 120,
        ]);

        Registration::factory()->create([
            'user_id' => $this->employee->id,
            'workshop_id' => $existingWorkshop->id,
            'status' => 'confirmed',
        ]);

        $overlappingWorkshop = Workshop::factory()->create([
            'date_time' => now()->addDays(5)->setHour(11)->setMinute(0),
            'duration_minutes' => 60,
        ]);

        $this->actingAs($this->employee)
            ->post(route('employee.workshops.register', $overlappingWorkshop))
            ->assertRedirect();

        $this->assertDatabaseMissing('registrations', [
            'user_id' => $this->employee->id,
            'workshop_id' => $overlappingWorkshop->id,
        ]);
    }

    public function test_no_overlap_allows_registration(): void
    {
        $existingWorkshop = Workshop::factory()->create([
            'date_time' => now()->addDays(5)->setHour(10)->setMinute(0),
            'duration_minutes' => 60,
        ]);

        Registration::factory()->create([
            'user_id' => $this->employee->id,
            'workshop_id' => $existingWorkshop->id,
            'status' => 'confirmed',
        ]);

        $nonOverlappingWorkshop = Workshop::factory()->create([
            'date_time' => now()->addDays(5)->setHour(14)->setMinute(0),
            'duration_minutes' => 60,
        ]);

        $this->actingAs($this->employee)
            ->post(route('employee.workshops.register', $nonOverlappingWorkshop))
            ->assertRedirect();

        $this->assertDatabaseHas('registrations', [
            'user_id' => $this->employee->id,
            'workshop_id' => $nonOverlappingWorkshop->id,
        ]);
    }
}
