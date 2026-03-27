<?php

namespace Tests\Feature;

use App\Mail\WorkshopReminder;
use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReminderCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_reminder_sends_emails_for_tomorrow_workshops(): void
    {
        Mail::fake();

        $workshop = Workshop::factory()->create([
            'date_time' => now()->addDay()->setHour(10),
        ]);

        $registration = Registration::factory()->create([
            'workshop_id' => $workshop->id,
            'status' => 'confirmed',
        ]);

        $this->artisan('academy:remind')
            ->expectsOutputToContain('1 email inviate')
            ->assertSuccessful();

        Mail::assertSent(WorkshopReminder::class, 1);
    }

    public function test_reminder_ignores_workshops_not_tomorrow(): void
    {
        Mail::fake();

        Workshop::factory()->create([
            'date_time' => now()->addDays(3),
        ]);

        $this->artisan('academy:remind')
            ->expectsOutputToContain('Nessun workshop')
            ->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_reminder_only_sends_to_confirmed(): void
    {
        Mail::fake();

        $workshop = Workshop::factory()->create([
            'date_time' => now()->addDay()->setHour(14),
        ]);

        Registration::factory()->create([
            'workshop_id' => $workshop->id,
            'status' => 'confirmed',
        ]);

        Registration::factory()->waiting(1)->create([
            'workshop_id' => $workshop->id,
        ]);

        $this->artisan('academy:remind')->assertSuccessful();

        Mail::assertSent(WorkshopReminder::class, 1);
    }
}
