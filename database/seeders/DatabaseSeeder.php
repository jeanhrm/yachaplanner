<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UgelSeeder::class,
            PlanSeeder::class,
            ModuleConfigSeeder::class,
            ContextTagSeeder::class,
        ]);
    }
}