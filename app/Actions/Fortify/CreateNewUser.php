<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
  use PasswordValidationRules;

  /**
   * Validate and create a newly registered user.
   *
   * @param  array<string, string>  $input
   */
  public function create(array $input): User
  {
      Validator::make($input, [
          'name' => ['required', 'string', 'max:255'],
          'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
          'password' => $this->passwordRules(),
          'role' => ['required', 'string', 'in:guest,student,alumni'], // Added role validation
          'student_id' => ['nullable', 'string', 'max:255', 'unique:users'], // Added student_id validation
          'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
      ])->validate();

      return User::create([
          'name' => $input['name'],
          'email' => $input['email'],
          'password' => Hash::make($input['password']),
          'role' => $input['role'], // Store the selected role
          'student_id' => $input['student_id'] ?? null, // Store student_id if provided
      ]);
  }
}
