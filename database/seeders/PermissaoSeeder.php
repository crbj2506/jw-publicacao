<?php

namespace Database\Seeders;

use App\Models\Permissao;
use Illuminate\Database\Seeder;

class PermissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissoes = [
            'Administrador',
            'Ancião',
            'Servo',
            'Publicador'
        ];

        foreach ($permissoes as $nome) {
            Permissao::firstOrCreate(
                ['permissao' => $nome]
            );
        }
    }
}
