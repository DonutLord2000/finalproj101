<x-app-layout>
    @section('title', 'GRC - Scholarship Management')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholarship Application Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.scholarships.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Applications
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Application Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Applicant</p>
                            <p class="font-medium">{{ $application->name ?? 'N/A' }}</p>
                            <p class="text-blue-600">{{ $application->email }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                   ($application->status === 'under_review' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Submitted At</p>
                            <p class="font-medium">{{ $application->created_at->format('F d, Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $application->created_at->format('h:i A') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Last Updated</p>
                            <p class="font-medium">{{ $application->updated_at->format('F d, Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $application->updated_at->format('h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Documents</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($application->documents as $document)
                            <div class="border rounded-lg p-4 flex items-center justify-between">
                                <div>
                                    <p class="font-medium">{{ $document->original_name }}</p>
                                    <p class="text-sm text-gray-500">Uploaded: {{ $document->created_at->format('M d, Y') }}</p>
                                </div>
                                <a href="{{ route('scholarships.view-document', $document->id) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Actions</h3>
                    
                    <div class="space-y-4">
                        @if($application->status === 'pending')
                            <form action="{{ route('admin.scholarships.review', $application->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="admin_notes" class="block text-sm font-medium text-gray-700">Admin Notes</label>
                                    <textarea id="admin_notes" name="admin_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $application->admin_notes }}</textarea>
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Mark as Under Review
                                </button>
                            </form>
                        @endif
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            @if($application->status !== 'approved')
                                <form action="{{ route('admin.scholarships.approve', $application->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label for="admin_notes_approve" class="block text-sm font-medium text-gray-700">Admin Notes</label>
                                        <textarea id="admin_notes_approve" name="admin_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $application->admin_notes }}</textarea>
                                    </div>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Approve Application
                                    </button>
                                </form>
                            @endif
                            
                            @if($application->status !== 'rejected')
                                <form action="{{ route('admin.scholarships.reject', $application->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label for="admin_notes_reject" class="block text-sm font-medium text-gray-700">Admin Notes</label>
                                        <textarea id="admin_notes_reject" name="admin_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $application->admin_notes }}</textarea>
                                    </div>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Reject Application
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

