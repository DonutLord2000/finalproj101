<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

return new class extends Migration
{
    /**
     * Run the migration – update only the selected columns
     */
    public function up(): void
    {
        // Ensure Faker is available in production: `composer require fakerphp/faker`
        $faker = Faker::create('en_PH');

        // 30 PH‑relevant industry names
        $industries = [
            'Business Process Outsourcing', 'Information Technology', 'Construction',
            'Healthcare', 'Education', 'Retail', 'Hospitality', 'Financial Services',
            'Government', 'Real Estate', 'Transportation', 'Manufacturing',
            'E‑commerce', 'Tourism', 'Agriculture', 'Energy', 'Telecommunications',
            'Logistics', 'Media', 'Utilities', 'Mining', 'Insurance', 'Aviation',
            'Legal Services', 'Advertising', 'Entertainment', 'Public Relations',
            'Research & Development', 'Pharmaceuticals', 'Food & Beverage',
        ];

        // 30 common job titles
        $jobTitles = [
            'Software Engineer', 'IT Support Specialist', 'Civil Engineer', 'Electrical Engineer',
            'Systems Analyst', 'Data Analyst', 'Network Administrator', 'Project Manager',
            'Teacher', 'Nurse', 'Accountant', 'Marketing Specialist', 'Sales Executive',
            'Customer Service Representative', 'Operations Manager', 'HR Specialist',
            'Content Creator', 'Video Editor', 'Graphic Designer', 'Business Analyst',
            'Administrative Assistant', 'Legal Assistant', 'Logistics Coordinator',
            'Call Center Agent', 'UX/UI Designer', 'Database Administrator', 'Financial Analyst',
            'Web Developer', 'Quality Assurance Tester', 'Mechanical Engineer',
        ];

        // Grab the first 100 rows (the ones you seeded) and update them in place
        DB::table('alumni')
            ->orderBy('id')
            ->limit(100)
            ->get()
            ->each(function ($alumnus) use ($faker, $industries, $jobTitles) {
                DB::table('alumni')
                    ->where('id', $alumnus->id)
                    ->update([
                        'year_graduated' => $faker->numberBetween(2001, 2025),
                        'industry'       => $faker->randomElement($industries),
                        'job_title'      => $faker->randomElement($jobTitles),
                        'updated_at'     => now(),
                    ]);
            });
    }

    /**
     * Reverse the migration (optional).
     * We cannot reconstruct the previous random values,
     * so we leave this empty or you may choose to set them to NULL.
     */
    public function down(): void
    {
        //
    }
};
