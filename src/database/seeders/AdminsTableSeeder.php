<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            "管理者",
            "店舗代表者",
        ];

        foreach ($roles as $key => $role) {
            DB::table('admins')->insert([
                'name' => '福沢諭吉' . $key,
                'email' => 'test' . $key .'@example.com',
                'password' => Hash::make('password'),
                'role' => $role,
            ]);
        }
    }
}
