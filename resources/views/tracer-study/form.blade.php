@extends('layouts.alumni')
@section('title', 'GRC - Tracer Study')
@section('content')

    
    <div class="py-12">
        
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h1 class="mb-4 text-3xl font-bold text-black-500">Graduate Tracer Study Questionnaire</h1>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 mx-auto">
            
    <form action="{{ route('tracer-study.submit') }}" method="POST">
        @csrf

        <div class="ml-5 mr-5 card mb-4 shadow-sm">
            <div class="mt-5 card-header bg-primary text-black py-3">
                <h2 class="mb-0 text-xl font-semibold ">Part A: General Information</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <x-label for="name" value="{{ __('1. Name: (Optional)') }}" />
                    <x-input id="name" type="text" class="mt-1 block w-full" name="name" />
                </div>
                <div class="mb-3">
                    <x-label for="year_graduated" value="{{ __('2. Year of Graduation:') }}" />
                    <x-input id="year_graduated" type="number" class="mt-1 block w-full" name="year_graduated" required />
                </div>
                <div class="mb-3">
                    <x-label for="age" value="{{ __('3. Age:') }}" />
                    <x-input id="age" type="number" class="mt-1 block w-full" name="age" />
                </div>
                <div class="mb-3">
                    <x-label for="gender" value="{{ __('4. Gender:') }}" />
                    <select class="form-select block mt-1 sm:rounded-lg border-gray-300" id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <x-label for="marital_status" value="{{ __('5. Marital Status:') }}" />
                    <x-input id="marital_status" type="text" class="mt-1 block w-full" name="marital_status" />
                </div>
                <div class="mb-3">
                    <x-label for="current_location" value="{{ __('6. Current Location:') }}" />
                    <x-input id="current_location" type="text" class="mt-1 block w-full" name="current_location" />
                </div>
                <div class="mb-3">
                    <x-label for="email" value="{{ __('7. Contact Information: Email Address') }}" />
                    <x-input id="email" type="email" class="mt-1 block w-full" name="email" />
                </div>
                <div class="mb-3">
                    <x-label for="phone" value="{{ __('Phone Number') }}" />
                    <x-input id="phone" type="tel" class="mt-1 block w-full" name="phone" />
                </div>
            </div>
        </div>

        <div class="ml-5 mr-5 card mb-4 shadow-sm">
            <div class="card-header bg-success text-black py-3">
                <h2 class="mb-0 text-xl font-semibold">Part B: Educational Background</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <x-label for="degree_program" value="{{ __('1. Degree Program:') }}" />
                    <x-input id="degree_program" type="text" class="mt-1 block w-full" name="degree_program" required />
                </div>
                <div class="mb-3">
                    <x-label for="major" value="{{ __('2. Major: (If applicable)') }}" />
                    <x-input id="major" type="text" class="mt-1 block w-full" name="major" />
                </div>
                <div class="mb-3">
                    <x-label for="minor" value="{{ __('3. Minor: (If applicable)') }}" />
                    <x-input id="minor" type="text" class="mt-1 block w-full" name="minor" />
                </div>
                <div class="mb-3">
                    <x-label for="gpa" value="{{ __('4. Overall GPA:') }}" />
                    <x-input id="gpa" type="number" step="0.01" min="0" max="4" class="mt-1 block w-full" name="gpa" />
                </div>
                <div class="mb-3">
                    <x-label value="{{ __('5. How satisfied were you with the following aspects of your education?') }}" />
                    <div class="table-responsive">
                        <table class="table table-bordered mt-2 ml-4">
                            <thead class="bg-light">
                                <tr>
                                    <th>Aspect</th>
                                    <th>Very Satisfied</th>
                                    <th>Satisfied</th>
                                    <th>Neutral</th>
                                    <th>Dissatisfied</th>
                                    <th>Very Dissatisfied</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['Quality of Instruction', 'Curriculum Relevance', 'Availability of Resources', 'Faculty Support', 'Career Advising', 'Extracurricular Activities'] as $aspect)
                                    <tr>
                                        <td>{{ $aspect }}</td>
                                        @foreach(['Very Satisfied', 'Satisfied', 'Neutral', 'Dissatisfied', 'Very Dissatisfied'] as $rating)
                                            <td class="text-center">
                                                <input type="radio" name="satisfaction_{{ Str::slug($aspect) }}" value="{{ $rating }}" required>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="ml-5 mr-5 card mb-4 shadow-sm">
            <div class="card-header bg-info text-black py-3">
                <h2 class="mb-0 text-xl font-semibold ">Part C: Employment Status</h2>
            </div>
            <div class="card-body">
                <div class="mb-3 ">
                    <x-label for="employment_status" value="{{ __('1. Are you currently employed?') }}" />
                    <select class="form-select block mt-1 sm:rounded-lg border-gray-300" id="employment_status" name="employment_status" required>
                        <option value="">Select</option>
                        <option value="Employed">Yes</option>
                        <option value="Unemployed">No</option>
                    </select>
                </div>

                <div id="employed-section" style="display: none;">
                    <div class="mb-3">
                        <x-label for="job_title" value="{{ __('Job Title:') }}" />
                        <x-input id="job_title" type="text" class="mt-1 block w-full" name="job_title" />
                    </div>
                    <div class="mb-3">
                        <x-label for="company" value="{{ __('Company/Organization:') }}" />
                        <x-input id="company" type="text" class="mt-1 block w-full" name="company" />
                    </div>
                    <div class="mb-3">
                        <x-label for="industry" value="{{ __('Industry:') }}" />
                        <x-input id="industry" type="text" class="mt-1 block w-full" name="industry" />
                    </div>
                    <div class="mb-3">
                        <x-label for="nature_of_work" value="{{ __('Nature of Work:') }}" />
                        <x-input id="nature_of_work" type="text" class="mt-1 block w-full" name="nature_of_work" placeholder="e.g., Academic, Supervisory, Technical" />
                    </div>
                    <div class="mb-3">
                        <x-label for="employment_sector" value="{{ __('Employment Sector:') }}" />
                        <x-input id="employment_sector" type="text" class="mt-1 block w-full" name="employment_sector" placeholder="e.g., Public, Private, Self-Employed" />
                    </div>
                    <div class="mb-3">
                        <x-label for="tenure_status" value="{{ __('Tenure Status:') }}" />
                        <x-input id="tenure_status" type="text" class="mt-1 block w-full" name="tenure_status" placeholder="e.g., Regular/Permanent, Contractual, Temporary" />
                    </div>
                    <div class="mb-3">
                        <x-label for="monthly_salary" value="{{ __('Monthly Salary: (Optional)') }}" />
                        <x-input id="monthly_salary" type="number" class="mt-1 block w-full" name="monthly_salary" />
                    </div>
                    <div class="mb-3">
                        <x-label for="time_to_first_job" value="{{ __('How long did it take you to find your first job after graduation?') }}" />
                        <x-input id="time_to_first_job" type="text" class="mt-1 block w-full" name="time_to_first_job" />
                    </div>
                    <div class="mb-3">
                        <x-label for="job_finding_method" value="{{ __('How did you find your first job?') }}" />
                        <x-input id="job_finding_method" type="text" class="mt-1 block w-full" name="job_finding_method" placeholder="e.g., Recommendation, Online Job Portal, Career Fair" />
                    </div>
                    <div class="mb-3">
                        <x-label for="job_related_to_degree" value="{{ __('Is your current job related to your degree program?') }}" />
                        <select class="form-select block mt-1 sm:rounded-lg border-gray-300" id="job_related_to_degree" name="job_related_to_degree">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <x-label for="job_unrelated_reason" value="{{ __('If no, please briefly explain why:') }}" />
                        <textarea class="form-textarea mt-1 block w-full sm:rounded-lg border-gray-300" id="job_unrelated_reason" name="job_unrelated_reason" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <x-label for="useful_skills" value="{{ __('What skills learned in college are most useful in your current job?') }}" />
                        <textarea class="form-textarea mt-1 block w-full sm:rounded-lg border-gray-300" id="useful_skills" name="useful_skills" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <x-label for="desired_skills" value="{{ __('What skills or knowledge do you wish you had acquired in college that would be beneficial in your current role?') }}" />
                        <textarea class="form-textarea mt-1 block w-full sm:rounded-lg border-gray-300" id="desired_skills" name="desired_skills" rows="3"></textarea>
                    </div>
                </div>

                <div id="unemployed-section" style="display: none;">
                    <div class="mb-3">
                        <x-label for="seeking_employment" value="{{ __('Are you currently seeking employment?') }}" />
                        <select class="form-select block sm:rounded-lg border-gray-300 mt-1" id="seeking_employment" name="seeking_employment">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <x-label for="job_types_sought" value="{{ __('If yes, what types of jobs are you looking for?') }}" />
                        <textarea class="form-textarea mt-1 block w-full sm:rounded-lg border-gray-300" id="job_types_sought" name="job_types_sought" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <x-label for="employment_challenges" value="{{ __('What challenges have you faced in finding employment?') }}" />
                        <textarea class="form-textarea mt-1 block w-full sm:rounded-lg border-gray-300" id="employment_challenges" name="employment_challenges" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <x-label for="reason_not_seeking" value="{{ __('If you are not seeking employment, please indicate the reason:') }}" />
                        <x-input id="reason_not_seeking" type="text" class="mt-1 block w-full " name="reason_not_seeking" placeholder="e.g., Further Studies, Family Concerns" />
                    </div>
                </div>
            </div>
        </div>

        <div class="ml-5 mr-5 card mb-4 shadow-sm">
            <div class="card-header bg-warning py-3">
                <h2 class="mb-0 text-xl font-semibold">Part D: Feedback and Suggestions</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <x-label for="program_strengths" value="{{ __('1. What are the strengths of your undergraduate program?') }}" />
                    <textarea class="form-textarea mt-1 block w-full sm:rounded-lg border-gray-300" id="program_strengths" name="program_strengths" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <x-label for="program_improvements" value="{{ __('2. What areas of your undergraduate program could be improved?') }}" />
                    <textarea class="form-textarea mt-1 block w-full sm:rounded-lg border-gray-300" id="program_improvements" name="program_improvements" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <x-label for="program_recommendations" value="{{ __('3. What specific recommendations do you have for enhancing the quality and relevance of your degree program?') }}" />
                    <textarea class="form-textarea mt-1 block w-full sm:rounded-lg border-gray-300" id="program_recommendations" name="program_recommendations" rows="3" placeholder="e.g., Curriculum Updates, Enhanced Faculty Training, Improved Facilities"></textarea>
                </div>
            </div>
        </div>

        <div class="mr-5 mb-5 d-grid gap-2 text-end">
            <x-button class="btn-lg">
                {{ __('Submit') }}
            </x-button>
        </div>
    </form>
</div>
</div>
</div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const employmentStatus = document.getElementById('employment_status');
        const employedSection = document.getElementById('employed-section');
        const unemployedSection = document.getElementById('unemployed-section');

        employmentStatus.addEventListener('change', function() {
            if (this.value === 'Employed') {
                employedSection.style.display = 'block';
                unemployedSection.style.display = 'none';
            } else if (this.value === 'Unemployed') {
                employedSection.style.display = 'none';
                unemployedSection.style.display = 'block';
            } else {
                employedSection.style.display = 'none';
                unemployedSection.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection