<x-app-layout>
    @push('styles')
        <style>
            /* Ensure word counter is always visible */
            .word-counter {
                display: flex !important;
                justify-content: space-between !important;
                margin-top: 0.25rem !important;
                font-size: 0.875rem !important;
                color: #6b7280 !important;
            }
            
            /* Style for the loading spinner */
            .loading-spinner {
                display: inline-block;
                width: 1rem;
                height: 1rem;
                border: 2px solid rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                border-top-color: white;
                animation: spin 1s ease-in-out infinite;
            }
            
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
        </style>
    @endpush
    @section('title', 'GRC - Edit Comment')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-4">Edit Comment</h2>
                    <form action="{{ route('comments.update', $comment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                            <textarea name="content" id="content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('content', $comment->content) }}</textarea>
                            <div class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('threads.show', $comment->thread_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 disabled:opacity-25 transition mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
                                Update Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
        <script src="{{ asset('js/word-counter.js') }}"></script>
    @endpush
</x-app-layout>
