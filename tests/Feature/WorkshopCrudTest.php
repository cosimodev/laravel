<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkshopCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_view_workshops_index(): void
    {
        Workshop::factory()->count(3)->create(['created_by' => $this->admin->id]);

        $this->actingAs($this->admin)
            ->get(route('admin.workshops.index'))
            ->assertOk();
    }

    public function test_admin_can_create_workshop(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.workshops.store'), [
                'title' => 'Test Workshop',
                'description' => 'A test workshop description',
                'date_time' => now()->addDays(5)->format('Y-m-d H:i:s'),
                'duration_minutes' => 60,
                'capacity' => 20,
            ])
            ->assertRedirect(route('admin.workshops.index'));

        $this->assertDatabaseHas('workshops', ['title' => 'Test Workshop']);
    }

    public function test_admin_can_edit_workshop(): void
    {
        $workshop = Workshop::factory()->create(['created_by' => $this->admin->id]);

        $this->actingAs($this->admin)
            ->put(route('admin.workshops.update', $workshop), [
                'title' => 'Updated Title',
                'description' => 'Updated description',
                'date_time' => now()->addDays(10)->format('Y-m-d H:i:s'),
                'duration_minutes' => 90,
                'capacity' => 15,
            ])
            ->assertRedirect(route('admin.workshops.index'));

        $this->assertDatabaseHas('workshops', ['title' => 'Updated Title']);
    }

    public function test_admin_can_delete_workshop(): void
    {
        $workshop = Workshop::factory()->create(['created_by' => $this->admin->id]);

        $this->actingAs($this->admin)
            ->delete(route('admin.workshops.destroy', $workshop))
            ->assertRedirect(route('admin.workshops.index'));

        $this->assertDatabaseMissing('workshops', ['id' => $workshop->id]);
    }

    public function test_workshop_requires_valid_data(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.workshops.store'), [])
            ->assertSessionHasErrors(['title', 'description', 'date_time', 'capacity']);
    }

    public function test_workshop_date_must_be_in_future(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.workshops.store'), [
                'title' => 'Past Workshop',
                'description' => 'Description',
                'date_time' => now()->subDay()->format('Y-m-d H:i:s'),
                'duration_minutes' => 60,
                'capacity' => 10,
            ])
            ->assertSessionHasErrors('date_time');
    }
}
