<?php


namespace App\Models\Traits\Rules;

use Illuminate\Validation\Rules\Password;

trait UserRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|min:3|max:50|regex:/^[a-zA-Z.,، ]+$/u',
            'nameArabic' => 'nullable|min:3|max:255|regex:/^[\p{Arabic}.,، ]+$/u',
            'email' => 'required|min:5|max:255|email|unique:users,email',
            'password' => [
                'required',
                Password::min(8)
                ->max(64)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'confirmPassword' => 'required|same:password',
            'termsAccepted' => 'required|accepted'
        ];
    }

    public function updatedRules()
    {
        return [
            'name' => 'required',
            'lname' => 'nullable|min:2',
        ];
    }

    public function attachRoleRules()
    {
        return [
            'roles' => 'required'
        ];
    }

    public function userSettingsRules()
    {
        return [
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'first_name' => 'required|min:2',
            'email' => 'required|email|unique:users,email,' . auth()->id() . ',id',
        ];
    }

    public function thumbnailRules()
    {
        return [
            'profile_picture' => 'required|image'
        ];
    }

    public function changePasswordRules()
    {
        return [
            'old_password' => [
                'required',
                Password::min(8)
                ->max(64)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'password' => [
                'required',
                Password::min(8)
                ->max(64)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'confirmPassword' => 'required|same:password'
        ];
    }

    public function loginRules()
    {

        return [
            'email' => 'required|min:5|max:255|email|exists:users,email',
            'password' => [
                'required',
                Password::min(8)
                ->max(64)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ]
        ];
    }

    public function resetPasswordRules()
    {
        return [
            'email' => 'required|min:5|max:255|email|exists:users,email',
            'password' => [
                'required',
                Password::min(8)
                ->max(64)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'confirmPassword' => 'required|same:password',
            'token' => 'required|min:10',
        ];
    }
}
