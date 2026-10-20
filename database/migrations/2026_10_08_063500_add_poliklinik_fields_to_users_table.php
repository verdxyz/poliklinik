<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $addNama = ! Schema::hasColumn('users', 'nama');
        $addAlamat = ! Schema::hasColumn('users', 'alamat');
        $addNoKtp = ! Schema::hasColumn('users', 'no_ktp');
        $addNoHp = ! Schema::hasColumn('users', 'no_hp');
        $addNoRm = ! Schema::hasColumn('users', 'no_rm');
        $addRole = ! Schema::hasColumn('users', 'role');
        $addIdPoli = ! Schema::hasColumn('users', 'id_poli');

        Schema::table('users', function (Blueprint $table) use (
            $addNama,
            $addAlamat,
            $addNoKtp,
            $addNoHp,
            $addNoRm,
            $addRole,
            $addIdPoli,
        ) {
            if ($addNama) {
                $table->string('nama')->nullable();
            }

            if ($addAlamat) {
                $table->string('alamat')->nullable();
            }

            if ($addNoKtp) {
                $table->string('no_ktp')->nullable()->unique();
            }

            if ($addNoHp) {
                $table->string('no_hp')->nullable();
            }

            if ($addNoRm) {
                $table->string('no_rm', 25)->nullable()->unique();
            }

            if ($addRole) {
                $table->enum('role', ['admin', 'dokter', 'pasien'])->default('pasien');
            }

            if ($addIdPoli) {
                $table->foreignId('id_poli')->nullable()->constrained('poli')->nullOnDelete();
            }
        });

        if (Schema::hasColumn('users', 'name') && Schema::hasColumn('users', 'nama')) {
            DB::table('users')
                ->whereNull('nama')
                ->update(['nama' => DB::raw('name')]);
        }
    }

    public function down(): void
    {
        throw new \LogicException('This compatibility migration is forward-only to preserve existing user data.');
    }
};
