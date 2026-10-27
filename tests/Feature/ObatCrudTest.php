<?php

namespace Tests\Feature;

use App\Models\Obat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ObatCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'nama' => 'Admin Uji',
            'email' => 'admin-test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_view_obat_list_with_active_sidebar_menu(): void
    {
        $this->actingAs($this->admin());
        Obat::create(['nama_obat' => 'Paracetamol', 'kemasan' => 'Tablet', 'harga' => 5000]);

        $this->get(route('obat.index'))
            ->assertOk()
            ->assertSee('Paracetamol')
            ->assertSee('Manajemen Obat')
            ->assertSee('border-indigo-400 border-2');
    }

    public function test_admin_can_create_update_and_delete_obat_with_notifications(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('obat.store'), [
            'nama_obat' => 'Paracetamol',
            'kemasan' => 'Tablet',
            'harga' => 5000,
        ])->assertRedirect(route('obat.index'))->assertSessionHas('success');

        $obat = Obat::where('nama_obat', 'Paracetamol')->firstOrFail();

        $this->put(route('obat.update', $obat), [
            'nama_obat' => 'Paracetamol Forte',
            'kemasan' => 'Kaplet',
            'harga' => 7500,
        ])->assertRedirect(route('obat.index'))->assertSessionHas('success');

        $this->assertDatabaseHas('obat', ['id' => $obat->id, 'nama_obat' => 'Paracetamol Forte']);

        $this->delete(route('obat.destroy', $obat))
            ->assertRedirect(route('obat.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('obat', ['id' => $obat->id]);
    }

    public function test_obat_input_is_validated_and_non_admin_is_denied(): void
    {
        $this->actingAs($this->admin())
            ->post(route('obat.store'), ['nama_obat' => '', 'kemasan' => '', 'harga' => -1])
            ->assertSessionHasErrors(['nama_obat', 'kemasan', 'harga']);

        $doctor = User::create([
            'nama' => 'Dokter Uji',
            'email' => 'doctor-test@example.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
        ]);

        $this->actingAs($doctor)->get(route('obat.index'))->assertForbidden();
    }
}
