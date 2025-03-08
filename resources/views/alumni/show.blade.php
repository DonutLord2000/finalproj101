<x-app-layout>
    @section('title', 'GRC - Alumni')
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-900 leading-tight">
            {{ __('Alumnus Details') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200">
                <div class="p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Personal Information</h3>
                            <div class="space-y-2 text-gray-600">
                                <p><strong>Name:</strong> {{ $alumnus->name }}</p>
                                <p><strong>Year of Graduation:</strong> {{ $alumnus->year_graduated }}</p>
                                <p><strong>Age:</strong> {{ $alumnus->age ?? 'N/A' }}</p>
                                <p><strong>Gender:</strong> {{ $alumnus->gender ?? 'N/A' }}</p>
                                <p><strong>Marital Status:</strong> {{ $alumnus->marital_status ?? 'N/A' }}</p>
                                <p><strong>Current Location:</strong> {{ $alumnus->current_location ?? 'N/A' }}</p>
                                <p><strong>Email Address:</strong> {{ $alumnus->email ?? 'N/A' }}</p>
                                <p><strong>Phone Number:</strong> {{ $alumnus->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <!-- Academic Information -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Academic Information</h3>
                            <div class="space-y-2 text-gray-600">
                                <p><strong>Degree Program:</strong> {{ $alumnus->degree_program }}</p>
                                <p><strong>Major:</strong> {{ $alumnus->major ?? 'N/A' }}</p>
                                <p><strong>Minor:</strong> {{ $alumnus->minor ?? 'N/A' }}</p>
                                <p><strong>Overall GPA:</strong> {{ $alumnus->gpa ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Employment Information -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Employment Information</h3>
                        <div class="space-y-2 text-gray-600">
                            <p><strong>Employment Status:</strong> {{ $alumnus->employment_status ?? 'N/A' }}</p>
                            <p><strong>Job Title:</strong> {{ $alumnus->job_title ?? 'N/A' }}</p>
                            <p><strong>Company/Organization:</strong> {{ $alumnus->company ?? 'N/A' }}</p>
                            <p><strong>Industry:</strong> {{ $alumnus->industry ?? 'N/A' }}</p>
                            <p><strong>Nature of Work:</strong> {{ $alumnus->nature_of_work ?? 'N/A' }}</p>
                            <p><strong>Employment Sector:</strong> {{ $alumnus->employment_sector ?? 'N/A' }}</p>
                            <p><strong>Tenure Status:</strong> {{ $alumnus->tenure_status ?? 'N/A' }}</p>
                            <p><strong>Monthly Salary:</strong> {{ $alumnus->monthly_salary ? 'php ' . number_format($alumnus->monthly_salary, 2) : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('alumni.edit', $alumnus) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition ease-in-out duration-200 transform hover:scale-105">
                            Edit
                        </a>
                        <button onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition ease-in-out duration-200 transform hover:scale-105">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this alumnus?')) {
                fetch('{{ route('alumni.destroy', $alumnus) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                }).then(response => {
                    if (response.ok) {
                        window.location.href = '{{ route('alumni.index') }}';
                    }
                });
            }
        }
    </script>
</x-app-layout>
