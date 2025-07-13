<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 rounded-2xl">
    <h2 class="text-3xl font-extrabold text-gray-900 mb-6 text-center">Alumni Tracking System</h2>

    <!-- Individual Search Section (Moved to Top) -->
    <div class="mb-6 p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-md border border-blue-200">
        <h3 class="text-xl font-bold text-blue-800 mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Search Individual Alumni
        </h3>
        <div class="relative mb-4">
            <input type="text" id="alumni_search_input" placeholder="Search alumni by name..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2">
            <div id="alumni_search_results" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 hidden max-h-60 overflow-y-auto">
                <!-- Search results will be appended here -->
            </div>
        </div>
        <button id="search_alumni_btn" class="w-full inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
            Get Individual Insight
        </button>
    </div>

    <!-- Filters Section (For Group Analysis) -->
    <div class="mb-6 p-5 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-md border border-purple-200">
        <h3 class="text-xl font-bold text-purple-800 mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01.293.707V19a2 2 0 01-2 2H4a2 2 0 01-2-2V7.293a1 1 0 01.293-.707L3 4zm6 6V7m0 6h.01M12 7h.01M15 7h.01M12 10h.01M15 10h.01M12 13h.01M15 13h.01M12 16h.01M15 16h.01" />
            </svg>
            Filter Alumni Data (Group Analysis)
        </h3>
        <div class="grid grid-cols-1 gap-y-4">
            <!-- Filter Item: Graduation Year -->
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 flex items-center justify-between w-48">
                    <label for="toggle_year_filter" class="text-sm font-medium text-gray-700">Graduation Year</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" id="toggle_year_filter" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
                <div id="year_filter_panel" class="filter-panel flex-grow bg-white p-4 rounded-lg shadow-lg border border-gray-200 z-10 opacity-100 pointer-events-auto transition-all duration-300 ease-in-out">
                    <div class="relative mb-6 h-2 bg-gray-200 rounded-lg">
                        <input type="range" id="from_year" min="1950" max="2025" value="1950" class="w-full h-full appearance-none cursor-pointer absolute top-0 left-0 z-20">
                        <input type="range" id="to_year" min="1950" max="2025" value="2025" class="w-full h-full appearance-none cursor-pointer absolute top-0 left-0 z-20">
                    </div>
                    <div class="flex justify-between text-xs font-semibold text-gray-700 -mt-4 mb-2">
                        <span id="from_year_value"></span>
                        <span id="to_year_value"></span>
                    </div>
                </div>
            </div>

            <!-- Filter Item: Gender -->
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 flex items-center justify-between w-48">
                    <label for="toggle_gender_filter" class="text-sm font-medium text-gray-700">Gender</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" id="toggle_gender_filter">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
                <div id="gender_filter_panel" class="filter-panel flex-grow bg-white p-4 rounded-lg shadow-lg border border-gray-200 z-10 opacity-50 pointer-events-none transition-all duration-300 ease-in-out">
                    <div>
                        <label for="gender" class="block text-xs font-medium text-gray-600 mb-1">Select Gender</label>
                        <select id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-2 text-sm">
                            <option value="">Any</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Filter Item: Age -->
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 flex items-center justify-between w-48">
                    <label for="toggle_age_filter" class="text-sm font-medium text-gray-700">Age Range</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" id="toggle_age_filter">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
                <div id="age_filter_panel" class="filter-panel flex-grow bg-white p-4 rounded-lg shadow-lg border border-gray-200 z-10 opacity-50 pointer-events-none transition-all duration-300 ease-in-out">
                    <div class="relative mb-6 h-2 bg-gray-200 rounded-lg">
                        <input type="range" id="age_from" min="18" max="100" value="18" class="w-full h-full appearance-none cursor-pointer absolute top-0 left-0 z-20">
                        <input type="range" id="age_to" min="18" max="100" value="100" class="w-full h-full appearance-none cursor-pointer absolute top-0 left-0 z-20">
                    </div>
                    <div class="flex justify-between text-xs font-semibold text-gray-700 -mt-4 mb-2">
                        <span id="age_from_value"></span>
                        <span id="age_to_value"></span>
                    </div>
                </div>
            </div>

            <!-- Filter Item: Monthly Salary -->
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 flex items-center justify-between w-48">
                    <label for="toggle_salary_filter" class="text-sm font-medium text-gray-700">Monthly Salary</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" id="toggle_salary_filter">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
                <div id="salary_filter_panel" class="filter-panel flex-grow bg-white p-4 rounded-lg shadow-lg border border-gray-200 z-10 opacity-50 pointer-events-none transition-all duration-300 ease-in-out">
                    <div class="relative mb-6 h-2 bg-gray-200 rounded-lg">
                        <input type="range" id="salary_from" min="0" max="200000" value="0" step="1000" class="w-full h-full appearance-none cursor-pointer absolute top-0 left-0 z-20">
                        <input type="range" id="salary_to" min="0" max="200000" value="200000" step="1000" class="w-full h-full appearance-none cursor-pointer absolute top-0 left-0 z-20">
                    </div>
                    <div class="flex justify-between text-xs font-semibold text-gray-700 -mt-4 mb-2">
                        <span id="salary_from_value"></span>
                        <span id="salary_to_value"></span>
                    </div>
                </div>
            </div>

            <!-- Filter Item: Degree Program -->
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 flex items-center justify-between w-48">
                    <label for="toggle_degree_program_filter" class="text-sm font-medium text-gray-700">Degree Program</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" id="toggle_degree_program_filter">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
                <div id="degree_program_filter_panel" class="filter-panel flex-grow bg-white p-4 rounded-lg shadow-lg border border-gray-200 z-10 opacity-50 pointer-events-none transition-all duration-300 ease-in-out">
                    <div>
                        <label for="degree_program" class="block text-xs font-medium text-gray-600 mb-1">Select Program</label>
                        <select id="degree_program" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-2 text-sm">
                            <option value="">Any</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Filter Item: Industry -->
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 flex items-center justify-between w-48">
                    <label for="toggle_industry_filter" class="text-sm font-medium text-gray-700">Industry</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" id="toggle_industry_filter">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
                <div id="industry_filter_panel" class="filter-panel flex-grow bg-white p-4 rounded-lg shadow-lg border border-gray-200 z-10 opacity-50 pointer-events-none transition-all duration-300 ease-in-out">
                    <div>
                        <label for="industry" class="block text-xs font-medium text-gray-600 mb-1">Select Industry</label>
                        <select id="industry" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-2 text-sm">
                            <option value="">Any</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Filter Item: Job Title -->
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 flex items-center justify-between w-48">
                    <label for="toggle_job_title_filter" class="text-sm font-medium text-gray-700">Job Title</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" id="toggle_job_title_filter">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
                <div id="job_title_filter_panel" class="filter-panel flex-grow bg-white p-4 rounded-lg shadow-lg border border-gray-200 z-10 opacity-50 pointer-events-none transition-all duration-300 ease-in-out">
                    <div>
                        <label for="job_title" class="block text-xs font-medium text-gray-600 mb-1">Select Job Title</label>
                        <select id="job_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-2 text-sm">
                            <option value="">Any</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Filter Item: Employment Status -->
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 flex items-center justify-between w-48">
                    <label for="toggle_employment_status_filter" class="text-sm font-medium text-gray-700">Employment Status</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" id="toggle_employment_status_filter">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
                <div id="employment_status_filter_panel" class="filter-panel flex-grow bg-white p-4 rounded-lg shadow-lg border border-gray-200 z-10 opacity-50 pointer-events-none transition-all duration-300 ease-in-out">
                    <div>
                        <label for="employment_status" class="block text-xs font-medium text-gray-600 mb-1">Select Status</label>
                        <select id="employment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-2 text-sm">
                            <option value="">Any</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <button id="apply_filters_btn" class="w-full inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out mt-6">
            Apply Filters for Group Insights
        </button>
    </div>

    <!-- AI Insights Display -->
    <div class="mt-6 p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl shadow-md border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            AI-Generated Insights
        </h3>
        <div id="ai_insights_loading" class="text-center text-gray-500 py-6 hidden">
            <svg class="animate-spin h-8 w-8 text-indigo-500 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-lg">Generating insights...</p>
        </div>
        <div id="ai_insights_display" class="text-gray-700 prose max-w-none">
            <p class="text-lg text-center text-gray-500">Select filters or search for an alumni to generate insights.</p>
        </div>
        <div id="ai_insights_error" class="text-red-600 mt-4 text-center font-medium hidden"></div>

        <!-- Charts Container for Group Insights -->
        <div id="group_charts_container" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 hidden">
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Employment Status Distribution</h4>
                <canvas id="employmentStatusChart" class="h-64"></canvas>
            </div>
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Top Industries</h4>
                <canvas id="topIndustriesChart" class="h-64"></canvas>
            </div>
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200 md:col-span-2">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Top Degree Programs</h4>
                <canvas id="topDegreeProgramsChart" class="h-64"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    /* Custom styles for range input thumbs */
    input[type="range"] {
        -webkit-appearance: none; /* Remove default webkit styles */
        appearance: none;
        background: transparent; /* Make track transparent, parent div has bg-gray-200 */
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%; /* Make it cover the parent div for interaction */
        margin: 0;
        padding: 0;
        /* z-index is managed by JS now */
    }

    /* Track styling (make it transparent as the parent div provides the visual track) */
    input[type="range"]::-webkit-slider-runnable-track {
        width: 100%;
        height: 2px; /* Match the h-2 of the parent div */
        background: transparent;
        border-radius: 0px;
        pointer-events: none; /* Crucial: Prevent interaction with the track */
    }

    input[type="range"]::-moz-range-track {
        width: 100%;
        height: 2px;
        background: transparent;
        border-radius: 0px;
        pointer-events: none; /* Crucial: Prevent interaction with the track */
    }

    /* Thumb styling */
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px; /* Increased size */
        height: 20px; /* Increased size */
        background: #8B5CF6; /* Purple color for the thumb */
        border: 2px solid #ffffff; /* White border */
        border-radius: 50%; /* Make it a circle */
        cursor: grab; /* Indicate it's draggable */
        margin-top: -9px; /* Adjusted to center vertically on the 2px track (20px thumb - 2px track) / 2 = 9px */
        box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.3); /* Focus ring effect */
        transition: background 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        pointer-events: auto; /* Crucial: Ensure thumb is interactive */
    }

    input[type="range"]::-moz-range-thumb {
        width: 20px; /* Increased size */
        height: 20px; /* Increased size */
        background: #8B5CF6;
        border: 2px solid #ffffff;
        border-radius: 50%;
        cursor: grab; /* Indicate it's draggable */
        box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.3);
        transition: background 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        pointer-events: auto; /* Crucial: Ensure thumb is interactive */
    }

    /* Active state for thumbs */
    input[type="range"]:active::-webkit-slider-thumb,
    input[type="range"]:focus::-webkit-slider-thumb {
        background: #6D28D9; /* Darker purple on active/focus */
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.5);
        cursor: grabbing; /* Indicate it's being dragged */
    }

    input[type="range"]:active::-moz-range-thumb,
    input[type="range"]:focus::-moz-range-thumb {
        background: #6D28D9;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.5);
        cursor: grabbing; /* Indicate it's being dragged */
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if the alumni tracker elements exist on the page before initializing
        if (!document.getElementById('alumni_search_input')) {
            return; // Exit if the component is not present
        }

        // Filter elements (now including sliders)
        const fromYearSlider = document.getElementById('from_year');
        const toYearSlider = document.getElementById('to_year');
        const fromYearValueSpan = document.getElementById('from_year_value');
        const toYearValueSpan = document.getElementById('to_year_value');

        const genderSelect = document.getElementById('gender');
        
        const ageFromSlider = document.getElementById('age_from');
        const ageToSlider = document.getElementById('age_to');
        const ageFromValueSpan = document.getElementById('age_from_value');
        const ageToValueSpan = document.getElementById('age_to_value');

        const salaryFromSlider = document.getElementById('salary_from');
        const salaryToSlider = document.getElementById('salary_to');
        const salaryFromValueSpan = document.getElementById('salary_from_value');
        const salaryToValueSpan = document.getElementById('salary_to_value');

        const degreeProgramSelect = document.getElementById('degree_program');
        const industrySelect = document.getElementById('industry');
        const jobTitleSelect = document.getElementById('job_title');
        const employmentStatusSelect = document.getElementById('employment_status');

        // Buttons and display areas
        const applyFiltersBtn = document.getElementById('apply_filters_btn');
        const alumniSearchInput = document.getElementById('alumni_search_input');
        const alumniSearchResults = document.getElementById('alumni_search_results');
        const searchAlumniBtn = document.getElementById('search_alumni_btn');
        const aiInsightsLoading = document.getElementById('ai_insights_loading');
        const aiInsightsDisplay = document.getElementById('ai_insights_display');
        const aiInsightsError = document.getElementById('ai_insights_error');
        const groupChartsContainer = document.getElementById('group_charts_container');

        let selectedAlumniId = null;

        // Filter group toggles and panels
        const filterToggles = {
            'toggle_year_filter': 'year_filter_panel',
            'toggle_gender_filter': 'gender_filter_panel',
            'toggle_age_filter': 'age_filter_panel',
            'toggle_salary_filter': 'salary_filter_panel',
            'toggle_degree_program_filter': 'degree_program_filter_panel',
            'toggle_industry_filter': 'industry_filter_panel',
            'toggle_job_title_filter': 'job_title_filter_panel',
            'toggle_employment_status_filter': 'employment_status_filter_panel',
        };

        // Function to manage filter panel visibility (grayed out vs. active)
        function toggleFilterPanel(toggleId, panelId, isChecked) {
            const panel = document.getElementById(panelId);
            if (panel) {
                if (isChecked) {
                    panel.classList.remove('opacity-50', 'pointer-events-none'); // Remove grayed out state
                    panel.classList.add('opacity-100', 'pointer-events-auto'); // Make fully visible and interactive
                } else {
                    panel.classList.remove('opacity-100', 'pointer-events-auto'); // Remove fully visible state
                    panel.classList.add('opacity-50', 'pointer-events-none'); // Make grayed out and non-interactive
                    // Optionally clear values when grayed out
                    const inputs = panel.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        if (input.tagName === 'SELECT') {
                            input.value = '';
                        } else if (input.type === 'range') {
                            // Reset range sliders to min/max
                            if (input.id.includes('_from')) {
                                input.value = input.min;
                            } else if (input.id.includes('_to')) {
                                input.value = input.max;
                            }
                            // Trigger input event to update displayed values
                            input.dispatchEvent(new Event('input'));
                        } else if (input.type === 'number' || input.type === 'text') {
                            input.value = '';
                        }
                    });
                }
            }
        }

        // Initialize filter panel visibility and event listeners
        for (const toggleId in filterToggles) {
            const toggle = document.getElementById(toggleId);
            if (toggle) {
                // Initial state: year filter is on, others are off (grayed out)
                toggleFilterPanel(toggleId, filterToggles[toggleId], toggle.checked);
                
                toggle.addEventListener('change', function() {
                    toggleFilterPanel(toggleId, filterToggles[toggleId], this.checked);
                });
            }
        }

        // Function to recreate a canvas element
        function recreateCanvas(id) {
            const oldCanvas = document.getElementById(id);
            if (oldCanvas) {
                const parent = oldCanvas.parentNode;
                const newCanvas = document.createElement('canvas');
                newCanvas.id = id;
                newCanvas.className = oldCanvas.className; // Preserve existing classes
                parent.replaceChild(newCanvas, oldCanvas);
                return newCanvas;
            }
            return null;
        }

        // Function to populate select dropdowns and set slider ranges
        async function populateFilterOptions() {
            try {
                const response = await fetch('/admin/alumni-tracker/filter-options');
                const data = await response.json();
                console.log('Filter options data:', data);

                function populateSelect(selectElement, options) {
                    selectElement.innerHTML = '<option value="">Any</option>'; // Default "Any" option
                    options.forEach(option => {
                        if (option) {
                            const opt = document.createElement('option');
                            opt.value = option;
                            opt.textContent = option;
                            selectElement.appendChild(opt);
                        }
                    });
                }

                // Populate dropdowns
                populateSelect(genderSelect, data.genders);
                populateSelect(degreeProgramSelect, data.degreePrograms);
                populateSelect(industrySelect, data.industries);
                populateSelect(jobTitleSelect, data.jobTitles);
                populateSelect(employmentStatusSelect, data.employmentStatuses);

                // Set year slider ranges and initial values
                if (data.years && data.years.length > 0) {
                    const minYear = Math.min(...data.years);
                    const maxYear = Math.max(...data.years);

                    fromYearSlider.min = minYear;
                    fromYearSlider.max = maxYear;
                    fromYearSlider.value = minYear;

                    toYearSlider.min = minYear;
                    toYearSlider.max = maxYear;
                    toYearSlider.value = maxYear;
                } else {
                    // Fallback if no years are available
                    fromYearSlider.min = 1950;
                    fromYearSlider.max = new Date().getFullYear();
                    fromYearSlider.value = 1950;

                    toYearSlider.min = 1950;
                    toYearSlider.max = new Date().getFullYear();
                    toYearSlider.value = new Date().getFullYear();
                }

                // Initial update for all sliders to display values
                setupRangeSlider(fromYearSlider, toYearSlider, fromYearValueSpan, toYearValueSpan);
                setupRangeSlider(ageFromSlider, ageToSlider, ageFromValueSpan, ageToValueSpan);
                setupRangeSlider(salaryFromSlider, salaryToSlider, salaryFromValueSpan, salaryToValueSpan, val => `₱${parseInt(val).toLocaleString()}`);

            } catch (error) {
                console.error('Error fetching filter options:', error);
            }
        }

        // Slider event listeners and logic for dual-handle sliders
        function setupRangeSlider(fromSlider, toSlider, fromSpan, toSpan, formatter = val => val) {
            const updateDisplay = () => {
                fromSpan.textContent = formatter(parseInt(fromSlider.value));
                toSpan.textContent = formatter(parseInt(toSlider.value));
            };

            fromSlider.addEventListener('input', () => {
                let fromVal = parseInt(fromSlider.value);
                let toVal = parseInt(toSlider.value);

                if (fromVal > toVal) {
                    fromSlider.value = toVal; // Cap 'from' at 'to'
                }
                updateDisplay();
            });

            toSlider.addEventListener('input', () => {
                let fromVal = parseInt(fromSlider.value);
                let toVal = parseInt(toSlider.value);

                if (toVal < fromVal) {
                    toSlider.value = fromVal; // Cap 'to' at 'from'
                }
                updateDisplay();
            });

            // Z-index management to ensure the active thumb is on top
            fromSlider.addEventListener('mousedown', () => {
                fromSlider.style.zIndex = 3;
                toSlider.style.zIndex = 2;
            });
            toSlider.addEventListener('mousedown', () => {
                toSlider.style.zIndex = 3;
                fromSlider.style.zIndex = 2;
            });
            fromSlider.addEventListener('touchstart', () => {
                fromSlider.style.zIndex = 3;
                toSlider.style.zIndex = 2;
            });
            toSlider.addEventListener('touchstart', () => {
                toSlider.style.zIndex = 3;
                fromSlider.style.zIndex = 2;
            });

            // Initial display update
            updateDisplay();
        }

        // --- New functions for client-side chart data aggregation ---
        function getEmploymentStatusDistribution(alumniData) {
            const distribution = {};
            alumniData.forEach(alumnus => {
                const status = alumnus.employment_status || 'Unknown';
                distribution[status] = (distribution[status] || 0) + 1;
            });

            const total = alumniData.length;
            const percentages = {};
            for (const status in distribution) {
                percentages[status] = (distribution[status] / total) * 100;
            }
            return percentages;
        }

        function getTopCategories(alumniData, categoryKey, limit = 5) {
            const counts = {};
            alumniData.forEach(alumnus => {
                const category = alumnus[categoryKey] || 'Unknown';
                counts[category] = (counts[category] || 0) + 1;
            });

            const sortedCategories = Object.entries(counts)
                .sort(([, countA], [, countB]) => countB - countA)
                .slice(0, limit)
                .map(([category, count]) => ({ [categoryKey]: category, count }));
            
            return sortedCategories;
        }

        // Function to fetch and display AI insights
        async function fetchInsights(params, isIndividualSearch = false) {
            aiInsightsDisplay.innerHTML = '';
            aiInsightsError.classList.add('hidden');
            aiInsightsLoading.classList.remove('hidden');
            groupChartsContainer.classList.add('hidden'); // Hide charts by default

            try {
                const queryString = new URLSearchParams(params).toString();
                const response = await fetch(`/admin/alumni-tracker/insights?${queryString}`);
                const responseData = await response.json(); // Renamed to avoid conflict with 'data' variable

                if (!response.ok) {
                    throw new Error(responseData.insight || 'Failed to fetch insights.');
                }

                if (isIndividualSearch) {
                    const data = responseData; // For individual, the whole response is the insight
                    // Render structured data for individual insights
                    let htmlContent = '';
                    if (data.name) {
                        htmlContent += `<h4 class="text-xl font-bold text-gray-900 mb-2">${data.name}</h4>`;
                    }
                    if (data.summary) {
                        htmlContent += `<p class="mb-4">${data.summary}</p>`;
                    }

                    if (data.education) {
                        htmlContent += `<h5 class="text-lg font-semibold text-gray-800 mb-2">Education:</h5>`;
                        htmlContent += `<ul class="list-disc list-inside mb-4 space-y-1">`;
                        if (data.education.degree) htmlContent += `<li><strong>Degree:</strong> ${data.education.degree}</li>`;
                        if (data.education.major) htmlContent += `<li><strong>Major:</strong> ${data.education.major}</li>`;
                        if (data.education.graduation_year) htmlContent += `<li><strong>Graduation Year:</strong> ${data.education.graduation_year}</li>`;
                        htmlContent += `</ul>`;
                    }

                    if (data.career_details) {
                        htmlContent += `<h5 class="text-lg font-semibold text-gray-800 mb-2">Career Details:</h5>`;
                        htmlContent += `<ul class="list-disc list-inside mb-4 space-y-1">`;
                        if (data.career_details.company) htmlContent += `<li><strong>Company:</strong> ${data.career_details.company}</li>`;
                        if (data.career_details.job_title) htmlContent += `<li><strong>Job Title:</strong> ${data.career_details.job_title}</li>`;
                        if (data.career_details.industry) htmlContent += `<li><strong>Industry:</strong> ${data.career_details.industry}</li>`;
                        if (data.career_details.employment_status) htmlContent += `<li><strong>Employment Status:</strong> ${data.career_details.employment_status}</li>`;
                        if (data.career_details.monthly_salary) htmlContent += `<li><strong>Monthly Salary:</strong> ₱${data.career_details.monthly_salary.toLocaleString()}</li>`;
                        if (data.career_details.role_description) htmlContent += `<li><strong>Role Description:</strong> ${data.career_details.role_description}</li>`;
                        htmlContent += `</ul>`;
                    }

                    if (data.key_achievements && data.key_achievements.length > 0) {
                        htmlContent += `<h5 class="text-lg font-semibold text-gray-800 mb-2">Key Achievements:</h5>`;
                        htmlContent += `<ul class="list-disc list-inside mb-4 space-y-1">`;
                        data.key_achievements.forEach(achievement => {
                            htmlContent += `<li>${achievement}</li>`;
                        });
                        htmlContent += `</ul>`;
                    }

                    if (data.overall_impression) {
                        htmlContent += `<h5 class="text-lg font-semibold text-gray-800 mb-2">Overall Impression:</h5>`;
                        htmlContent += `<p>${data.overall_impression}</p>`;
                    }

                    aiInsightsDisplay.innerHTML = htmlContent;

                } else {
                    // For group analysis, responseData contains both ai_insights and alumni_data
                    const aiInsights = responseData.ai_insights;
                    const alumniData = responseData.alumni_data;

                    let htmlContent = '';
                    if (aiInsights.summary) {
                        htmlContent += `<p class="mb-4">${aiInsights.summary}</p>`;
                    }
                    if (aiInsights.key_trends && aiInsights.key_trends.length > 0) {
                        htmlContent += '<h4 class="text-xl font-semibold text-gray-800 mb-2">Key Trends:</h4>';
                        htmlContent += '<ul class="list-disc list-inside mb-4 space-y-1">';
                        aiInsights.key_trends.forEach(trend => {
                            htmlContent += `<li>${trend}</li>`;
                        });
                        htmlContent += '</ul>';
                    }
                    if (aiInsights.career_progression_notes) {
                        htmlContent += `<h4 class="text-xl font-semibold text-gray-800 mb-2">Career Progression Notes:</h4>`;
                        htmlContent += `<p class="mb-4">${aiInsights.career_progression_notes}</p>`;
                    }

                    aiInsightsDisplay.innerHTML = htmlContent;

                    // Render charts using client-side aggregated data
                    if (alumniData && alumniData.length > 0) {
                        groupChartsContainer.classList.remove('hidden');

                        // Employment Status Distribution Chart
                        const employmentStatusData = getEmploymentStatusDistribution(alumniData);
                        if (Object.keys(employmentStatusData).length > 0) {
                            const canvas = recreateCanvas('employmentStatusChart');
                            if (canvas) {
                                const ctx = canvas.getContext('2d');
                                new Chart(ctx, {
                                    type: 'pie',
                                    data: {
                                        labels: Object.keys(employmentStatusData),
                                        datasets: [{
                                            data: Object.values(employmentStatusData),
                                            backgroundColor: [
                                                'rgba(75, 192, 192, 0.8)', // Teal
                                                'rgba(255, 99, 132, 0.8)', // Red
                                                'rgba(255, 205, 86, 0.8)', // Yellow
                                                'rgba(54, 162, 235, 0.8)', // Blue
                                                'rgba(153, 102, 255, 0.8)' // Purple
                                            ],
                                            borderColor: '#ffffff',
                                            borderWidth: 2
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: { position: 'bottom' },
                                            title: { display: false },
                                        }
                                    }
                                });
                            }
                        }

                        // Top Industries Chart
                        const topIndustries = getTopCategories(alumniData, 'industry');
                        if (topIndustries.length > 0) {
                            const canvas = recreateCanvas('topIndustriesChart');
                            if (canvas) {
                                const ctx = canvas.getContext('2d');
                                new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: topIndustries.map(item => item.industry),
                                        datasets: [{
                                            label: 'Number of Alumni',
                                            data: topIndustries.map(item => item.count),
                                            backgroundColor: 'rgba(153, 102, 255, 0.8)', // Purple
                                            borderColor: 'rgba(153, 102, 255, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: { display: false },
                                            title: { display: false },
                                        },
                                        scales: {
                                            y: { beginAtZero: true }
                                        }
                                    }
                                });
                            }
                        }

                        // Top Degree Programs Chart
                        const topDegreePrograms = getTopCategories(alumniData, 'degree_program');
                        if (topDegreePrograms.length > 0) {
                            const canvas = recreateCanvas('topDegreeProgramsChart');
                            if (canvas) {
                                const ctx = canvas.getContext('2d');
                                new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: topDegreePrograms.map(item => item.degree_program),
                                        datasets: [{
                                            label: 'Number of Alumni',
                                            data: topDegreePrograms.map(item => item.count),
                                            backgroundColor: 'rgba(255, 159, 64, 0.8)', // Orange
                                            borderColor: 'rgba(255, 159, 64, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: { display: false },
                                            title: { display: false },
                                        },
                                        scales: {
                                            y: { beginAtZero: true }
                                        }
                                    }
                                });
                            }
                        }
                    } else {
                        // If no alumni data for charts, hide the container
                        groupChartsContainer.classList.add('hidden');
                    }
                }

            } catch (error) {
                aiInsightsError.textContent = `Error: ${error.message}`;
                aiInsightsError.classList.remove('hidden');
                aiInsightsDisplay.innerHTML = '<p class="text-gray-500 text-center">Could not load insights. Please try again.</p>';
                console.error('Error fetching AI insights:', error);
            } finally {
                aiInsightsLoading.classList.add('hidden');
            }
        }

        // Event listener for Apply Filters button
        applyFiltersBtn.addEventListener('click', function() {
            selectedAlumniId = null; // Clear individual alumni selection
            alumniSearchInput.value = ''; // Clear search input
            alumniSearchResults.classList.add('hidden'); // Hide search results

            const params = {
                from_year: document.getElementById('toggle_year_filter').checked ? fromYearSlider.value : '', // Only send if toggle is on
                to_year: document.getElementById('toggle_year_filter').checked ? toYearSlider.value : '', // Only send if toggle is on
                degree_program: document.getElementById('toggle_degree_program_filter').checked ? degreeProgramSelect.value : '',
                industry: document.getElementById('toggle_industry_filter').checked ? industrySelect.value : '',
                job_title: document.getElementById('toggle_job_title_filter').checked ? jobTitleSelect.value : '',
                employment_status: document.getElementById('toggle_employment_status_filter').checked ? employmentStatusSelect.value : '',
                gender: document.getElementById('toggle_gender_filter').checked ? genderSelect.value : '',
                age_from: document.getElementById('toggle_age_filter').checked ? ageFromSlider.value : '',
                age_to: document.getElementById('toggle_age_filter').checked ? ageToSlider.value : '',
                salary_from: document.getElementById('toggle_salary_filter').checked ? salaryFromSlider.value : '',
                salary_to: document.getElementById('toggle_salary_filter').checked ? salaryToSlider.value : '',
            };
            fetchInsights(params, false); // Not an individual search
        });

        // Event listener for Alumni Search Input (Autocomplete)
        let searchTimeout;
        alumniSearchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;
            if (query.length < 2) {
                alumniSearchResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`/admin/alumni-tracker/search?query=${encodeURIComponent(query)}`);
                    const alumni = await response.json();
                    console.log('Alumni search results:', alumni);

                    alumniSearchResults.innerHTML = '';
                    if (alumni.length > 0) {
                        alumni.forEach(item => {
                            const div = document.createElement('div');
                            div.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-100', 'text-gray-800', 'font-medium');
                            div.textContent = item.name;
                            div.dataset.id = item.id;
                            div.addEventListener('click', function() {
                                alumniSearchInput.value = item.name;
                                selectedAlumniId = item.id;
                                alumniSearchResults.classList.add('hidden');
                            });
                            alumniSearchResults.appendChild(div);
                        });
                        alumniSearchResults.classList.remove('hidden');
                    } else {
                        alumniSearchResults.classList.add('hidden');
                    }
                } catch (error) {
                    console.error('Error during alumni search:', error);
                    alumniSearchResults.classList.add('hidden');
                }
            }, 300); // Debounce search
        });

        // Hide search results when clicking outside
        document.addEventListener('click', function(event) {
            if (!alumniSearchInput.contains(event.target) && !alumniSearchResults.contains(event.target)) {
                alumniSearchResults.classList.add('hidden');
            }
        });

        // Event listener for Get Individual Insight button
        searchAlumniBtn.addEventListener('click', function() {
            // Clear filter selections when performing individual search
            // Iterate through all filter toggles and set them to unchecked, which will gray out and clear panels
            for (const toggleId in filterToggles) {
                const toggle = document.getElementById(toggleId);
                if (toggle) {
                    toggle.checked = false;
                    toggleFilterPanel(toggleId, filterToggles[toggleId], false);
                }
            }

            if (selectedAlumniId) {
                fetchInsights({ alumni_id: selectedAlumniId }, true); // Is an individual search
            } else if (alumniSearchInput.value.trim() !== '') {
                aiInsightsDisplay.innerHTML = ''; // Clear previous content
                aiInsightsError.textContent = 'Please select an alumni from the dropdown suggestions.';
                aiInsightsError.classList.remove('hidden');
            } else {
                aiInsightsDisplay.innerHTML = ''; // Clear previous content
                aiInsightsError.textContent = 'Enter an alumni name to search.';
                aiInsightsError.classList.remove('hidden');
            }
        });

        // Initial population of filter options
        populateFilterOptions();
    });
</script>
@endpush
