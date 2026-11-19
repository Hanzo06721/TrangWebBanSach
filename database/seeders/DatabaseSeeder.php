<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            BookSeeder::class,
            PostSeeder::class,
            ConsignmentSeeder::class,
            DonationSeeder::class,
            ExchangeSeeder::class,
            TransactionSeeder::class,
            ReviewSeeder::class,
            FavoriteSeeder::class,
            PostModerationSeeder::class,
        ]);
    }
}
