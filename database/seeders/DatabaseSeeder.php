<?php

namespace Database\Seeders;

use App\Models\User;
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
        // $this->call(SomeSeeder::class);

        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@saas.test',
            'password' => bcrypt('password'), // password
        ]);

        $tenantJakarta = \App\Models\Tenant::create([
            'name' => 'Tenant A',
            'slug' => 'jakarta',
        ]);

        $tenantBandung = \App\Models\Tenant::create([
            'name' => 'Tenant B',
            'slug' => 'bandung',
        ]);

        // Attach user to the tenants
        $user->tenants()->attach([
            $tenantJakarta->id => ['role' => 'owner'],
            $tenantBandung->id => ['role' => 'owner'],
        ]);
    }
}
