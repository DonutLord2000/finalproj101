<x-app-layout>
    @section('title', 'GRC - Scholarship Management')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholarship Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8" style="width: 1100pt">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Manage Scholarship Applications</h3>
                        
                        <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-4">
                            <!-- Search Form -->
                            <form action="{{ route('admin.scholarships.index') }}" method="GET" class="flex">
                                <input type="text" name="search" placeholder="Search by email or name" value="{{ request('search') }}"
                                    class="rounded-l-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" style="width: 500px">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Search
                                </button>
                            </form>
                            
                            <!-- Filter by Status -->
                            <form action="{{ route('admin.scholarships.index') }}" method="GET" class="flex">
                                <select name="status" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </form>

                            <!-- Form Management Button -->
                            <a href="{{ route('admin.scholarships.forms') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Manage Forms
                            </a>
                        </div>
                    </div>
                    
                    <!-- Applications Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px">
                                        <a href="{{ route('admin.scholarships.index', ['sort' => 'email', 'direction' => request('direction') == 'asc' && request('sort') == 'email' ? 'desc' : 'asc'] + request()->except(['sort', 'direction'])) }}" class="flex items-center">
                                            Applicant
                                            @if(request('sort') == 'email')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" >
                                        <a href="{{ route('admin.scholarships.index', ['sort' => 'created_at', 'direction' => request('direction') == 'asc' && request('sort') == 'created_at' ? 'desc' : 'asc'] + request()->except(['sort', 'direction'])) }}" class="flex items-center">
                                            Submitted At
                                            @if(request('sort') == 'created_at')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px">
                                        <a href="{{ route('admin.scholarships.index', ['sort' => 'status', 'direction' => request('direction') == 'asc' && request('sort') == 'status' ? 'desc' : 'asc'] + request()->except(['sort', 'direction'])) }}" class="flex items-center">
                                            Status
                                            @if(request('sort') == 'status')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" >
                                        Documents
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($applications as $application)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($application->user)
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full" src="{{ $application->user->profile_photo_url }}" alt="{{ $application->user->name }}">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $application->user->name }}
                                                        </div>
                                                @endif
                                                <div class="text-sm text-gray-500">
                                                    {{ $application->email }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $application->created_at->format('M d, Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $application->created_at->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                   ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                                   ($application->status === 'under_review' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" >
                                            @foreach($application->documents as $document)
                                                <a href="{{ route('scholarships.view-document', $document->id) }}" target="_blank" class="text-blue-600 hover:text-blue-900 block">
                                                    {{ $document->original_name }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.scholarships.show', $application->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                                View Details
                                            </a>
                                            
                                            @if($application->status === 'pending')
                                                <form action="{{ route('admin.scholarships.review', $application->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-3">Review</button>
                                                </form>
                                            @endif
                                            
                                            @if($application->status !== 'approved')
                                                <form action="{{ route('admin.scholarships.approve', $application->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                                </form>
                                            @endif
                                            
                                            @if($application->status !== 'rejected')
                                                <form action="{{ route('admin.scholarships.reject', $application->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No scholarship applications found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4" >
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

