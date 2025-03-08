@extends('layouts.alumni')
@section('title', 'GRC - Alumni')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-center text-3xl font-bold mb-8 text-gray-800">Alumni List</h1>
              
            <div class="mb-6 px-4">
                <form id="searchForm" action="{{ route('alumni.index') }}" method="GET" class="flex">
                    <input 
                        type="text" 
                        id="searchInput" 
                        name="search" 
                        placeholder="Search by name, degree program, or industry..." 
                        class="border border-gray-300 rounded-full px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ $search ?? '' }}"
                    >
                </form>
            </div>
            
            <div class="mb-6 px-4">
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Show/Hide Columns:</h3>
                <div class="flex flex-wrap gap-4">
                    @foreach($additionalColumns as $column => $label)
                        <label class="inline-flex items-center">
                            <input type="checkbox" class="form-checkbox column-toggle h-5 w-5 text-blue-600" name="{{ $column }}" value="{{ $column }}">
                            <span class="ml-2 text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="mb-4">
                <a href="{{ route('alumni.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Alumnus
                </a>
            </div>  
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300" id="alumniTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            <div class="group inline-flex cursor-pointer" data-sort="name">
                                                Name
                                                <span class="ml-2 flex-none rounded sort-icon {{ $sortColumn === 'name' ? ($sortDirection === 'asc' ? 'text-gray-900' : 'text-gray-900 rotate-180') : 'invisible group-hover:visible text-gray-400' }}">
                                                    ▲
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            <div class="group inline-flex cursor-pointer" data-sort="gender">
                                                Gender
                                                <span class="ml-2 flex-none rounded sort-icon {{ $sortColumn === 'gender' ? ($sortDirection === 'asc' ? 'text-gray-900' : 'text-gray-900 rotate-180') : 'invisible group-hover:visible text-gray-400' }}">
                                                    ▲
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            <div class="group inline-flex cursor-pointer" data-sort="degree_program">
                                                Degree Program
                                                <span class="ml-2 flex-none rounded sort-icon {{ $sortColumn === 'degree_program' ? ($sortDirection === 'asc' ? 'text-gray-900' : 'text-gray-900 rotate-180') : 'invisible group-hover:visible text-gray-400' }}">
                                                    ▲
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            <div class="group inline-flex cursor-pointer" data-sort="employment_status">
                                                Employment Status
                                                <span class="ml-2 flex-none rounded sort-icon {{ $sortColumn === 'employment_status' ? ($sortDirection === 'asc' ? 'text-gray-900' : 'text-gray-900 rotate-180') : 'invisible group-hover:visible text-gray-400' }}">
                                                    ▲
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            <div class="group inline-flex cursor-pointer" data-sort="industry">
                                                Industry
                                                <span class="ml-2 flex-none rounded sort-icon {{ $sortColumn === 'industry' ? ($sortDirection === 'asc' ? 'text-gray-900' : 'text-gray-900 rotate-180') : 'invisible group-hover:visible text-gray-400' }}">
                                                    ▲
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            <div class="group inline-flex cursor-pointer" data-sort="year_graduated">
                                                Year Graduated
                                                <span class="ml-2 flex-none rounded sort-icon {{ $sortColumn === 'year_graduated' ? ($sortDirection === 'asc' ? 'text-gray-900' : 'text-gray-900 rotate-180') : 'invisible group-hover:visible text-gray-400' }}">
                                                    ▲
                                                </span>
                                            </div>
                                        </th>
                                        @foreach($additionalColumns as $column => $label)
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 column-{{ $column }} hidden">
                                                <div class="group inline-flex cursor-pointer" data-sort="{{ $column }}">
                                                    {{ $label }}
                                                    <span class="ml-2 flex-none rounded sort-icon {{ $sortColumn === $column ? ($sortDirection === 'asc' ? 'text-gray-900' : 'text-gray-900 rotate-180') : 'invisible group-hover:visible text-gray-400' }}">
                                                        ▲
                                                    </span>
                                                </div>
                                            </th>
                                        @endforeach
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white" id="alumniTableBody">
                                    @include('alumni.partials.table_rows', ['alumni' => $alumni, 'additionalColumns' => $additionalColumns])
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('alumniTable');
            const toggles = document.querySelectorAll('.column-toggle');
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            let currentSort = {
                column: '{{ $sortColumn }}',
                direction: '{{ $sortDirection }}'
            };
        
            // Handle column toggles
            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    applyColumnVisibility();
                    updateSortHandlers();
                });
            });
        
            // Handle sorting
            function updateSortHandlers() {
                table.querySelectorAll('[data-sort]').forEach(header => {
                    header.removeEventListener('click', sortHandler);
                    header.addEventListener('click', sortHandler);
                });
            }
        
            function sortHandler() {
                const column = this.dataset.sort;
                const direction = column === currentSort.column && currentSort.direction === 'asc' ? 'desc' : 'asc';
                
                // Update sort state
                currentSort = { column, direction };
                
                updateTable(column, direction);
            }
        
            updateSortHandlers();
        
            // Handle search
            searchInput.addEventListener('input', debounce(function() {
                updateTable(currentSort.column, currentSort.direction);
            }, 300));
        
            // Prevent form submission on enter
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                updateTable(currentSort.column, currentSort.direction);
            });
        
            function updateTable(sortColumn, sortDirection) {
                const query = searchInput.value;
                const tbody = table.querySelector('tbody');
                tbody.style.opacity = '0.5';
        
                fetch(`${window.location.pathname}?search=${encodeURIComponent(query)}&sort=${sortColumn}&direction=${sortDirection}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    tbody.innerHTML = html;
                    tbody.style.opacity = '1';
                    
                    // Update sort indicators
                    updateSortIndicators(sortColumn, sortDirection);
                    
                    // Update URL without page reload
                    const url = new URL(window.location);
                    url.searchParams.set('search', query);
                    url.searchParams.set('sort', sortColumn);
                    url.searchParams.set('direction', sortDirection);
                    window.history.pushState({}, '', url);
        
                    // Reapply column visibility
                    applyColumnVisibility();
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.style.opacity = '1';
                });
            }
        
            function updateSortIndicators(column, direction) {
                table.querySelectorAll('.sort-icon').forEach(icon => {
                    const header = icon.closest('[data-sort]');
                    if (header.dataset.sort === column) {
                        icon.classList.remove('invisible', 'text-gray-400');
                        icon.classList.add('text-gray-900');
                        icon.classList.toggle('rotate-180', direction === 'desc');
                    } else {
                        icon.classList.add('invisible', 'text-gray-400');
                        icon.classList.remove('text-gray-900', 'rotate-180');
                    }
                });
            }
        
            function applyColumnVisibility() {
                toggles.forEach(toggle => {
                    const column = toggle.value;
                    const cells = document.querySelectorAll(`.column-${column}`);
                    const isVisible = toggle.checked;
                    cells.forEach(cell => {
                        cell.classList.toggle('hidden', !isVisible);
                    });
                });
            }
        
            // Apply initial column visibility
            applyColumnVisibility();
        
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        });
        
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this alumnus?')) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/alumni/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
        }
        </script>

    <style>
        .max-w-7xl {
        max-width: 95% !important;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .sort-icon {
            transition: transform 0.2s ease-in-out;
        }

        #alumniTable {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        #alumniTable th {
            position: sticky;
            top: 0;
            background: #f3f4f6;
            z-index: 10;
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
        }

        #alumniTable th, #alumniTable td {
            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }

        #alumniTable th:first-child, #alumniTable td:first-child {
            border-left: 1px solid #e5e7eb;
        }

        #alumniTable tr:first-child th {
            border-top: 1px solid #e5e7eb;
        }

        #alumniTable tr:last-child td {
            border-bottom: none;
        }

        #alumniTable th:first-child {
            border-top-left-radius: 0.5rem;
        }

        #alumniTable th:last-child {
            border-top-right-radius: 0.5rem;
        }

        #alumniTable tr:last-child td:first-child {
            border-bottom-left-radius: 0.5rem;
        }

        #alumniTable tr:last-child td:last-child {
            border-bottom-right-radius: 0.5rem;
        }

        tbody tr:hover {
            background-color: #f9fafb;
        }
    </style>
@endsection
