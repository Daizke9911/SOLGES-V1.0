<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            SectorSistemaSeeder::class,
            EstatusSeeder::class,
            CategoriasSeeder::class,
            SubCategoriasSeeder::class,
            SecurityQuestionSeeder::class,
            RoleSeeder::class,
            GeneroSeeder::class,
            NacionalidadSeeder::class,
            InstitucionSeeder::class,
            TipoReunionSeeder::class,
            ParroquiaSeeder::class,
            ComunidadesSeeder::class,
            UserSeeder::class,
/*             ConcejalesSeeder::class,
            SolicitudesSeeder::class,
            ReunionSeeder::class, */

        ]);
    }
}

