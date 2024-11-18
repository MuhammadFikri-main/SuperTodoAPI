<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 templates with a user assigned to them
        // Template::factory(5)->create();

        // Create some templates
        Template::create([
            'title' => 'Template 1',
            'user_id' => User::inRandomOrder()->first()->id,
            'is_public' => false, // Make sure this template is public
            'description' => 'A public template example.'
        ]);

        Template::create([
            'title' => 'Template 2',
            'user_id' => User::inRandomOrder()->first()->id,
            'is_public' => true, // Make sure this template is public
            'description' => 'A public template example.'
        ]);

        Template::create([
            'title' => 'Template 3',
            'user_id' => User::inRandomOrder()->first()->id,
            'is_public' => true, // Make sure this template is public
            'description' => 'A public template example.'
        ]);

        Template::create([
            'title' => 'Template 4',
            'user_id' => User::inRandomOrder()->first()->id,
            'is_public' => false, // Make sure this template is public
            'description' => 'A public template example.'
        ]);

        Template::create([
            'title' => 'Template 4',
            'user_id' => User::inRandomOrder()->first()->id,
            'is_public' => true, // Make sure this template is public
            'description' => 'A public template example.'
        ]);

        Template::create([
            'title' => 'Template 6',
            'user_id' => User::inRandomOrder()->first()->id,
            'is_public' => false, // Make sure this template is public
            'description' => 'A public template example.'
        ]);

        Template::create([
            'title' => 'Template 7',
            'user_id' => User::inRandomOrder()->first()->id,
            'is_public' => true, // Make sure this template is public
            'description' => 'A public template example.'
        ]);

        Template::create([
            'title' => 'Template 8',
            'user_id' => User::inRandomOrder()->first()->id,
            'is_public' => true, // Make sure this template is public
            'description' => 'A public template example.'
        ]);

        Template::create([
            'title' => 'Template 9',
            'user_id' => User::inRandomOrder()->first()->id,
            'is_public' => false, // Make sure this template is public
            'description' => 'A public template example.'
        ]);
    }
}
