<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "email"=>"app@mailinator.com",
            "name" => "admin",
            "password" => "123456",
            "type" => "Admin"
        ]);
    }
}
