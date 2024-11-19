<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // Create a specific test user
       User::factory()->create([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('123456')
        ]);

        // Create additional users
        User::factory(5)->create();

        // Call TemplateSeeder before creating tasks
        $this->call(TemplateSeeder::class);

        // Create tasks (ensure templates exist first)
        Task::factory(10)->create();

        // Seed user-template relationships
        $this->call(UserTemplateSeeder::class);
    }
}
