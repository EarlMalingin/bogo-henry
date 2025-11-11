<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (!Admin::where('email', 'admin@mentorhub.com')->exists()) {
            Admin::create([
                'name' => 'Administrator',
                'email' => 'admin@mentorhub.com',
                'password' => Hash::make('earlgwapo123'),
            ]);
        }
    }
}


