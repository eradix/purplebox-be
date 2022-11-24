<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            "first_name" => "Admin",
            "middle_name" => "Mid",
            "last_name" => "Super",
            "address" => "Tanza, Cavite",
            "role" => "admin",
            "email" => "admin@gmail.com",
            "password" => "password",
        ]);
    }
}
