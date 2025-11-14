<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (!Admin::where('email', 'admin@mentorhub.com')->exists()) {
            Admin::create([
                'name' => 'Administrator',
                'email' => 'admin@mentorhub.com',
                'password' => 'earlgwapo123', // Will be automatically hashed by the model's cast
            ]);
        } else {
            // Update existing admin password if it exists
            $admin = Admin::where('email', 'admin@mentorhub.com')->first();
            $admin->password = 'earlgwapo123'; // Will be automatically hashed by the model's cast
            $admin->save();
        }
    }
}


