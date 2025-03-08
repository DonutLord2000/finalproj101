@extends('layouts.alumni')
@section('title', 'GRC - Tracer Study')
@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-2xl p-8 max-w-lg text-center">
        <h1 class="text-3xl font-bold text-gray-800">Thank You!</h1>
        <p class="text-gray-600 mt-4">
            Your response has been submitted and is pending approval. 
            We appreciate your participation in our tracer study.
        </p>
        
        <a href="{{ route('welcome') }}" 
           class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            Return to Home
        </a>
    </div>
</div>
@endsection
