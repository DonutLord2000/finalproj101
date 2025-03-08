<x-app-layout>
    @section('title', 'Dashboard')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    
    
    <!-- CHART FOR ALUMNI -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Alumni Statistics</h2>
                
                <!-- Top Row Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Total Alumni Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-6 transform transition-all hover:scale-105">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Total Alumni</h3>
                        <p class="text-5xl font-bold text-blue-600">{{ $totalAlumni }}</p>
                    </div>

                    <!-- Gender Distribution Card -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Gender Distribution</h3>
                        <canvas id="genderChart" class="w-full h-48"></canvas>
                    </div>

                    <!-- Graduation Year Trends Card -->
                    <div class="bg-gradient-to-br from-red-100 to-yellow-100 rounded-xl shadow-lg p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v4m-4-4v4m8-4v4M5 8l7-7 7 7M5 8h14v11H5V8z" />
                            </svg>
                            Graduation Year Trends
                        </h3>
                        <canvas id="graduationChart" class="w-full h-full"></canvas>
                    </div>                                
                </div>

                <!-- Bottom Row Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Top Industries Card -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Top Industries</h3>
                        <div class="space-y-4">
                            @php
                                $totalIndustries = array_sum($topIndustries);
                            @endphp
                            @foreach($topIndustries as $industry => $count)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700 font-medium">{{ $industry }}</span>
                                <div class="flex items-center">
                                    <div class="h-2.5 rounded-full bg-purple-200 w-32 mr-2">
                                        <div class="h-2.5 rounded-full bg-purple-600" style="width: {{ ($count / $totalIndustries) * 100 }}%"></div>
                                    </div>
                                    <span class="text-gray-600 font-semibold">{{ round(($count / $totalIndustries) * 100, 1) }}%</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                
                    <!-- Top Degree Programs Card -->
                    <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Top Degree Programs</h3>
                        <div class="space-y-4">
                            @php
                                $totalPrograms = array_sum($topDegreePrograms);
                            @endphp
                            @foreach($topDegreePrograms as $program => $count)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700 font-medium">{{ $program }}</span>
                                <div class="flex items-center">
                                    <div class="h-2.5 rounded-full bg-pink-200 w-32 mr-2">
                                        <div class="h-2.5 rounded-full bg-pink-600" style="width: {{ ($count / $totalPrograms) * 100 }}%"></div>
                                    </div>
                                    <span class="text-gray-600 font-semibold">{{ round(($count / $totalPrograms) * 100, 1) }}%</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <h4 class="text-2xl font-bold text-gray-900 mb-4 mt-4">Common Job Titles and Industries by Degree</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300 rounded-lg shadow-md">
                            <thead class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 uppercase text-sm">
                                <tr>
                                    <th class="py-3 px-5 text-left border-b border-gray-900">Major</th>
                                    <th class="py-3 px-5 text-left border-b border-gray-900">Common Job Titles</th>
                                    <th class="py-3 px-5 text-left border-b border-gray-900">Common Industries</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-800">
                                @foreach($jobTitlesByMajor as $major => $data)
                                    <tr class="even:bg-gray-50 hover:bg-gray-100 transition duration-200">
                                        <td class="py-3 px-5 border-b border-gray-300 font-medium">{{ $major }}</td>
                                        <td class="py-3 px-5 border-b border-gray-300">{{ implode(', ', $data['job_titles']->toArray()) }}</td>
                                        <td class="py-3 px-5 border-b border-gray-300">{{ implode(', ', $data['industries']->toArray()) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>                              
                
            </div>
            

            <div class="max-w-7xl mx-auto">
                <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Active Users Overview
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Daily Active Users -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-md hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 bg-blue-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex items-center text-sm text-blue-600">
                                    <span class="font-medium">Today</span>
                                </div>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-1">Daily Active Users</h4>
                            <div class="flex items-center justify-between">
                                <span class="text-3xl font-bold text-gray-900">{{ $dailyActiveUsers }}</span>
                                @if(isset($dailyActiveUsersGrowth))
                                    <div class="flex items-center {{ $dailyActiveUsersGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="{{ $dailyActiveUsersGrowth >= 0 
                                                    ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' 
                                                    : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}" />
                                        </svg>
                                        <span class="font-medium">{{ abs($dailyActiveUsersGrowth) }}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                
                        <!-- Weekly Active Users -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-md hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 bg-purple-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex items-center text-sm text-purple-600">
                                    <span class="font-medium">This Week</span>
                                </div>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-1">Weekly Active Users</h4>
                            <div class="flex items-center justify-between">
                                <span class="text-3xl font-bold text-gray-900">{{ $weeklyActiveUsers }}</span>
                                @if(isset($weeklyActiveUsersGrowth))
                                    <div class="flex items-center {{ $weeklyActiveUsersGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="{{ $weeklyActiveUsersGrowth >= 0 
                                                    ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' 
                                                    : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}" />
                                        </svg>
                                        <span class="font-medium">{{ abs($weeklyActiveUsersGrowth) }}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                
                        <!-- Monthly Active Users -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-md hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 bg-green-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="flex items-center text-sm text-green-600">
                                    <span class="font-medium">This Month</span>
                                </div>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-1">Monthly Active Users</h4>
                            <div class="flex items-center justify-between">
                                <span class="text-3xl font-bold text-gray-900">{{ $monthlyActiveUsers }}</span>
                                @if(isset($monthlyActiveUsersGrowth))
                                    <div class="flex items-center {{ $monthlyActiveUsersGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="{{ $monthlyActiveUsersGrowth >= 0 
                                                    ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' 
                                                    : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}" />
                                        </svg>
                                        <span class="font-medium">{{ abs($monthlyActiveUsersGrowth) }}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NEws post -->
            <div class="py-12">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                       <h2 class="text-2xl font-semibold text-gray-800 mt-4 mb-4 text-center mx-auto">Latest News</h2>

                            @foreach ($newsPosts as $post)
                            
                                <div class="ml-10 mr-10 mb-8 relative border-l-4 border-l-gray-200 bg-white shadow-md rounded-lg overflow-hidden mx-auto">
                                    <div class="absolute left-1 top-1 text-black-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 " viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="bg-gray-200 text-black py-2 px-4 flex items-center justify-between">
                                        <h3 class="ml-4 font-semibold">{{ $post->title }}</h3>
                                    </div>
                                    <div class="p-4 pl-12">
                                        @if($post->image)
                                                <img src="{{ Storage::url($post->image) }}" alt="News post image" class="mb-2 mt-2 mr-4 rounded-lg" style="max-width: 40rem; max-height: 24rem; object-contain;">
                                        @endif
                                        @if($post->video)
                                            <video controls class="w-full h-auto mb-4 rounded-lg">
                                                <source src="{{ Storage::url($post->video) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @endif
                                        <div class="prose prose-sm max-w-none mb-2 mt-2 mr-4">
                                            {!! nl2br(e($post->content)) !!}
                                        </div>
                                    </div>
                                    @if($post->source)
                                        <div class="px-4 py-2 text-sm text-black-600 border-t">
                                            This is a message from <strong>{{ $post->source }}</strong>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                    </div>
                </div>
            </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gender Distribution Chart
            const genderCtx = document.getElementById('genderChart');
            if (genderCtx) {
                new Chart(genderCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(@json($genderDistribution)),
                        datasets: [{
                            data: Object.values(@json($genderDistribution)),
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)', // Blue
                                'rgba(236, 72, 153, 0.8)', // Pink
                                'rgba(107, 114, 128, 0.8)' // Gray
                            ],
                            borderColor: [
                                'rgba(59, 130, 246, 1)',
                                'rgba(236, 72, 153, 1)',
                                'rgba(107, 114, 128, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Graduation Year Trends Chart
            const gradCtx = document.getElementById('graduationChart');
            if (gradCtx) {
                new Chart(gradCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(@json($graduationYearTrends)),
                        datasets: [{
                            label: 'Graduates',
                            data: Object.values(@json($graduationYearTrends)),
                            backgroundColor: 'rgba(239, 68, 68, 0.8)', // Bright red
                            borderColor: 'rgba(220, 38, 38, 1)', // Darker red
                            borderWidth: 2,
                            hoverBackgroundColor: 'rgba(220, 38, 38, 0.9)', // Slightly darker red
                            hoverBorderColor: 'rgba(185, 28, 28, 1)', // Deep red
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true, // Allow the chart to fill the container
                        aspectRatio: 1, // Adjust this value to control the aspect ratio
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: '#4B5563', // Dark gray
                                    font: {
                                        family: 'Inter, sans-serif',
                                        size: 14
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: '#edc0c0',
                                titleColor: '#374151', // Dark gray
                                bodyColor: '#111827', // Almost black
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                borderColor: '#F59E0B',
                                borderWidth: 1,
                                padding: 10
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false // Hide vertical gridlines for a cleaner look
                                },
                                ticks: {
                                    color: '#6B7280', // Gray
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#6B7280', // Gray
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: 'rgba(209, 213, 219, 0.5)', // Light gray gridlines
                                    borderDash: [5, 5] // Dashed lines
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>