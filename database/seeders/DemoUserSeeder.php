<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use App\Support\Role;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Super Admin','password' => Hash::make('password'),'role' => Role::ADMIN]
        );

        $teknisi = User::firstOrCreate(
            ['email' => 'teknisi@example.com'],
            ['name' => 'Teknisi Satu','password' => Hash::make('password'),'role' => Role::TEKNISI]
        );

        $clientUser = User::firstOrCreate(
            ['email' => 'client@example.com'],
            ['name' => 'PT Contoh','password' => Hash::make('password'),'role' => Role::CLIENT]
        );

        Client::firstOrCreate(
            ['user_id' => $clientUser->id],
            ['company_name' => 'PT Contoh Sejahtera', 'email' => 'pic@contoh.co.id', 'pic_name' => 'Andi']
        );
    }
}
