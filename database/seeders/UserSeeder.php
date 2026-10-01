<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallets;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Akun contoh UNTUK DEVELOPMENT SAJA (password: "password").
 * Jangan jalankan seeder ini di production.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'superadmin@ppob.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'status' => 'active',
            ]
        );

        // Wallet hanya untuk agen.
        $agen = User::firstOrCreate(
            ['email' => 'agen@ppob.test'],
            [
                'name' => 'Agen Contoh',
                'password' => Hash::make('password'),
                'role' => 'agen',
                'status' => 'active',
                'phone' => '081200000001',
            ]
        );

        Wallets::firstOrCreate(
            ['user_id' => $agen->id],
            ['balance' => 0, 'held_balance' => 0]
        );

        User::firstOrCreate(
            ['email' => 'user@ppob.test'],
            [
                'name' => 'User Contoh',
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
            ]
        );
    }
}
