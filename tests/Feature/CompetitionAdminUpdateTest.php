<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompetitionAdminUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_competition_schedule_and_public_event_reuses_it(): void
    {
        $user = User::factory()->create();

        $competition = Competition::create([
            'name' => 'Basket Competition',
            'slug' => 'basket-competition',
            'event_name' => 'GEN FEST 2026',
            'description' => 'Deskripsi lama',
            'category' => 'Olahraga',
            'price' => 50000,
            'event_date' => '2026-09-20',
            'location' => 'Batam',
            'status' => 'open',
            'unit' => 'peserta',
            'schedule' => json_encode([
                ['time' => '09:00', 'event' => 'Pendaftaran'],
            ]),
        ]);

        $this->actingAs($user)
            ->put(route('admin.competitions.update', $competition->id), [
                'name' => 'Basket Competition',
                'event_name' => 'GEN FEST 2027',
                'description' => 'Deskripsi baru',
                'category' => 'Olahraga',
                'price' => 60000,
                'event_date' => '2026-10-20',
                'location' => 'Batam',
                'status' => 'open',
                'unit' => 'team',
                'min_purchase' => 2,
                'schedule' => json_encode([
                    ['time' => '08:30', 'event' => 'Registrasi dibuka'],
                    ['time' => '10:00', 'event' => 'Babak penyisihan'],
                ]),
            ])
            ->assertRedirect(route('admin.competitions.index'));

        $competition->refresh();

        $this->assertSame('Deskripsi baru', $competition->description);
        $this->assertSame('GEN FEST 2027', $competition->event_name);
        $this->assertSame('team', $competition->unit);
        $this->assertSame(2, $competition->min_purchase);
        $this->assertJsonStringEqualsJsonString(json_encode([
            ['time' => '08:30', 'event' => 'Registrasi dibuka'],
            ['time' => '10:00', 'event' => 'Babak penyisihan'],
        ]), $competition->schedule);

        $response = $this->get(route('competitions.show', $competition->slug));
        $response->assertOk();
        $response->assertSee('Registrasi dibuka');
        $response->assertSee('Babak penyisihan');
        $response->assertSee('/ team');
        $response->assertSee('min. 2');
    }
}
