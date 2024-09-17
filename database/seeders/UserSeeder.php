<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criar um usuário de teste com os dados fornecidos
        User::create([
            'name' => 'Leonardo',
            'email' => 'leonardo.silva.ads1@gmail.com',
            'password' => Hash::make('admin1234'), // Senha criptografada
            'status' => 'active', // Você pode adicionar mais campos conforme sua necessidade
        ]);
    }
}
