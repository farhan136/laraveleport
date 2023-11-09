<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\User2;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = new User;
        $user->name = "user";
        $user->email = "user@gmail.com";
        $user->password = Hash::make("user");
        $user->save();

        $user2 = new User2;
        $user2->name = "user2";
        $user2->email = "user2@gmail.com";
        $user2->password = Hash::make("user2");
        $user2->save();
    }
}
