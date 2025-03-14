@extends('layouts.alumni')
@section('title', 'GRC - Tracer Study')
@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
    <div class="bg-white dark:bg-gray-400 shadow-lg rounded-2xl p-8 max-w-lg w-full text-center">
        <h1 class="text-4xl font-extrabold text-red-700">Thank You!</h1>
        <p class="text-white mt-4 leading-relaxed">
            Your response has been successfully submitted and is pending approval. 
            We appreciate your valuable participation in our tracer study.
        </p>

        <a href="{{ route('welcome') }}" 
           class="mt-6 inline-block bg-red-700 text-white px-6 py-2 rounded-lg shadow-md 
                  hover:bg-red-800 transition transform hover:scale-105">
            Return to Home
        </a>
    </div>
</div>
@endsection
