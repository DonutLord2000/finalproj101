<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumnus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'year_graduated', 'age', 'gender', 'marital_status', 'current_location',
        'email', 'phone', 'degree_program', 'major', 'minor', 'gpa', 'employment_status',
        'job_title', 'company', 'industry', 'nature_of_work', 'employment_sector',
        'tenure_status', 'monthly_salary'
    ];

    protected $casts = [
        'year_graduated' => 'integer',
        'age' => 'integer',
        'gpa' => 'float',
        'monthly_salary' => 'float',
    ];
}