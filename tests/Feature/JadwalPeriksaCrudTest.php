<?php

namespace Tests\Feature;

use App\Models\JadwalPeriksa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class JadwalPeriksaCrudTest extends TestCase
{
    use RefreshDatabase;

    private function doctor(string $email = 'doctor-test@example.com'): User
    {
        return User::create([
            'nama' => 'Dokter Uji',
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => 'dokter',
        ]);
    }

    public function test_doctor_sees_only_their_schedule_and_active_sidebar_menu(): void
    {
        $doctor = $this->doctor();
        $otherDoctor = $this->doctor('other-doctor@example.com');
        $this->actingAs($doctor);

        JadwalPeriksa::create([
            'id_dokter' => $doctor->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'status' => 'aktif',
        ]);
        JadwalPeriksa::create([
            'id_dokter' => $otherDoctor->id,
            'hari' => 'Selasa',
            'jam_mulai' => '09:00',
            'jam_selesai' => '12:00',
            'status' => 'aktif',
        ]);

        $this->get(route('jadwal-periksa.index'))
            ->assertOk()
            ->assertSee('Senin')
            ->assertDontSee('Selasa')
            ->assertSee('Jadwal Periksa')
            ->assertSee('border-indigo-400 border-2');
    }

    public function test_doctor_can_create_update_and_delete_schedule_with_notifications(): void
    {
        $doctor = $this->doctor();
        $this->actingAs($doctor);

        $this->post(route('jadwal-periksa.store'), [
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'status' => 'aktif',
        ])->assertRedirect(route('jadwal-periksa.index'))->assertSessionHas('success');

        $jadwal = JadwalPeriksa::where('id_dokter', $doctor->id)->firstOrFail();

        $this->put(route('jadwal-periksa.update', $jadwal), [
            'hari' => 'Selasa',
            'jam_mulai' => '09:00',
            'jam_selesai' => '13:00',
            'status' => 'nonaktif',
        ])->assertRedirect(route('jadwal-periksa.index'))->assertSessionHas('success');

        $this->assertDatabaseHas('jadwal_periksa', [
            'id' => $jadwal->id,
            'hari' => 'Selasa',
            'status' => 'nonaktif',
        ]);

        $this->delete(route('jadwal-periksa.destroy', $jadwal))
            ->assertRedirect(route('jadwal-periksa.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('jadwal_periksa', ['id' => $jadwal->id]);
    }

    public function test_schedule_validation_and_ownership_are_enforced(): void
    {
        $doctor = $this->doctor();
        $this->actingAs($doctor)
            ->post(route('jadwal-periksa.store'), [
                'hari' => 'Funday',
                'jam_mulai' => '13:00',
                'jam_selesai' => '12:00',
                'status' => 'invalid',
            ])
            ->assertSessionHasErrors(['hari', 'jam_selesai', 'status']);

        $otherDoctor = $this->doctor('other-doctor@example.com');
        $foreignSchedule = JadwalPeriksa::create([
            'id_dokter' => $otherDoctor->id,
            'hari' => 'Rabu',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'status' => 'aktif',
        ]);

        $this->get(route('jadwal-periksa.edit', $foreignSchedule))->assertNotFound();
        $this->delete(route('jadwal-periksa.destroy', $foreignSchedule))->assertNotFound();
    }

    public function test_schedule_form_uses_24_hour_time_and_rejects_end_time_before_start(): void
    {
        $doctor = $this->doctor();
        $this->actingAs($doctor)
            ->get(route('jadwal-periksa.create'))
            ->assertOk()
            ->assertSee('type="text" name="jam_mulai"', false)
            ->assertSee('type="text" name="jam_selesai"', false)
            ->assertSee('format 24 jam (HH:MM)');

        $this->post(route('jadwal-periksa.store'), [
            'hari' => 'Jumat',
            'jam_mulai' => '22:10',
            'jam_selesai' => '23:12',
            'status' => 'aktif',
        ])->assertRedirect(route('jadwal-periksa.index'));

        $this->assertDatabaseHas('jadwal_periksa', [
            'id_dokter' => $doctor->id,
            'hari' => 'Jumat',
            'jam_mulai' => '22:10',
            'jam_selesai' => '23:12',
        ]);

        $this->from(route('jadwal-periksa.create'))
            ->post(route('jadwal-periksa.store'), [
                'hari' => 'Jumat',
                'jam_mulai' => '22:10',
                'jam_selesai' => '10:12',
                'status' => 'aktif',
            ])
            ->assertRedirect(route('jadwal-periksa.create'))
            ->assertSessionHasErrors('jam_selesai');
    }
}
