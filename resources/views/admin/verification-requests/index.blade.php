<x-app-layout>
    @section('title', 'GRC - Verification Requests Management')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verification Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Pending Requests -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Pending Requests</h3>
                    <div class="mb-4">
                        <input type="text" id="pendingSearch" placeholder="Search pending requests..." class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="pendingRequestsBody">
                            @foreach ($pendingRequests as $request)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ $request->user->profile_photo_url }}" alt="{{ $request->user->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $request->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $request->user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $request->created_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @foreach ($request->documents as $document)
                                            <a href="{{ route('verification.show-document', $document->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $document->original_name }}
                                            </a><br>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('alumni.profile.show', $request->user->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">View Profile</a>
                                        <form action="{{ route('verification.approve', $request->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                        </form>
                                        <form action="{{ route('verification.reject', $request->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $pendingRequests->links() }}
                    </div>
                </div>
            </div>

            <!-- Processed Requests -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Processed Requests</h3>
                    <div class="mb-4">
                        <input type="text" id="processedSearch" placeholder="Search processed requests..." class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Processed At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="processedRequestsBody">
                            @foreach ($processedRequests as $request)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ $request->user->profile_photo_url }}" alt="{{ $request->user->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $request->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $request->user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $request->updated_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->updated_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @foreach ($request->documents as $document)
                                            <a href="{{ route('verification.show-document', $document->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $document->original_name }}
                                            </a><br>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('alumni.profile.show', $request->user->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">View Profile</a>
                                        @if ($request->status === 'approved')
                                            <form action="{{ route('verification.reject', $request->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                            </form>
                                        @else
                                            <form action="{{ route('verification.approve', $request->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $processedRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterTable(inputId, tableBodyId) {
            const input = document.getElementById(inputId);
            const filter = input.value.toUpperCase();
            const tbody = document.getElementById(tableBodyId);
            const rows = tbody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const nameCell = rows[i].getElementsByTagName('td')[0];
                if (nameCell) {
                    const nameValue = nameCell.textContent || nameCell.innerText;
                    if (nameValue.toUpperCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }

        document.getElementById('pendingSearch').addEventListener('keyup', () => filterTable('pendingSearch', 'pendingRequestsBody'));
        document.getElementById('processedSearch').addEventListener('keyup', () => filterTable('processedSearch', 'processedRequestsBody'));
    </script>
</x-app-layout>

