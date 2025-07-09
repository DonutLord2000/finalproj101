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
         <x-authentication-card class="bg-white bg-opacity-80 p-8 rounded-lg shadow-lg backdrop-blur-none transition-all duration-300 ease-in-out">
             <div>
                 <img src="{{ asset('images/grc.png') }}" alt="Logo" style="width: 350px; height: 170px;">
             </div>

     <x-validation-errors class="mb-4" />

     <!-- Requirements Not Met Toast -->
     <div id="requirements-toast" class="fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 scale-0 opacity-0 z-50 flex items-center">
         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
         </svg>
         <span>Requirements not met - please check highlighted fields</span>
     </div>

     <form method="POST" action="{{ route('register') }}" id="registration-form" enctype="multipart/form-data">
         @csrf

         <div class="flex space-x-4 relative">
             <div class="w-1/2">
                 <x-label for="first_name" value="{{ __('First Name') }}" />
                 <x-input
                     id="first_name"
                     class="block mt-1 w-full transition-all duration-200"
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
                     class="block mt-1 w-full transition-all duration-200"
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
             <x-input id="email" class="block mt-1 w-full transition-all duration-200" type="email" name="email" :value="old('email')" required autocomplete="username" />
         </div>

         <div class="mt-4">
             <x-label for="password" value="{{ __('Password') }}" />
             <div class="relative">
                 <input id="password" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm pr-10 transition-all duration-200" type="password" name="password" required autocomplete="new-password" oninput="checkPasswordStrength(); checkPasswordMatch();" />
                 <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" onclick="togglePassword('password', 'eye-icon-password')">
                     <svg id="eye-icon-password" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.27.842-.678 1.633-1.21 2.344M15.73 15.73a9 9 0 01-9.458 0"></path>
                     </svg>
                 </span>
             </div>

             <!-- Password Strength Indicators (Initially Hidden) -->
             <div id="password-requirements" class="mt-2 space-y-1 hidden">
                 <div class="text-sm font-medium text-gray-700 mb-2">Password Requirements:</div>
                 <div id="length-check" class="flex items-center text-sm text-red-500 transition-all duration-200">
                     <span class="w-4 h-4 mr-2 rounded-full border-2 border-red-500 flex items-center justify-center transition-all duration-200">
                         <span class="w-2 h-2 rounded-full bg-red-500 hidden transition-all duration-200"></span>
                     </span>
                     At least 8 characters
                 </div>
                 <div id="uppercase-check" class="flex items-center text-sm text-red-500 transition-all duration-200">
                     <span class="w-4 h-4 mr-2 rounded-full border-2 border-red-500 flex items-center justify-center transition-all duration-200">
                         <span class="w-2 h-2 rounded-full bg-red-500 hidden transition-all duration-200"></span>
                     </span>
                     At least one uppercase letter (A-Z)
                 </div>
                 <div id="lowercase-check" class="flex items-center text-sm text-red-500 transition-all duration-200">
                     <span class="w-4 h-4 mr-2 rounded-full border-2 border-red-500 flex items-center justify-center transition-all duration-200">
                         <span class="w-2 h-2 rounded-full bg-red-500 hidden transition-all duration-200"></span>
                     </span>
                     At least one lowercase letter (a-z)
                 </div>
                 <div id="number-check" class="flex items-center text-sm text-red-500 transition-all duration-200">
                     <span class="w-4 h-4 mr-2 rounded-full border-2 border-red-500 flex items-center justify-center transition-all duration-200">
                         <span class="w-2 h-2 rounded-full bg-red-500 hidden transition-all duration-200"></span>
                     </span>
                     At least one number (0-9)
                 </div>
                 <div id="special-check" class="flex items-center text-sm text-red-500 transition-all duration-200">
                     <span class="w-4 h-4 mr-2 rounded-full border-2 border-red-500 flex items-center justify-center transition-all duration-200">
                         <span class="w-2 h-2 rounded-full bg-red-500 hidden transition-all duration-200"></span>
                     </span>
                     At least one special character (@, #, $, %, etc.)
                 </div>
             </div>
         </div>

         <div class="mt-4">
             <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
             <div class="relative">
                 <input id="password_confirmation" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm pr-10 transition-all duration-200" type="password" name="password_confirmation" required autocomplete="new-password" oninput="checkPasswordMatch();" />
                 <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" onclick="togglePassword('password_confirmation', 'eye-icon-confirm-password')">
                     <svg id="eye-icon-confirm-password" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.27.842-.678 1.633-1.21 2.344M15.73 15.73a9 9 0 01-9.458 0"></path>
                     </svg>
                 </span>
             </div>

             <!-- Password Match Notification -->
             <div id="password-match-notification" class="mt-2 text-sm hidden">
                 <div id="password-match-success" class="text-green-600 hidden transition-all duration-200">
                     ✓ Passwords match
                 </div>
                 <div id="password-match-error" class="text-red-600 hidden transition-all duration-200">
                     ✗ Passwords do not match
                 </div>
             </div>
         </div>

         <!-- Account Type Selection (Radio Boxes) -->
         <div class="mt-4">
             <x-label value="{{ __('Account Type') }}" />
             <div class="flex flex-row space-x-3">
                 <!-- Guest Box -->
                 <label for="role-guest" class="role-box group relative block cursor-pointer rounded-lg border-2 p-4 text-center shadow-sm transition-all duration-300 ease-in-out hover:shadow-md flex-1
                   bg-gray-100 border-gray-300 text-gray-800
                   group-[.is-selected]:bg-blue-500 group-[.is-selected]:border-blue-600 group-[.is-selected]:text-white">
                   <input type="radio" name="role" id="role-guest" value="guest" class="sr-only" {{ old('role', 'guest') == 'guest' ? 'checked' : '' }} onchange="handleRoleChange(this)">
                   <span class="font-semibold text-lg">Guest</span>
                   <div class="absolute inset-0 rounded-lg transition-all duration-300 ease-in-out group-[.is-selected]:shadow-[0_0_15px_rgba(59,130,246,0.7)]"></div>
               </label>

               <!-- Student Box -->
               <label for="role-student" class="role-box group relative block cursor-pointer rounded-lg border-2 p-4 text-center shadow-sm transition-all duration-300 ease-in-out hover:shadow-md flex-1
                   bg-gray-100 border-gray-300 text-gray-800
                   group-[.is-selected]:bg-yellow-500 group-[.is-selected]:border-yellow-600 group-[.is-selected]:text-gray-900">
                   <input type="radio" name="role" id="role-student" value="student" class="sr-only" {{ old('role') == 'student' ? 'checked' : '' }} onchange="handleRoleChange(this)">
                   <span class="font-semibold text-lg">Student</span>
                   <div class="absolute inset-0 rounded-lg transition-all duration-300 ease-in-out group-[.is-selected]:shadow-[0_0_15px_rgba(234,179,8,0.7)]"></div>
               </label>

               <!-- Alumni Box -->
               <label for="role-alumni" class="role-box group relative block cursor-pointer rounded-lg border-2 p-4 text-center shadow-sm transition-all duration-300 ease-in-out hover:shadow-md flex-1
                   bg-gray-100 border-gray-300 text-gray-800
                   group-[.is-selected]:bg-green-500 group-[.is-selected]:border-green-600 group-[.is-selected]:text-white">
                   <input type="radio" name="role" id="role-alumni" value="alumni" class="sr-only" {{ old('role') == 'alumni' ? 'checked' : '' }} onchange="handleRoleChange(this)">
                   <span class="font-semibold text-lg">Alumni</span>
                   <div class="absolute inset-0 rounded-lg transition-all duration-300 ease-in-out group-[.is-selected]:shadow-[0_0_15px_rgba(34,197,94,0.7)]"></div>
               </label>
           </div>
       </div>

       <!-- Student/Alumni ID and Picture Upload (Conditional) -->
       <div id="id-verification-fields" class="mt-4 space-y-4">
           <div>
               <x-label for="student_id" value="{{ __('Student/Alumni ID') }}" />
               <x-input
                   id="student_id"
                   class="block mt-1 w-full transition-all duration-200"
                   type="text"
                   name="student_id"
                   :value="old('student_id')"
               />
           </div>
           <div>
               <x-label for="id_picture" value="{{ __('Physical ID Picture') }}" />
               <div class="flex items-end gap-4">
                   <input
                       id="id_picture"
                       class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm transition-all duration-200"
                       type="file"
                       name="id_picture"
                       accept="image/*"
                   />
               </div>
           </div>
           <!-- Loading and Status Display -->
           <div id="id-verification-loading-status" class="mt-2 text-sm text-gray-600 flex items-center hidden-visually">
               <svg class="animate-spin h-5 w-5 mr-3 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                   <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                   <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
               </svg>
               <span id="loading-message"></span>
           </div>
           <div id="id-match-status" class="mt-2 text-sm hidden-visually"></div>
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

           // Function to show requirements toast
           function showRequirementsToast() {
               const toast = document.getElementById('requirements-toast');

               // Show the toast
               toast.classList.remove('scale-0', 'opacity-0');
               toast.classList.add('scale-100', 'opacity-100');

               // Hide after 4 seconds
               setTimeout(() => {
                   toast.classList.remove('scale-100', 'opacity-100');
                   toast.classList.add('scale-0', 'opacity-0');
               }, 4000);
           }

           // Function to trigger blinking animation
           function triggerBlink(element) {
               element.classList.add('blink-red');
               setTimeout(() => {
                   element.classList.remove('blink-red');
               }, 1000);
           }

           // Function to trigger field border blinking
           function triggerFieldBlink(fieldId) {
               const field = document.getElementById(fieldId);
               field.classList.add('blink-border-red');
               setTimeout(() => {
                   field.classList.remove('blink-border-red');
               }, 1000);
           }

           // Function to highlight all unmet requirements when register is clicked
           function highlightUnmetRequirements() {
               const firstNameInput = document.getElementById('first_name');
               const lastNameInput = document.getElementById('last_name');
               const emailInput = document.getElementById('email');
               const passwordInput = document.getElementById('password');
               const passwordConfirmInput = document.getElementById('password_confirmation');
               const roleRadios = document.querySelectorAll('input[name="role"]'); // Get all role radios
               const studentIdInput = document.getElementById('student_id');
               const idPictureInput = document.getElementById('id_picture');

               // Check and highlight empty required fields
               if (!firstNameInput.value.trim()) {
                   triggerFieldBlink('first_name');
               }
               if (!lastNameInput.value.trim()) {
                   triggerFieldBlink('last_name');
               }
               if (!emailInput.value.trim()) {
                   triggerFieldBlink('email');
               }
               if (!passwordInput.value.trim()) {
                   triggerFieldBlink('password');
               }
               if (!passwordConfirmInput.value.trim()) {
                   triggerFieldBlink('password_confirmation');
               }

               // Check and highlight password requirements
               const password = passwordInput.value;
               if (password.length > 0) {
                   if (password.length < 8) {
                       triggerBlink(document.getElementById('length-check'));
                   }
                   if (!/[A-Z]/.test(password)) {
                       triggerBlink(document.getElementById('uppercase-check'));
                   }
                   if (!/[a-z]/.test(password)) {
                       triggerBlink(document.getElementById('lowercase-check'));
                   }
                   if (!/[0-9]/.test(password)) {
                       triggerBlink(document.getElementById('number-check'));
                   }
                   if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
                       triggerBlink(document.getElementById('special-check'));
                   }
               }

               // Check password match
               if (passwordInput.value !== passwordConfirmInput.value && passwordConfirmInput.value.length > 0) {
                   triggerFieldBlink('password_confirmation');
                   triggerBlink(document.getElementById('password-match-error'));
               }

               // Check email format
               const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
               if (emailInput.value.length > 0 && !emailRegex.test(emailInput.value)) {
                   triggerFieldBlink('email');
               }

               // Check name format
               const nameRegex = /^[A-Za-z\s\-]+$/;
               if (!nameRegex.test(firstNameInput.value) || !nameRegex.test(lastNameInput.value)) {
                   triggerFieldBlink('first_name');
               }
               if (!nameRegex.test(lastNameInput.value)) {
                   triggerFieldBlink('last_name');
               }

               // Check ID verification fields if applicable
               const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
               if (selectedRole === 'student' || selectedRole === 'alumni') {
                   if (!studentIdInput.value.trim()) {
                       triggerFieldBlink('student_id');
                   }
                   if (!idPictureInput.files.length) {
                       triggerFieldBlink('id_picture');
                   }
                   // Also check the ID verification status
                   const idMatchStatus = document.getElementById('id-match-status');
                   if (idMatchStatus.dataset.verified !== 'true') {
                       idMatchStatus.classList.add('blink-red-text');
                       triggerFieldBlink('student_id');
                       triggerFieldBlink('id_picture');
                   }
               }
           }

           // Password strength checking function
           function checkPasswordStrength() {
               const password = document.getElementById('password').value;
               let hasUnmetRequirements = false;

               // Check minimum length (8 characters)
               const lengthCheck = document.getElementById('length-check');
               const lengthIndicator = lengthCheck.querySelector('span span');
               if (password.length >= 8) {
                   lengthCheck.classList.remove('text-red-500', 'blink-red');
                   lengthCheck.classList.add('text-green-600');
                   lengthCheck.querySelector('span').classList.remove('border-red-500');
                   lengthCheck.querySelector('span').classList.add('border-green-600');
                   lengthIndicator.classList.remove('hidden', 'bg-red-500');
                   lengthIndicator.classList.add('bg-green-600');
               } else {
                   lengthCheck.classList.remove('text-green-600');
                   lengthCheck.classList.add('text-red-500');
                   lengthCheck.querySelector('span').classList.remove('border-green-600');
                   lengthCheck.querySelector('span').classList.add('border-red-500');
                   lengthIndicator.classList.add('hidden');
                   lengthIndicator.classList.remove('bg-green-600');
                   lengthIndicator.classList.add('bg-red-500');
                   if (password.length > 0) {
                       triggerBlink(lengthCheck);
                       hasUnmetRequirements = true;
                   }
               }

               // Check uppercase letters
               const uppercaseCheck = document.getElementById('uppercase-check');
               const uppercaseIndicator = uppercaseCheck.querySelector('span span');
               if (/[A-Z]/.test(password)) {
                   uppercaseCheck.classList.remove('text-red-500', 'blink-red');
                   uppercaseCheck.classList.add('text-green-600');
                   uppercaseCheck.querySelector('span').classList.remove('border-red-500');
                   uppercaseCheck.querySelector('span').classList.add('border-green-600');
                   uppercaseIndicator.classList.remove('hidden', 'bg-red-500');
                   uppercaseIndicator.classList.add('bg-green-600');
               } else {
                   uppercaseCheck.classList.remove('text-green-600');
                   uppercaseCheck.classList.add('text-red-500');
                   uppercaseCheck.querySelector('span').classList.remove('border-green-600');
                   uppercaseCheck.querySelector('span').classList.add('border-red-500');
                   uppercaseIndicator.classList.add('hidden');
                   uppercaseIndicator.classList.remove('bg-green-600');
                   uppercaseIndicator.classList.add('bg-red-500');
                   if (password.length > 0) {
                       triggerBlink(uppercaseCheck);
                       hasUnmetRequirements = true;
                   }
               }

               // Check lowercase letters
               const lowercaseCheck = document.getElementById('lowercase-check');
               const lowercaseIndicator = lowercaseCheck.querySelector('span span');
               if (/[a-z]/.test(password)) {
                   lowercaseCheck.classList.remove('text-red-500', 'blink-red');
                   lowercaseCheck.classList.add('text-green-600');
                   lowercaseCheck.querySelector('span').classList.remove('border-red-500');
                   lowercaseCheck.querySelector('span').classList.add('border-green-600');
                   lowercaseIndicator.classList.remove('hidden', 'bg-red-500');
                   lowercaseIndicator.classList.add('bg-green-600');
               } else {
                   lowercaseCheck.classList.remove('text-green-600');
                   lowercaseCheck.classList.add('text-red-500');
                   lowercaseCheck.querySelector('span').classList.remove('border-green-600');
                   lowercaseCheck.querySelector('span').classList.add('border-red-500');
                   lowercaseIndicator.classList.add('hidden');
                   lowercaseIndicator.classList.remove('bg-green-600');
                   lowercaseIndicator.classList.add('bg-red-500');
                   if (password.length > 0) {
                       triggerBlink(lowercaseCheck);
                       hasUnmetRequirements = true;
                   }
               }

               // Check numbers
               const numberCheck = document.getElementById('number-check');
               const numberIndicator = numberCheck.querySelector('span span');
               if (/[0-9]/.test(password)) {
                   numberCheck.classList.remove('text-red-500', 'blink-red');
                   numberCheck.classList.add('text-green-600');
                   numberCheck.querySelector('span').classList.remove('border-red-500');
                   numberCheck.querySelector('span').classList.add('border-green-600');
                   numberIndicator.classList.remove('hidden', 'bg-red-500');
                   numberIndicator.classList.add('bg-green-600');
               } else {
                   numberCheck.classList.remove('text-green-600');
                   numberCheck.classList.add('text-red-500');
                   numberCheck.querySelector('span').classList.remove('border-green-600');
                   numberCheck.querySelector('span').classList.add('border-red-500');
                   numberIndicator.classList.add('hidden');
                   numberIndicator.classList.remove('bg-green-600');
                   numberIndicator.classList.add('bg-red-500');
                   if (password.length > 0) {
                       triggerBlink(numberCheck);
                       hasUnmetRequirements = true;
                   }
               }

               // Check special characters
               const specialCheck = document.getElementById('special-check');
               const specialIndicator = specialCheck.querySelector('span span');
               if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
                   specialCheck.classList.remove('text-red-500', 'blink-red');
                   specialCheck.classList.add('text-green-600');
                   specialCheck.querySelector('span').classList.remove('border-red-500');
                   specialCheck.querySelector('span').classList.add('border-green-600');
                   specialIndicator.classList.remove('hidden', 'bg-red-500');
                   specialIndicator.classList.add('bg-green-600');
               } else {
                   specialCheck.classList.remove('text-green-600');
                   specialCheck.classList.add('text-red-500');
                   specialCheck.querySelector('span').classList.remove('border-green-600');
                   specialCheck.querySelector('span').classList.add('border-red-500');
                   specialIndicator.classList.add('hidden');
                   specialIndicator.classList.remove('bg-green-600');
                   specialIndicator.classList.add('bg-red-500');
                   if (password.length > 0) {
                       triggerBlink(specialCheck);
                       hasUnmetRequirements = true;
                   }
               }

               // Trigger password field blink if there are unmet requirements
               if (hasUnmetRequirements && password.length > 0) {
                   triggerFieldBlink('password');
               }
           }

           // Password match checking function
           function checkPasswordMatch() {
               const password = document.getElementById('password').value;
               const confirmPassword = document.getElementById('password_confirmation').value;
               const notification = document.getElementById('password-match-notification');
               const successMsg = document.getElementById('password-match-success');
               const errorMsg = document.getElementById('password-match-error');

               // Only show notification if confirm password field has content
               if (confirmPassword.length > 0) {
                   notification.classList.remove('hidden');

                   if (password === confirmPassword) {
                       successMsg.classList.remove('hidden');
                       errorMsg.classList.add('hidden');
                       // Remove any blinking from confirm password field
                       document.getElementById('password_confirmation').classList.remove('blink-border-red');
                   } else {
                       successMsg.classList.add('hidden');
                       errorMsg.classList.remove('hidden');
                       // Trigger blinking for both the error message and confirm password field
                       triggerBlink(errorMsg);
                       triggerFieldBlink('password_confirmation');
                   }
               } else {
                   notification.classList.add('hidden');
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

           // --- New Functions for Account Type and ID Verification ---

           // Function to handle role box selection and update UI
           function handleRoleChange(radio) {
               const roleBoxes = document.querySelectorAll('.role-box');
               roleBoxes.forEach(box => {
                   box.classList.remove('is-selected');
               });

               if (radio.checked) {
                   radio.closest('.role-box').classList.add('is-selected');
               }
               toggleIdFields(); // Call the existing function to show/hide ID fields
           }

           function toggleIdFields() {
               const selectedRoleRadio = document.querySelector('input[name="role"]:checked');
               const selectedRole = selectedRoleRadio ? selectedRoleRadio.value : 'guest'; // Default to guest
               const idVerificationFields = document.getElementById('id-verification-fields');
               const studentIdInput = document.getElementById('student_id');
               const idPictureInput = document.getElementById('id_picture');
               const idMatchStatus = document.getElementById('id-match-status');
               const registerButton = document.querySelector('#registration-form button[type="submit"]');
               const loadingStatusDiv = document.getElementById('id-verification-loading-status');

               if (selectedRole === 'student' || selectedRole === 'alumni') {
                   // Show fields instantly
                   idVerificationFields.style.display = 'block';
                   idVerificationFields.style.maxHeight = 'none'; // Remove max-height constraint
                   idVerificationFields.style.opacity = '1';

                   studentIdInput.setAttribute('required', 'true');
                   idPictureInput.setAttribute('required', 'true');
                   registerButton.disabled = false; // Enable register button, validation will happen on click
                   // Ensure loading status is hidden when ID fields are shown initially
                   loadingStatusDiv.classList.add('hidden-visually'); // Use new class for initial hide
                   idMatchStatus.classList.add('hidden-visually'); // Ensure match status is hidden
               } else {
                   // Hide fields instantly
                   idVerificationFields.style.display = 'none';
                   idVerificationFields.style.maxHeight = '0';
                   idVerificationFields.style.opacity = '0';

                   studentIdInput.removeAttribute('required');
                   idPictureInput.removeAttribute('required');
                   // Clear validation status when hidden
                   idMatchStatus.classList.add('hidden-visually'); // Use new class for hide
                   idMatchStatus.innerHTML = '';
                   idMatchStatus.dataset.verified = 'false';
                   studentIdInput.classList.remove('border-green-500', 'border-red-500', 'blink-border-red');
                   idPictureInput.classList.remove('border-green-500', 'border-red-500', 'blink-border-red');
                   registerButton.disabled = false; // Enable register button for guest
                   // Ensure loading status is hidden when ID fields are hidden
                   loadingStatusDiv.classList.add('hidden-visually'); // Use new class for hide
               }
           }

           // Modified verifyId to return a promise with verification result
           async function verifyId() {
               const studentIdInput = document.getElementById('student_id');
               const idPictureInput = document.getElementById('id_picture');
               const idMatchStatus = document.getElementById('id-match-status');
               const loadingStatusDiv = document.getElementById('id-verification-loading-status');
               const loadingMessageSpan = document.getElementById('loading-message');

               const studentId = studentIdInput.value.trim();
               const file = idPictureInput.files[0];

               // 1. Immediately hide and clear previous status
               idMatchStatus.classList.add('hidden-visually'); // Ensure it's hidden
               idMatchStatus.innerHTML = ''; // Clear content
               idMatchStatus.dataset.verified = 'false';
               idMatchStatus.classList.remove('text-green-600', 'text-red-600', 'blink-red-text'); // Clear previous colors/blinks
               studentIdInput.classList.remove('border-green-500', 'border-red-500', 'blink-border-red');
               idPictureInput.classList.remove('border-green-500', 'border-red-500', 'blink-border-red');

               if (!studentId || !file) {
                   // If validation fails client-side, show error immediately
                   idMatchStatus.classList.remove('hidden-visually'); // Make visible
                   idMatchStatus.classList.remove('text-green-600');
                   idMatchStatus.classList.add('text-red-600');
                   idMatchStatus.innerHTML = 'Please enter your ID and upload a picture before registering.';
                   triggerFieldBlink('student_id');
                   triggerFieldBlink('id_picture');
                   return { isValid: false, message: 'Missing ID number or picture.' };
               }

               // Show loading status
               loadingStatusDiv.classList.remove('hidden-visually');
               loadingMessageSpan.textContent = 'Uploading image...';

               return new Promise((resolve) => {
                   const reader = new FileReader();
                   reader.readAsDataURL(file);
                   reader.onloadend = async function() {
                       const imageData = reader.result;

                       loadingMessageSpan.textContent = 'Analyzing ID...';

                       try {
                           const response = await fetch('/api/verify-id', {
                               method: 'POST',
                               headers: {
                                   'Content-Type': 'application/json',
                                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                               },
                               body: JSON.stringify({ idNumber: studentId, imageData: imageData }),
                           });

                           loadingMessageSpan.textContent = 'Verifying ID...';

                           const data = await response.json();

                           // Hide loading status
                           loadingStatusDiv.classList.add('hidden-visually');
                           loadingMessageSpan.textContent = '';

                           // Make status element visible before setting content to ensure proper rendering
                           idMatchStatus.classList.remove('hidden-visually');
                           void idMatchStatus.offsetWidth; // Force reflow for immediate visibility

                           // Set the message and colors
                           if (data.isValid) {
                               idMatchStatus.classList.add('text-green-600');
                               idMatchStatus.innerHTML = `✓ ${data.message}`;
                               studentIdInput.classList.add('border-green-500');
                               idPictureInput.classList.add('border-green-500');
                               idMatchStatus.dataset.verified = 'true';
                           } else {
                               idMatchStatus.classList.add('text-red-600');
                               idMatchStatus.innerHTML = `✗ ${data.message}`;
                               studentIdInput.classList.add('border-red-500');
                               idPictureInput.classList.add('border-red-500');
                               idMatchStatus.dataset.verified = 'false';
                               triggerBlink(idMatchStatus);
                               triggerFieldBlink('student_id');
                               triggerFieldBlink('id_picture');
                           }

                           resolve({ isValid: data.isValid, message: data.message });

                       } catch (error) {
                           console.error('Error during ID verification:', error);
                           loadingStatusDiv.classList.add('hidden-visually');
                           loadingMessageSpan.textContent = '';

                           idMatchStatus.classList.remove('hidden-visually'); // Make visible
                           void idMatchStatus.offsetWidth; // Force reflow
                           idMatchStatus.classList.add('text-red-600');
                           idMatchStatus.innerHTML = 'An error occurred during ID verification. Please try again.';
                           idMatchStatus.dataset.verified = 'false';
                           triggerBlink(idMatchStatus);

                           resolve({ isValid: false, message: 'An error occurred during ID verification.' });
                       }
                   };
               });
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
               const roleRadios = document.querySelectorAll('input[name="role"]'); // Get all role radios
               const studentIdInput = document.getElementById('student_id');
               const idPictureInput = document.getElementById('id_picture');
               const idMatchStatus = document.getElementById('id-match-status');
               const passwordRequirementsDiv = document.getElementById('password-requirements');
               const idVerificationFields = document.getElementById('id-verification-fields');
               const loadingStatusDiv = document.getElementById('id-verification-loading-status');


               // Initialize form if there are old values
               updateFullName();

               // Initialize role selection and ID fields visibility
               const initialRoleRadio = document.querySelector('input[name="role"]:checked');
               if (initialRoleRadio) {
                   handleRoleChange(initialRoleRadio);
               } else {
                   // Default to guest if no old value or invalid old value
                   const guestRadio = document.getElementById('role-guest');
                   if (guestRadio) {
                       guestRadio.checked = true;
                       handleRoleChange(guestRadio);
                   }
               }

               // Set initial state for ID verification fields based on current role
               const currentSelectedRole = document.querySelector('input[name="role"]:checked')?.value;
               if (currentSelectedRole === 'student' || currentSelectedRole === 'alumni') {
                   idVerificationFields.style.display = 'block'; // Ensure it's block initially
                   idVerificationFields.style.maxHeight = 'none'; // Remove max-height constraint
                   idVerificationFields.style.opacity = '1';
               } else {
                   idVerificationFields.style.maxHeight = '0';
                   idVerificationFields.style.opacity = '0';
                   idVerificationFields.style.display = 'none'; // Hide initially
               }

               // Set initial state for loading status and match status (always hidden on load)
               loadingStatusDiv.classList.add('hidden-visually');
               idMatchStatus.classList.add('hidden-visually');


               // Add input event listeners to update the combined name
               firstNameInput.addEventListener('input', updateFullName);
               lastNameInput.addEventListener('input', updateFullName);

               // Password field focus/blur listeners for smooth dropdown
               passwordInput.addEventListener('focus', () => {
                   passwordRequirementsDiv.classList.remove('hidden');
                   passwordRequirementsDiv.style.maxHeight = passwordRequirementsDiv.scrollHeight + 'px'; // Set to actual height
                   passwordRequirementsDiv.style.opacity = '1';
               });
               passwordInput.addEventListener('blur', () => {
                   // Always hide on blur
                   passwordRequirementsDiv.style.maxHeight = '0';
                   passwordRequirementsDiv.style.opacity = '0';
                   // Add 'hidden' class after transition completes to ensure it's truly hidden for screen readers etc.
                   setTimeout(() => {
                       passwordRequirementsDiv.classList.add('hidden');
                   }, 300); // Match CSS transition duration
               });


               // Validate form before submission
               submitButton.addEventListener('click', async function(e) { // Made async
                   e.preventDefault();

                   // Ensure names are properly capitalized before submission
                   firstNameInput.value = capitalizeNameParts(firstNameInput.value.trim());
                   lastNameInput.value = capitalizeNameParts(lastNameInput.value.trim());
                   updateFullName();

                   // Check if all required fields are filled and valid
                   const formIsValid = await validateForm(); // Await the validation result

                   if (formIsValid) {
                       eulaModal.classList.remove('hidden');
                   } else {
                       // Show toast and highlight unmet requirements instead of alert
                       showRequirementsToast();
                       highlightUnmetRequirements();
                   }
               });

               async function validateForm() { // Made async
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

                   // Check password strength requirements
                   const password = passwordInput.value;
                   if (password.length < 8 ||
                       !/[A-Z]/.test(password) ||
                       !/[a-z]/.test(password) ||
                       !/[0-9]/.test(password) ||
                       !/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
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

                   // Check ID verification fields if applicable
                   const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
                   if (selectedRole === 'student' || selectedRole === 'alumni') {
                       // Trigger ID verification and await its result
                       const verificationResult = await verifyId();
                       if (!verificationResult.isValid) {
                           return false;
                       }
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

           .fixed {
               position: fixed;
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

           .top-4 {
               top: 1rem;
           }

           .right-4 {
               right: 1rem;
           }

           .pr-3 {
               padding-right: 0.75rem;
           }

           .px-6 {
               padding-left: 1.5rem;
               padding-right: 1.5rem;
           }

           .py-3 {
               padding-top: 0.75rem;
               padding-bottom: 0.75rem;
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

           .duration-200 {
               transition-duration: 200ms;
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

           .z-50 {
               z-index: 50;
           }

           /* Blinking animations */
           @keyframes blinkRed {
               0%, 50%, 100% {
                   color: #dc2626;
               }
               25%, 75% {
                   color: #ef4444;
                   text-shadow: 0 0 5px rgba(239, 68, 68, 0.5);
               }
           }

           @keyframes blinkBorderRed {
               0%, 50%, 100% {
                   border-color: #dc2626;
                   box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
               }
               25%, 75% {
                   border-color: #ef4444;
                   box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
               }
           }

           @keyframes blinkRedText {
               0%, 50%, 100% {
                   color: #dc2626;
               }
               25%, 75% {
                   color: #ef4444;
               }
           }

           .blink-red {
               animation: blinkRed 1s ease-in-out;
           }

           .blink-border-red {
               animation: blinkBorderRed 1s ease-in-out;
           }

           .blink-red-text {
               animation: blinkRedText 1s ease-in-out;
           }

           /* Enhanced transitions for smoother effects */
           input[type="password"], input[type="text"], input[type="email"], select, input[type="file"] {
               transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
           }

           .text-red-500, .text-green-600 {
               transition: color 0.2s ease-in-out, text-shadow 0.2s ease-in-out;
           }

           /* Toast styling */
           .bg-red-600 {
               background-color: #dc2626;
           }

           .rounded-lg {
               border-radius: 0.5rem;
           }

           .shadow-lg {
               box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
           }

           .flex {
               display: flex;
           }

           .items-center {
               align-items: center;
           }

           .w-5 {
               width: 1.25rem;
           }

           .h-5 {
               height: 1.25rem;
           }

           .mr-2 {
               margin-right: 0.5rem;
           }

           /* Password requirements dropdown animation */
           #password-requirements {
               max-height: 0;
               opacity: 0;
               overflow: hidden;
               transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
           }

           #password-requirements.hidden {
               /* This class will be added after the transition to ensure it's truly hidden */
               display: none;
           }

           /* Role Box Styling */
           .role-box {
               /* The base styling for the box */
               border-width: 2px; /* Ensure border is visible */
               flex: 1; /* Make boxes take equal width in the row */
               /* Add transition for background and border colors */
               transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
           }

           /* ID Verification Loading Status & Match Status - Unified visibility */
           #id-verification-loading-status,
           #id-match-status {
               opacity: 1; /* Default visible state */
               transition: opacity 0.3s ease-out, visibility 0.3s ease-out;
           }

           #id-verification-loading-status.hidden-visually,
           #id-match-status.hidden-visually {
               opacity: 0;
               visibility: hidden;
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