<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateAlumniEmploymentStatusSeeder extends Seeder
{
    public function run()
    {
        User::where('role', 'alumni')->update(['is_employed' => 'unknown']);
    }
}