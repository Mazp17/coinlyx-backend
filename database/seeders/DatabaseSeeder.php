<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Miguel',
            'last_name' => 'Zapata',
            'email' => 'admin@coinlyx.com',
            'password' => Hash::make("admin123"),
            'api_token' => Str::random(60),
            'role' => 'admin'
        ]);

        User::factory(50)
            ->has(
                Account::factory()
                    ->has(
                        Transaction::factory()
                            ->count(10)
                        )
                    ->count(1)
            )
            ->create();
    }
}
