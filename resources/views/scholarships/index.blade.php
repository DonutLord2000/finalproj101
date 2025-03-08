<x-app-layout>
    @section('title', 'GRC - Scholarship')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholarship Program') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="bg-red-700 p-8 flex flex-col justify-center">
                        <h1 class="text-3xl md:text-4xl font-bold text-yellow-300 mb-4">
                            WE OFFER<br>100% SCHOLARSHIP<br>APPLY NOW!
                        </h1>
                        <p class="text-white text-sm">In Partnership of GRC and MLALAF</p>
                    </div>
                    <div class="bg-gray-800 p-8 flex items-center justify-center">
                        <div class="w-48 h-48 md:w-64 md:h-64 rounded-full bg-blue-600 flex items-center justify-center relative">
                            <div class="text-center text-white">
                                <div class="text-yellow-300 text-sm md:text-base font-light">life & livelihood</div>
                                <div class="text-2xl md:text-3xl font-bold">Motortrade</div>
                                <div class="text-yellow-300 text-sm md:text-base font-light">assistance foundation</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Main Content -->
                <div class="md:col-span-3">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">GRC-MLALAF SCHOLARSHIP</h2>
                            
                            <!-- Tabs -->
                            <div class="mb-6 border-b">
                                <div class="flex flex-wrap -mb-px">
                                    @foreach($tabs as $index => $tab)
                                        <button 
                                            class="tab-button mr-2 inline-block p-4 border-b-2 rounded-t-lg {{ $index === 0 ? 'border-blue-600 text-blue-600 active' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                                            data-tab="{{ $tab->id }}"
                                        >
                                            {{ $tab->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Tab Content -->
                            @foreach($tabs as $index => $tab)
                                <div 
                                    id="tab-content-{{ $tab->id }}" 
                                    class="tab-content {{ $index === 0 ? 'block' : 'hidden' }}"
                                >
                                    {!! $tab->content !!}
                                </div>
                            @endforeach
                            
                            <!-- Action Buttons -->
                            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                                @if($activeForm)
                                    <a href="{{ route('scholarships.download-form', $activeForm) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                        </svg>
                                        Download Application Form
                                    </a>
                                @endif
                                <a href="{{ route('scholarships.apply') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Apply for Scholarship
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Details Sidebar -->
                <div class="md:col-span-1">
                    <div class="bg-gray-700 text-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-4">CONTACT DETAILS</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="font-semibold">Email:</p>
                                    <a href="mailto:scholarship@grc.edu.ph" class="text-blue-300 hover:underline">scholarship@grc.edu.ph</a>
                                </div>
                                <div>
                                    <p class="font-semibold">Location:</p>
                                    <p>Scholarship's office, 3rd Floor, GRC Building.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.getAttribute('data-tab');
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Show the selected tab content
                    document.getElementById(`tab-content-${tabId}`).classList.remove('hidden');
                    
                    // Update active tab styling
                    tabButtons.forEach(btn => {
                        btn.classList.remove('border-blue-600', 'text-blue-600', 'active');
                        btn.classList.add('border-transparent');
                    });
                    
                    button.classList.add('border-blue-600', 'text-blue-600', 'active');
                    button.classList.remove('border-transparent');
                });
            });
        });
    </script>
</x-app-layout>

