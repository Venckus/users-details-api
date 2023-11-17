<?php

namespace App\Domains\Users\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    public const SORT_BY = 'sort_by';
    public const SORT = 'sort';
    public const PAGE = 'page';
    public const PER_PAGE = 'per_page';

    public UserIndexData $userIndexData;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            User::FIRST_NAME => 'string|max:255',
            User::LAST_NAME => 'string|max:255',
            User::EMAIL => 'email',
            self::SORT_BY => 'string|in:first_name,last_name,email',
            self::SORT => 'string|in:asc,desc',
            self::PAGE => 'integer|min:1',
            self::PER_PAGE => 'integer|min:1|max:100',
        ];
    }

    public function passedValidation(): void
    {
        $this->userIndexData = new UserIndexData(
            first_name: $this->input(User::FIRST_NAME),
            last_name: $this->input(User::LAST_NAME),
            email: $this->input(User::EMAIL),
            sort_by: $this->input(self::SORT_BY),
            sort: $this->input(self::SORT),
            page: $this->input(self::PAGE),
            per_page: $this->input(self::PER_PAGE, 10),
        );
    }
}