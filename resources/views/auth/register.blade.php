@extends('layouts.authentication') 
    @section('content')
    @section('title', 'GRC - Register')
    <!-- Wrapper for background image and blur effect -->
    <div class="relative min-h-screen">
       <!-- Background image with blur applied -->
       <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/bg.png') }}'); filter: blur(8px);">
       </div>
       <!-- Overlay to darken the background for better contrast -->
       <div class="absolute inset-0 bg-black bg-opacity-50"></div>
       <!-- Content wrapper to ensure form is above the blurred background -->
       <div class="relative flex items-center justify-center min-h-screen">
           <x-authentication-card class="bg-white bg-opacity-80 p-8 rounded-lg shadow-lg backdrop-blur-none">
               <div>
                   <img src="{{ asset('images/grc.png') }}" alt="Logo" style="width: 350px; height: 170px;">
               </div>

       <x-validation-errors class="mb-4" />

       <form method="POST" action="{{ route('register') }}" id="registration-form">
           @csrf

           <div class="flex space-x-4 relative">
               <div class="w-1/2">
                   <x-label for="first_name" value="{{ __('First Name') }}" />
                   <x-input 
                       id="first_name" 
                       class="block mt-1 w-full" 
                       type="text" 
                       name="first_name" 
                       :value="old('first_name')" 
                       required 
                       autofocus 
                       autocomplete="given-name" 
                       onclick="showNameNotification()"
                       pattern="[A-Za-z\s\-]+"
                       title="Only letters, spaces, and hyphens are allowed"
                       oninput="validateNameInput(this)" 
                   />
                   <div class="text-red-500 text-xs mt-1 hidden" id="first-name-error">Only letters, spaces, and hyphens are allowed</div>
               </div>
               <div class="w-1/2">
                   <x-label for="last_name" value="{{ __('Last Name') }}" />
                   <x-input 
                       id="last_name" 
                       class="block mt-1 w-full" 
                       type="text" 
                       name="last_name" 
                       :value="old('last_name')" 
                       required 
                       autocomplete="family-name" 
                       onclick="showNameNotification()"
                       pattern="[A-Za-z\s\-]+"
                       title="Only letters, spaces, and hyphens are allowed"
                       oninput="validateNameInput(this)" 
                   />
                   <div class="text-red-500 text-xs mt-1 hidden" id="last-name-error">Only letters, spaces, and hyphens are allowed</div>
               </div>
               
               <!-- Notification toast positioned under the name fields -->
               <div id="notification-toast" class="absolute -bottom-10 left-0 right-0 bg-red-800 text-white px-4 py-2 rounded shadow-lg transform transition-all duration-300 scale-0 opacity-0 text-center text-sm">
                   Please use your real name for registration purposes.
               </div>
           </div>

           <!-- Hidden field to store the combined name -->
           <input type="hidden" id="name" name="name" value="{{ old('name') }}">

           <div class="mt-4">
               <x-label for="email" value="{{ __('Email') }}" />
               <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
           </div>

           <div class="mt-4">
               <x-label for="password" value="{{ __('Password') }}" />
               <div class="relative">
                   <input id="password" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm pr-10" type="password" name="password" required autocomplete="new-password" />
                   <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" onclick="togglePassword('password', 'eye-icon-password')">
                       <svg id="eye-icon-password" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.27.842-.678 1.633-1.21 2.344M15.73 15.73a9 9 0 01-9.458 0"></path>
                       </svg>
                   </span>
               </div>
           </div>

           <div class="mt-4">
               <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
               <div class="relative">
                   <input id="password_confirmation" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm pr-10" type="password" name="password_confirmation" required autocomplete="new-password" />
                   <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" onclick="togglePassword('password_confirmation', 'eye-icon-confirm-password')">
                       <svg id="eye-icon-confirm-password" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.27.842-.678 1.633-1.21 2.344M15.73 15.73a9 9 0 01-9.458 0"></path>
                       </svg>
                   </span>
               </div>
           </div>

           <script>
               function togglePassword(fieldId, iconId) {
                   const passwordField = document.getElementById(fieldId);
                   const eyeIcon = document.getElementById(iconId);

                   if (passwordField.type === "password") {
                       passwordField.type = "text";
                       eyeIcon.classList.add("text-gray-700");
                       eyeIcon.classList.remove("text-gray-500");
                   } else {
                       passwordField.type = "password";
                       eyeIcon.classList.add("text-gray-500");
                       eyeIcon.classList.remove("text-gray-700");
                   }
               }

               // Show notification toast for 2 seconds
               let notificationTimeout;
               function showNameNotification() {
                   const toast = document.getElementById('notification-toast');
                   
                   // Clear any existing timeout
                   clearTimeout(notificationTimeout);
                   
                   // Show the toast
                   toast.classList.remove('scale-0', 'opacity-0');
                   toast.classList.add('scale-100', 'opacity-100');
                   
                   // Hide after 2 seconds
                   notificationTimeout = setTimeout(() => {
                       toast.classList.remove('scale-100', 'opacity-100');
                       toast.classList.add('scale-0', 'opacity-0');
                   }, 2000);
               }

               // Capitalize the first letter of each word in a string
               function capitalizeWords(str) {
                   // Handle empty strings
                   if (!str) return '';
                   
                   // Split by spaces and hyphens, capitalize each part, then rejoin
                   return str.split(/[\s-]+/).map(word => {
                       if (word.length === 0) return '';
                       return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
                   }).join(' ').replace(/\s-\s/g, '-');
               }

               // Capitalize the first letter of each name part (including hyphenated names)
               function capitalizeNameParts(name) {
                   // Handle hyphenated names like "jean-paul"
                   return name.split('-').map(part => {
                       return part.charAt(0).toUpperCase() + part.slice(1).toLowerCase();
                   }).join('-');
               }

               // Validate name input to allow only letters, spaces, and hyphens
               function validateNameInput(input) {
                   const errorId = input.id === 'first_name' ? 'first-name-error' : 'last-name-error';
                   const errorElement = document.getElementById(errorId);
                   
                   // Regular expression to match only letters, spaces, and hyphens
                   const regex = /^[A-Za-z\s\-]*$/;
                   
                   if (!regex.test(input.value)) {
                       // Remove invalid characters
                       input.value = input.value.replace(/[^A-Za-z\s\-]/g, '');
                       
                       // Show error message
                       errorElement.classList.remove('hidden');
                       setTimeout(() => {
                           errorElement.classList.add('hidden');
                       }, 3000);
                   } else {
                       errorElement.classList.add('hidden');
                   }
                   
                   // Update the combined name field
                   updateFullName();
               }

               // Function to combine first and last name into the hidden name field
               function updateFullName() {
                   const firstNameInput = document.getElementById('first_name');
                   const lastNameInput = document.getElementById('last_name');
                   const nameInput = document.getElementById('name');
                   
                   // Get the values and trim whitespace
                   let firstName = firstNameInput.value.trim();
                   let lastName = lastNameInput.value.trim();
                   
                   // Capitalize the first letter of each name part
                   firstName = capitalizeNameParts(firstName);
                   lastName = capitalizeNameParts(lastName);
                   
                   // Combine the names
                   nameInput.value = firstName + ' ' + lastName;
               }

               // Validate all fields before form submission
               document.addEventListener('DOMContentLoaded', function() {
                   const form = document.getElementById('registration-form');
                   const submitButton = form.querySelector('button[type="submit"]');
                   const eulaModal = document.getElementById('eulaModal');
                   const eulaContent = document.getElementById('eulaContent');
                   const acceptEulaBtn = document.getElementById('acceptEula');
                   const rejectEulaBtn = document.getElementById('rejectEula');
                   
                   const firstNameInput = document.getElementById('first_name');
                   const lastNameInput = document.getElementById('last_name');
                   const emailInput = document.getElementById('email');
                   const passwordInput = document.getElementById('password');
                   const passwordConfirmInput = document.getElementById('password_confirmation');
                   
                   // Initialize form if there are old values
                   updateFullName();
                   
                   // Add input event listeners to update the combined name
                   firstNameInput.addEventListener('input', updateFullName);
                   lastNameInput.addEventListener('input', updateFullName);

                   // Validate form before submission
                   submitButton.addEventListener('click', function(e) {
                       e.preventDefault();
                       
                       // Ensure names are properly capitalized before submission
                       firstNameInput.value = capitalizeNameParts(firstNameInput.value.trim());
                       lastNameInput.value = capitalizeNameParts(lastNameInput.value.trim());
                       updateFullName();
                       
                       // Check if all required fields are filled
                       if (validateForm()) {
                           eulaModal.classList.remove('hidden');
                       } else {
                           // Alert the user to fill all fields
                           alert('Please fill in all required fields correctly before proceeding.');
                       }
                   });

                   function validateForm() {
                       // Check if all required fields are filled and valid
                       if (!firstNameInput.value.trim() || !lastNameInput.value.trim() || 
                           !emailInput.value.trim() || !passwordInput.value.trim() || 
                           !passwordConfirmInput.value.trim()) {
                           return false;
                       }
                       
                       // Check if passwords match
                       if (passwordInput.value !== passwordConfirmInput.value) {
                           return false;
                       }
                       
                       // Check if names contain only valid characters
                       const nameRegex = /^[A-Za-z\s\-]+$/;
                       if (!nameRegex.test(firstNameInput.value) || !nameRegex.test(lastNameInput.value)) {
                           return false;
                       }
                       
                       // Check if email is valid
                       const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                       if (!emailRegex.test(emailInput.value)) {
                           return false;
                       }
                       
                       return true;
                   }

                   eulaContent.addEventListener('scroll', function() {
                       if (eulaContent.scrollTop + eulaContent.clientHeight >= eulaContent.scrollHeight - 10) {
                           acceptEulaBtn.disabled = false;
                           acceptEulaBtn.classList.remove('bg-gray-300', 'text-gray-600');
                           acceptEulaBtn.classList.add('bg-green-500', 'text-white', 'hover:bg-green-600');
                       }
                   });

                   acceptEulaBtn.addEventListener('click', function() {
                       if (!acceptEulaBtn.disabled) {
                           eulaModal.classList.add('hidden');
                           form.submit();
                       }
                   });

                   rejectEulaBtn.addEventListener('click', function() {
                       eulaModal.classList.add('hidden');
                   });
               });
           </script>

           <style>
               .relative {
                   position: relative;
               }

               .absolute {
                   position: absolute;
               }

               .inset-y-0 {
                   top: 50%;
                   transform: translateY(-50%);
               }

               .right-0 {
                   right: 0;
               }

               .-bottom-10 {
                   bottom: -2.5rem;
               }

               .pr-3 {
                   padding-right: 0.75rem;
               }

               .transform {
                   transform: translateX(0);
               }

               .transition-all {
                   transition-property: all;
                   transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
               }

               .duration-300 {
                   transition-duration: 300ms;
               }

               .scale-0 {
                   transform: scale(0);
               }

               .scale-100 {
                   transform: scale(1);
               }

               .opacity-0 {
                   opacity: 0;
               }

               .opacity-100 {
                   opacity: 1;
               }
           </style>

           @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
               <div class="mt-4">
                   <x-label for="terms">
                       <div class="flex items-center">
                           <x-checkbox name="terms" id="terms" required />

                           <div class="ms-2">
                               {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                       'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                       'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                               ]) !!}
                           </div>
                       </div>
                   </x-label>
               </div>
           @endif

           <div class="flex items-center justify-end mt-4">
               <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                   {{ __('Already registered?') }}
               </a>

               <x-button type="submit" class="ms-4 bg-red-800 hover:bg-red-600">
                   {{ __('Register') }}
               </x-button>
           </div>
       </form>

       <!-- EULA Modal -->
       <div id="eulaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
           <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
               <div class="mt-3 text-center">
                   <h3 class="text-lg leading-6 font-medium text-gray-900">End-User License Agreement</h3>
                   <div id="eulaContent" class="mt-2 px-7 py-3 text-left h-96 overflow-y-auto">
                       <p class="text-sm text-gray-500">
                           <!-- Insert your EULA text here -->
                           <h2 class="text-lg font-semibold">End-User License Agreement (EULA)</h2>
                           <p class="mt-2">
                               Welcome to AI-Lumni! By registering or creating an account on this platform, you agree to abide by the terms and conditions outlined in this End-User License Agreement (EULA). Please read carefully before proceeding.
                           </p>
                           <h3 class="mt-4 text-base font-semibold">1. Acceptance of Terms</h3>
                           <p>
                               By creating an account, you acknowledge that you have read, understood, and agree to be bound by this EULA. If you do not agree to these terms, please refrain from registering or using this application.
                           </p>
                           <h3 class="mt-4 text-base font-semibold">2. Permitted Use</h3>
                           <p>
                               This application is designed for the students, alumni, and staff of Global Reciprocal College (GRC) to connect, share information, and access services provided by the institution. You agree to use this application solely for its intended purpose and in compliance with applicable laws and GRC policies.
                           </p>
                           <h3 class="mt-4 text-base font-semibold">3. Account Responsibilities</h3>
                           <p>
                            You are responsible for maintaining the confidentiality of your account credentials.
                            Any activity performed under your account is your responsibility. Notify the administrator immediately if you suspect unauthorized access to your account.
                           </p>
                           <h3 class="mt-4 text-base font-semibold">4. Prohibited Activities</h3>
                           <p>
                            You agree not to:

                            1. Use this application for any unlawful or malicious activity.
                            2. Share or upload inappropriate, false, or misleading information.
                            3. Attempt to access, alter, or damage the application, its data, or its users' information.
                           </p>
                           <h3 class="mt-4 text-base font-semibold">5. Data Collection and Privacy</h3>
                           <p>
                            By registering, you consent to the collection, storage, and processing of your personal data as outlined in our Privacy Policy.
                            GRC is committed to protecting your data and will not share it with third parties without your consent unless required by law.
                           </p>
                           <h3 class="mt-4 text-base font-semibold">6. Intellectual Property</h3>
                           <p>
                            All content, designs, and features of this application are the intellectual property of GRC. Unauthorized reproduction, modification, or distribution is prohibited.
                           </p>
                           <h3 class="mt-4 text-base font-semibold">7. Termination</h3>
                           <p>
                            GRC reserves the right to suspend or terminate your account if you violate this EULA or engage in any activity that disrupts the functionality of this application.
                           </p>
                           <h3 class="mt-4 text-base font-semibold">8. Limitation of Liability</h3>
                           <p>
                            GRC is not liable for any damages resulting from the use or inability to use this application. Use it at your own risk.
                           </p>
                           <h3 class="mt-4 text-base font-semibold">9. Amendments</h3>
                           <p>
                            GRC reserves the right to update or modify this EULA at any time. You will be notified of any changes, and continued use of the application constitutes acceptance of the revised terms.
                           </p>
                           <h3 class="mt-4 text-base font-semibold">10. Governing Law</h3>
                           <p>
                            This EULA is governed by the laws applicable to Global Reciprocal College. Any disputes shall be resolved in accordance with these laws.
                           </p>
                           <p class="mt-4 text-base font-semibold">
                            By clicking "I Agree" or creating an account, you confirm that you have read and understood this EULA and agree to be bound by its terms.
                           </p>
                           <!-- Add more EULA content here -->
                       </p>
                   </div>
                   <div class="items-center px-4 py-3">
                       <button id="acceptEula" class="px-4 py-2 bg-gray-300 text-gray-600 text-base font-medium rounded-md w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300" disabled>
                           I Agree
                       </button>
                       <button id="rejectEula" class="mt-3 px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                           I Disagree
                       </button>
                   </div>
               </div>
           </div>
       </div>
           </x-authentication-card>
       </div>
   </div>
@endsection