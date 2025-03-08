@props(['show' => false])

<div x-data="{ 
    open: {{ $show ? 'true' : 'false' }},
    scrolledToBottom: false,
    checkboxEnabled: false,
    submitEnabled: false,
    showCheckboxTooltip: false,
    showSubmitTooltip: false
}" 
    x-init="
        $watch('open', value => {
            if (value) {
                document.body.classList.add('overflow-hidden');
            } else {
                document.body.classList.remove('overflow-hidden');
            }
        });
        open = {{ $show ? 'true' : 'false' }};
    "
    x-show="open"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true">
    
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <!-- Modal panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="open" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            End User License Agreement
                        </h3>
                        <div class="mt-2">
                            <form id="eula-form" action="{{ route('profile.accept-eula') }}" method="POST">
                                @csrf
                                <div 
                                    id="eula-content" 
                                    class="mt-4 bg-gray-50 p-4 rounded-md h-96 overflow-y-auto text-sm text-gray-600"
                                    @scroll="
                                        if ($event.target.scrollHeight - $event.target.scrollTop <= $event.target.clientHeight + 5) {
                                            scrolledToBottom = true;
                                            checkboxEnabled = true;
                                        }
                                    "
                                >
                                    <h4 class="font-bold mb-2">1. ACCEPTANCE OF TERMS</h4>
                                    <p class="mb-4">By accessing and using this platform, you agree to be bound by this End User License Agreement ("EULA"). If you do not agree to these terms, please do not use this service.</p>
                                    
                                    <h4 class="font-bold mb-2">2. DESCRIPTION OF SERVICE</h4>
                                    <p class="mb-4">This platform provides alumni networking and profile management services. We reserve the right to modify, suspend, or discontinue any part of the service at any time.</p>
                                    
                                    <h4 class="font-bold mb-2">3. USER CONDUCT</h4>
                                    <p class="mb-4">You agree not to use the service for any illegal purposes or to conduct activities that may infringe upon the rights of others. You are solely responsible for all content you post on the platform.</p>
                                    
                                    <h4 class="font-bold mb-2">4. PRIVACY POLICY</h4>
                                    <p class="mb-4">Your use of the service is also governed by our Privacy Policy, which outlines how we collect, use, and protect your personal information.</p>
                                    
                                    <h4 class="font-bold mb-2">5. INTELLECTUAL PROPERTY</h4>
                                    <p class="mb-4">All content on this platform, including text, graphics, logos, and software, is the property of the platform owners and is protected by intellectual property laws.</p>
                                    
                                    <h4 class="font-bold mb-2">6. DISCLAIMER OF WARRANTIES</h4>
                                    <p class="mb-4">The service is provided "as is" without warranties of any kind, either express or implied. We do not guarantee that the service will be error-free or uninterrupted.</p>
                                    
                                    <h4 class="font-bold mb-2">7. LIMITATION OF LIABILITY</h4>
                                    <p class="mb-4">In no event shall we be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or related to your use of the service.</p>
                                    
                                    <h4 class="font-bold mb-2">8. TERMINATION</h4>
                                    <p class="mb-4">We reserve the right to terminate your access to the service for violations of this EULA or for any other reason at our discretion.</p>
                                    
                                    <h4 class="font-bold mb-2">9. GOVERNING LAW</h4>
                                    <p class="mb-4">This EULA shall be governed by and construed in accordance with the laws of the jurisdiction in which the platform operates.</p>
                                    
                                    <h4 class="font-bold mb-2">10. CHANGES TO EULA</h4>
                                    <p class="mb-4">We reserve the right to modify this EULA at any time. Continued use of the service after such modifications constitutes your acceptance of the revised EULA.</p>
                                    
                                    <h4 class="font-bold mb-2">11. CONTACT INFORMATION</h4>
                                    <p>If you have any questions about this EULA, please contact us through the provided channels on the platform.</p>
                                </div>
                                <div class="mt-4 flex items-center relative" x-data="{ showTooltip: false }">
                                    <input 
                                        type="checkbox" 
                                        id="eula-checkbox" 
                                        name="accept_eula" 
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 disabled:opacity-50" 
                                        x-bind:disabled="!checkboxEnabled"
                                        x-on:change="submitEnabled = $event.target.checked"
                                        required
                                        @mouseover="showTooltip = !submitEnabled"
                                        @mouseout="showTooltip = false"
                                    >
                                    <label for="eula-checkbox" class="ml-2 block text-sm" x-bind:class="{'text-gray-400': !checkboxEnabled, 'text-gray-900': checkboxEnabled}">
                                        I have read and agree to the End User License Agreement
                                    </label>
                                    <div x-show="!checkboxEnabled && showTooltip" class="absolute left-0 -top-8 bg-gray-800 text-white text-xs rounded py-1 px-2">
                                        Please read the entire EULA before agreeing
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse relative" x-data="{ showTooltip: false }">
                                    <button 
                                        type="submit" 
                                        id="eula-submit" 
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm"
                                        x-bind:disabled="!submitEnabled"
                                        x-bind:class="{'opacity-50 cursor-not-allowed': !submitEnabled, 'hover:bg-blue-700': submitEnabled}"
                                        @mouseover="showTooltip = !submitEnabled"
                                        @mouseout="showTooltip = false"
                                    >
                                        I Agree
                                    </button>
                                    <div x-show="!submitEnabled && showTooltip" class="absolute right-0 -top-8 bg-gray-800 text-white text-xs rounded py-1 px-2">
                                        Please read and check the agreement
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <a href="{{ route('dashboard') }}" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

