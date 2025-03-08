<x-app-layout>
    @section('title', 'GRC - Alumni')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Alumnus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form id="create-form" action="{{ route('alumni.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Personal Information</h3>
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="year_graduated" class="block text-sm font-medium text-gray-700">Year of Graduation</label>
                                    <input type="number" name="year_graduated" id="year_graduated" value="{{ old('year_graduated') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                                    <input type="number" name="age" id="age" value="{{ old('age') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                    <select name="gender" id="gender" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="marital_status" class="block text-sm font-medium text-gray-700">Marital Status</label>
                                    <input type="text" name="marital_status" id="marital_status" value="{{ old('marital_status') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="current_location" class="block text-sm font-medium text-gray-700">Current Location</label>
                                    <input type="text" name="current_location" id="current_location" value="{{ old('current_location') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Academic Information</h3>
                                <div class="mb-4">
                                    <label for="degree_program" class="block text-sm font-medium text-gray-700">Degree Program</label>
                                    <input type="text" name="degree_program" id="degree_program" value="{{ old('degree_program') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="major" class="block text-sm font-medium text-gray-700">Major</label>
                                    <input type="text" name="major" id="major" value="{{ old('major') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="minor" class="block text-sm font-medium text-gray-700">Minor</label>
                                    <input type="text" name="minor" id="minor" value="{{ old('minor') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="gpa" class="block text-sm font-medium text-gray-700">Overall GPA</label>
                                    <input type="number" step="0.01" name="gpa" id="gpa" value="{{ old('gpa') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <h3 class="text-lg font-semibold mb-2 mt-6">Employment Information</h3>
                                <div class="mb-4">
                                    <label for="employment_status" class="block text-sm font-medium text-gray-700">Employment Status</label>
                                    <input type="text" name="employment_status" id="employment_status" value="{{ old('employment_status') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="job_title" class="block text-sm font-medium text-gray-700">Job Title</label>
                                    <input type="text" name="job_title" id="job_title" value="{{ old('job_title') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="company" class="block text-sm font-medium text-gray-700">Company/Organization</label>
                                    <input type="text" name="company" id="company" value="{{ old('company') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="industry" class="block text-sm font-medium text-gray-700">Industry</label>
                                    <input type="text" name="industry" id="industry" value="{{ old('industry') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="nature_of_work" class="block text-sm font-medium text-gray-700">Nature of Work</label>
                                    <input type="text" name="nature_of_work" id="nature_of_work" value="{{ old('nature_of_work') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="employment_sector" class="block text-sm font-medium text-gray-700">Employment Sector</label>
                                    <input type="text" name="employment_sector" id="employment_sector" value="{{ old('employment_sector') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="tenure_status" class="block text-sm font-medium text-gray-700">Tenure Status</label>
                                    <input type="text" name="tenure_status" id="tenure_status" value="{{ old('tenure_status') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="monthly_salary" class="block text-sm font-medium text-gray-700">Monthly Salary</label>
                                    <input type="number" step="0.01" name="monthly_salary" id="monthly_salary" value="{{ old('monthly_salary') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Alumnus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

