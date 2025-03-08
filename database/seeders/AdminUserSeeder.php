<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 10,
            'name' => 'admin',
            'email' => 'admin',
            'email_verified_at' => Carbon::parse('2024-11-28 14:52:16'),
            'role' => 'admin',
            'student_id' => '2110928',
            'password' => Hash::make('admin'), // Hash the password for security
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'remember_token' => null,
            'current_team_id' => null,
            'profile_photo_path' => null,
            'created_at' => Carbon::parse('2024-11-28 14:52:16'),
            'updated_at' => Carbon::parse('2024-11-28 14:52:16'),
            'last_login_at' => null,
        ]);
    }
}
