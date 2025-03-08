@extends('layouts.admin')
@section('title', 'GRC - Activity Logs')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4">
                    <form action="{{ route('activity-logs.index') }}" method="GET">
                        <select name="log_type" onchange="this.form.submit()" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="all" {{ $logType === 'all' ? 'selected' : '' }}>All Logs</option>
                            @foreach($logTypes as $type)
                                <option value="{{ $type }}" {{ $logType === $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }} Logs
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Log Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($logs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $log->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($log->log_type) }}</td>
                                <td class="px-6 py-4">{{ $log->action }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection