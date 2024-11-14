<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@ellicenciado.app',
            'password' => Hash::make(config('app.admin-password')),
        ]);

        $sellers = [
            'Leonardo',
            'Gema',
            'Maira',
            'Edita'
        ];

        foreach($sellers as $name){
            User::factory()->create([
                'name' => ucfirst($name),
                'email' => "$name@ellicenciado.app",
                'password' => '12345678',
            ]);
        }
    }
}
