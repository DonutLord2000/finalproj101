@foreach ($alumni as $alumnus)
    <tr class="hover:bg-gray-50">
        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
            {{ $alumnus->name }}
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            {{ $alumnus->gender ?? 'N/A' }}
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            {{ $alumnus->degree_program }}
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            {{ $alumnus->employment_status ?? 'N/A' }}
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            {{ $alumnus->industry ?? 'N/A' }}
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            {{ $alumnus->year_graduated }}
        </td>
        @foreach($additionalColumns as $column => $label)
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 column-{{ $column }} hidden">
                {{ $alumnus->$column ?? 'N/A' }}
            </td>
        @endforeach
        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
            <a href="{{ route('alumni.show', $alumnus) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
            <a href="{{ route('alumni.edit', $alumnus) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
            <button onclick="confirmDelete({{ $alumnus->id }})" class="text-red-600 hover:text-red-900">Delete</button>
        </td>
    </tr>
@endforeach

@if($alumni->hasPages())
    <tr>
        <td colspan="{{ 7 + count($additionalColumns) }}" class="px-3 py-4">
            {{ $alumni->appends(request()->except('page'))->links() }}
        </td>
    </tr>
@endif

