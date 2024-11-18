<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all public templates
        $publicTemplates = Template::where('is_public', true)->get();

        // Assign random templates to users
        User::all()->each(function ($user) use ($publicTemplates) {
            $templatesToAttach = $publicTemplates->random(rand(1, 3)); // Attach 1 to 3 templates
            $user->usedTemplates()->attach($templatesToAttach);
        });
    }
}
