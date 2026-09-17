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

    public function test_admin_dashboard_and_detail_handles_string_order_ids_and_string_timestamps(): void
    {
        $user = User::factory()->create();

        session(['admin_registrations' => [[
            'id' => 'BRV-20260913-GRP1',
            'order_code' => 'BRV-20260913-GRP1',
            'email' => 'group@example.com',
            'phone' => '081234567890',
            'participant_count' => 15,
            'participants' => collect(range(1, 15))->map(fn ($i) => [
                'name' => 'Peserta ' . $i,
                'date_of_birth' => '2000-01-' . ($i < 10 ? '0' . $i : $i),
            ])->all(),
            'total_amount' => 1500000,
            'status' => 'paid',
            'payment' => ['status' => 'paid'],
            'competition' => ['name' => 'Basket Competition'],
            'tickets' => collect(range(1, 15))->map(fn ($i) => [
                'ticket_code' => 'BRV-TKT-' . $i,
                'participant' => 'Peserta ' . $i,
                'status' => 'active',
            ])->all(),
            'created_at' => now()->toDateTimeString(),
        ]]]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('BRV-20260913-GRP1');

        $this->actingAs($user)
            ->get(route('admin.registrations.show', 'BRV-20260913-GRP1'))
            ->assertOk()
            ->assertSee('BRV-20260913-GRP1');
    }

    public function test_admin_ticket_page_shows_registered_tickets_and_not_demo_rows(): void
    {
        $user = User::factory()->create();

        session(['admin_registrations' => [[
            'id' => 'BRV-20260913-TCK1',
            'order_code' => 'BRV-20260913-TCK1',
            'email' => 'ticket@example.com',
            'phone' => '081234567890',
            'participant_count' => 2,
            'participants' => [
                ['name' => 'Peserta A', 'date_of_birth' => '2000-01-01'],
                ['name' => 'Peserta B', 'date_of_birth' => '2000-01-02'],
            ],
            'competition' => ['name' => 'Basket Competition'],
            'status' => 'paid',
            'payment' => ['status' => 'paid'],
            'tickets' => [
                ['ticket_code' => 'BRV-TKT-A1B2C3', 'participant' => 'Peserta A', 'status' => 'active'],
                ['ticket_code' => 'BRV-TKT-D4E5F6', 'participant' => 'Peserta B', 'status' => 'active'],
            ],
            'created_at' => now()->toDateTimeString(),
        ]]]);

        $this->actingAs($user)
            ->get(route('admin.tickets.index'))
            ->assertOk()
            ->assertSee('BRV-TKT-A1B2C3')
            ->assertSee('Peserta A')
            ->assertDontSee('BRV-TKT-A8F92K');
    }

    public function test_registration_requires_admin_minimum_purchase(): void
    {
        $competition = Competition::create([
            'name' => 'Team Battle',
            'slug' => 'team-battle',
            'event_name' => 'GEN FEST 2026',
            'category' => 'Olahraga',
            'price' => 250000,
            'min_purchase' => 3,
            'event_date' => '2026-10-01',
            'location' => 'Batam',
            'status' => 'open',
            'unit' => 'team',
        ]);

        $response = $this->get(route('register.form', $competition->slug));
        $response->assertOk();
        $response->assertSee('min. 3');

        $this->post(route('register.store', $competition->slug), [
            'email' => 'peserta@example.com',
            'phone' => '81234567890',
            'participant_count' => 2,
            'participants' => [
                ['name' => 'A', 'date_of_birth' => '2000-01-01'],
                ['name' => 'B', 'date_of_birth' => '2000-01-02'],
            ],
        ])->assertSessionHasErrors(['participant_count']);

        $response = $this->post(route('register.store', $competition->slug), [
            'email' => 'peserta2@example.com',
            'phone' => '81234567891',
            'participant_count' => 25,
            'participants' => collect(range(1, 25))->map(fn ($i) => [
                'name' => 'Peserta ' . $i,
                'date_of_birth' => '2000-01-0' . ($i > 9 ? 9 : $i),
            ])->toArray(),
        ]);

        $response->assertRedirect();
        $this->assertMatchesRegularExpression('/\/register\/review\/BRV-' . date('Ymd') . '-[A-Z0-9]{4}$/', $response->headers->get('Location'));

        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('peserta2@example.com');
    }

    public function test_checkout_blocks_registration_below_minimum_purchase(): void
    {
        $competition = Competition::create([
            'name' => 'Team Battle',
            'slug' => 'team-battle',
            'event_name' => 'GEN FEST 2026',
            'category' => 'Olahraga',
            'price' => 250000,
            'min_purchase' => 3,
            'event_date' => '2026-10-01',
            'location' => 'Batam',
            'status' => 'open',
            'unit' => 'team',
        ]);

        session(['registration' => [
            'order_code' => 'BRV-20260913-TEST',
            'competition_slug' => $competition->slug,
            'competition' => (object) [
                'name' => $competition->name,
                'event_name' => $competition->event_name,
                'price' => $competition->price,
                'location' => $competition->location,
                'event_date' => $competition->event_date,
                'category' => $competition->category,
                'slug' => $competition->slug,
                'unit' => $competition->unit,
                'min_purchase' => $competition->min_purchase,
            ],
            'email' => 'peserta@example.com',
            'phone' => '081234567890',
            'participant_count' => 2,
            'participants' => [
                ['name' => 'A', 'date_of_birth' => '2000-01-01'],
                ['name' => 'B', 'date_of_birth' => '2000-01-02'],
            ],
            'total_amount' => 500000,
            'status' => 'pending',
        ]]);

        $this->post(route('payment.create', 'BRV-20260913-TEST'))
            ->assertRedirect(route('register.form', $competition->slug))
            ->assertSessionHas('error', 'Minimal pembelian untuk event ini adalah 3 team. Anda belum memenuhi jumlah minimum.');
    }
}
