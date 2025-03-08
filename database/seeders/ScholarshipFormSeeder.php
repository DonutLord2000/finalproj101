<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipForm;
use Illuminate\Support\Facades\Storage;

class ScholarshipFormSeeder extends Seeder
{
    public function run()
    {
        // Create a sample PDF file
        $content = "This is a sample scholarship application form.";
        $fileName = 'sample_scholarship_form.pdf';
        Storage::disk('private')->put($fileName, $content);

        ScholarshipForm::create([
            'name' => 'Default Scholarship Application Form',
            'file_path' => $fileName,
            'is_active' => true,
        ]);
    }
}

