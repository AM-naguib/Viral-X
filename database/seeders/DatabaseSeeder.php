<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Plan;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        Role::factory()->create([
            'name' => 'admin',
        ]);
        Role::factory()->create([
            'name' => 'user',
        ]);

        Plan::factory()->create([
            "name" => "admin",
            "description" => "admin",
            "price" => 0,
            "currency" => "USD",
            "features" => "admin",
            "status" => "not active",
        ]);
        Plan::factory()->create([
            "name" => "Free",
            "description" => "No access",
            "price" => 0,
            "currency" => "EGP",
            "features" => "No Access",
            "status" => "not active",
        ]);
        Plan::factory()->create([
            "name" => "Premium",
            "description" => "Full Access For All Tools",
            "price" => 650,
            "currency" => "EGP",
            "features" => "Post in Facebook Groups,Post in Facebook Pages,Post in Twitter Accounts,Scraping Sites,Post Scheduler,Chat Support",
            "status" => "active",
        ]);
        Plan::factory()->create([
            "name" => "Standard",
            "description" => "Full access to Facebook Tools",
            "price" => 500,
            "currency" => "EGP",
            "features" => "Post in Facebook Groups,Post in Facebook Pages,Chat Support",
            "status" => "active",
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'a@a.com',
            'password' => 'a@a.com',
            'role_id' => 1,
            'plan_id' => 1

        ]);
        \App\Models\User::factory()->create([
            'name' => 'FB TEST',
            'email' => 'fb@test.com',
            'password' => '12345678',
            'role_id' => 1,
            'plan_id' => 1

        ]);



    }
}
