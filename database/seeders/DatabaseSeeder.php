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
            'name' => 'AutoHub Admin',
            'email' => 'admin@autohub.id',
            'password' => bcrypt('password'), // password
        ]);

        $tenantJakarta = \App\Models\Tenant::create([
            'name' => 'AutoHub Jakarta',
            'slug' => 'jakarta',
        ]);

        $tenantBandung = \App\Models\Tenant::create([
            'name' => 'AutoHub Bandung',
            'slug' => 'bandung',
        ]);

        // Attach user to the tenants
        $user->tenants()->attach([
            $tenantJakarta->id => ['role' => 'owner'],
            $tenantBandung->id => ['role' => 'owner'],
        ]);
    }
}
