<?php

namespace Tests\Feature;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DaftarPoliTest extends TestCase
{
    use RefreshDatabase;

    private function poli(): Poli
    {
        return Poli::create([
            'nama_poli' => 'Poli Umum',
            'keterangan' => 'Poli pemeriksaan umum',
        ]);
    }

    private function doctor(Poli $poli): User
    {
        return User::create([
            'nama' => 'Dokter Uji',
            'email' => 'doctor-daftar@example.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
            'id_poli' => $poli->id,
        ]);
    }

    private function patient(): User
    {
        return User::create([
            'nama' => 'Pasien Uji',
            'email' => 'pasien-daftar@example.com',
            'password' => Hash::make('password'),
            'role' => 'pasien',
            'no_rm' => '202610-001',
        ]);
    }

    public function test_patient_sees_daftar_poli_button_and_can_register_schedule(): void
    {
        $poli = $this->poli();
        $doctor = $this->doctor($poli);
        $patient = $this->patient();

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $doctor->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'status' => 'aktif',
        ]);

        $this->actingAs($patient)
            ->get(route('pasien.dashboard'))
            ->assertOk()
            ->assertSee('Daftar Poli')
            ->assertSee(route('pasien.daftar', [], false));

        $this->actingAs($patient)
            ->post(route('pasien.daftar.submit'), [
                'id_poli' => $poli->id,
                'id_jadwal' => $jadwal->id,
                'keluhan' => 'Demam',
            ])
            ->assertRedirect(route('pasien.dashboard'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('daftar_poli', [
            'id_pasien' => $patient->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Demam',
            'no_antrian' => 1,
        ]);
    }

    public function test_registered_patient_appears_on_doctor_schedule_and_dashboard(): void
    {
        $poli = $this->poli();
        $doctor = $this->doctor($poli);
        $patient = $this->patient();

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $doctor->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'status' => 'aktif',
        ]);

        DaftarPoli::create([
            'id_pasien' => $patient->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Demam',
            'no_antrian' => 1,
        ]);

        $this->actingAs($doctor)
            ->get(route('dokter.dashboard'))
            ->assertOk()
            ->assertSee('Pasien Uji')
            ->assertSee('Senin');

        $this->actingAs($doctor)
            ->get(route('jadwal-periksa.index'))
            ->assertOk()
            ->assertSee('Pasien Uji')
            ->assertSee('1 pasien');

        $this->actingAs($doctor)
            ->get(route('periksa-pasien.index'))
            ->assertOk()
            ->assertSee('Pasien Uji')
            ->assertSee('Senin');
    }
}
