<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_PH');

        $degreePrograms = [
            'BS Computer Science', 'BS Information Technology', 'BS Information Systems',
            'BS Electronics Engineering', 'BS Electrical Engineering', 'BS Civil Engineering',
            'BS Mechanical Engineering', 'BS Accountancy', 'BS Business Administration', 'BS Psychology'
        ];

        $majors = [
            'Software Engineering', 'Data Science', 'Cybersecurity',
            'Network Administration', 'Artificial Intelligence', 'Multimedia Arts', 'Business Analytics'
        ];

        $minors = [
            'Mathematics', 'Economics', 'Psychology',
            'Statistics', 'Computer Science', 'Business Studies', 'Philosophy'
        ];

        $genders = ['Male', 'Female', 'Other'];
        $maritalStatuses = ['Single', 'Married', 'Divorced', 'Widowed', 'Separated'];
        $employmentStatuses = ['Employed', 'Unemployed'];
        $employmentSectors = ['Public', 'Private', 'Self-Employed', 'Non-Profit', 'Freelance'];
        $tenureStatuses = ['Regular/Permanent', 'Contractual', 'Temporary', 'Probationary', 'Part-Time'];

        for ($i = 0; $i < 100; $i++) {
            DB::table('alumni')->insert([
                'name'              => $faker->name,
                'year_graduated'    => $faker->numberBetween(1980, 1999),
                'age'               => $faker->numberBetween(43, 65),
                'gender'            => $faker->randomElement($genders),
                'marital_status'    => $faker->randomElement($maritalStatuses),
                'current_location'  => $faker->city . ', ' . $faker->state,
                'email'             => $faker->unique()->safeEmail,
                'phone'             => '09' . $faker->numerify('#########'), // 11-digit PH mobile
                'degree_program'    => $faker->randomElement($degreePrograms),
                'major'             => $faker->randomElement($majors),
                'minor'             => $faker->randomElement($minors),
                'gpa'               => $faker->randomFloat(2, 2.00, 4.00),
                'employment_status' => $faker->randomElement($employmentStatuses),
                'job_title'         => $faker->jobTitle,
                'company'           => $faker->company,
                'industry'          => $faker->word,
                'nature_of_work'    => $faker->randomElement(['Full-Time', 'Part-Time', 'Project-Based']),
                'employment_sector' => $faker->randomElement($employmentSectors),
                'tenure_status'     => $faker->randomElement($tenureStatuses),
                'monthly_salary'    => $faker->numberBetween(10000, 75000),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
