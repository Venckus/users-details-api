<?php

namespace App\Domains\Users\Requests;

use App\Domains\Users\DB\Models\UserDetails;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public UserStoreData $userStoreData;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            User::FIRST_NAME => 'required|string|max:255',
            User::LAST_NAME => 'required|string|max:255',
            User::PASSWORD => 'required|string|max:32',
            User::EMAIL => 'required|email|unique:users,email',
            UserDetails::ADDRESS => 'string|max:255',
        ];
    }
    
    public function passedValidation(): void
    {
        $this->userStoreData = new UserStoreData(
            first_name: $this->input(User::FIRST_NAME),
            last_name: $this->input(User::LAST_NAME),
            email: $this->input(User::EMAIL),
            password: $this->input(User::PASSWORD),
            address: $this->input(UserDetails::ADDRESS),
        );
    }
}
