@foreach ($users as $user)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            @if($user->role == 'guest')
                <span class="inline-block px-2 py-1 bg-blue-500 text-black-800 rounded">{{ $user->role }}</span>
            @elseif($user->role == 'alumni')
                <span class="inline-block px-2 py-1 bg-green-500 text-black-800 rounded">{{ $user->role }}</span>
            @elseif($user->role == 'student')
                <span class="inline-block px-2 py-1 bg-yellow-500 text-black-800 rounded">{{ $user->role }}</span>
            @elseif($user->role == 'admin')
                <span class="text-white inline-block px-2 py-1 bg-red-500 text-red-800 rounded">{{ $user->role }}</span>                                         
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 mb-2 mr-2">Edit</a>
            <form class="inline-block" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 focus:outline-none px-4 py-2 rounded-md mb-2 mr-2">Delete</button>
            </form>
        </td>
    </tr>
@endforeach
