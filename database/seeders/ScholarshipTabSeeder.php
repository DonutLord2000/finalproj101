<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ScholarshipTab;

class ScholarshipTabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tabs = [
            [
                'name' => 'REQUIREMENTS',
                'content' => '<ul class="list-disc pl-5 space-y-2">
                    <li>Current grades;
                        <ol class="list-decimal pl-5 mt-2 space-y-1">
                            <li>Form 138 (if freshman);</li>
                            <li>T.O.R. (if transferee);</li>
                            <li>Latest grade slip (if GRC student already)</li>
                        </ol>
                    </li>
                    <li>Current Certificate of Matriculation (if GRC student already);</li>
                    <li>GRC Admission slip or proof of successful compliance with all the GRC Admission requirements (if freshman or transferred-in);</li>
                    <li>Two (2) Recommendation Letter (from two (2) of any of the ff: Teacher, principal, guidance counselor, pastor, and/or public official);</li>
                    <li>Scholarship Application (download here: <a href="#" class="text-blue-600 hover:underline">APPLICATION FORM</a>)</li>
                    <li>Testimony (A 500-word essay of the applicant\'s life: story, family situation, and his/her reasons why he/she should be granted a scholarship.)</li>
                </ul>',
                'order' => 1,
            ],
            [
                'name' => 'QUALIFICATIONS',
                'content' => '<ul class="list-disc pl-5 space-y-2">
                    <li>Must be a Filipino citizen</li>
                    <li>Must have a general weighted average of at least 85% or its equivalent</li>
                    <li>Must be of good moral character</li>
                    <li>Must come from a low-income family with a combined annual income not exceeding PHP 300,000</li>
                    <li>Must not be a recipient of any other scholarship grant</li>
                    <li>Must be willing to maintain the required academic performance throughout the scholarship period</li>
                    <li>Must be willing to participate in community service activities as required by the scholarship program</li>
                </ul>',
                'order' => 2,
            ],
            [
                'name' => 'INSTRUCTIONS',
                'content' => '<ol class="list-decimal pl-5 space-y-2">
                    <li>Download and complete the scholarship application form</li>
                    <li>Gather all required documents as listed in the Requirements section</li>
                    <li>Scan all documents and save them as PDF files</li>
                    <li>Submit your application through this website by clicking the "Apply for Scholarship" button below</li>
                    <li>Wait for an email confirmation of your application submission</li>
                    <li>The scholarship committee will review your application within 3-5 working days</li>
                    <li>You will be notified via email about the status of your application</li>
                    <li>If approved, you will receive further instructions on the next steps</li>
                </ol>',
                'order' => 3,
            ],
        ];

        foreach ($tabs as $tab) {
            ScholarshipTab::create($tab);
        }
    }
}

