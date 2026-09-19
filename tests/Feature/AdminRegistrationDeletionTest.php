<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRegistrationDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_registration_and_activity_is_recorded(): void
    {
        $user = User::factory()->create();
        $competition = Competition::create([
            'name' => 'Test Competition',
            'slug' => 'test-competition',
            'event_name' => 'Test Event',
            'category' => 'Olahraga',
            'price' => 50000,
            'event_date' => '2026-10-01',
            'location' => 'Harbour Bay, Jodoh River, Batu Ampar, Batam City, Riau Islands',
            'status' => 'open',
            'unit' => 'peserta',
        ]);
        $registration = Registration::create([
            'order_code' => 'BRV-DELETE-001',
            'competition_id' => $competition->id,
            'email' => 'peserta@example.com',
            'phone' => '081234567890',
            'participant_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get(route('admin.registrations.show', $registration->order_code))
            ->assertOk();

        $this->actingAs($user)
            ->delete(route('admin.registrations.destroy', $registration->order_code))
            ->assertRedirect(route('admin.registrations.index'));

        $this->assertDatabaseMissing('registrations', ['id' => $registration->id]);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'delete',
            'route_name' => 'admin.registrations.destroy',
        ]);
        $this->assertSame(2, ActivityLog::count());
    }
}
